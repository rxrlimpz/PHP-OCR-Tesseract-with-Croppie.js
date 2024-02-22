let croppi;

$('#imageModalContainer').on('shown.bs.modal', function () {
    let width = document.getElementById('crop-image-container').offsetWidth - 20;
    let height = width * 1.2; // Set the height to be 50% of the width
    $('#crop-image-container').height(height + 'px');
    croppi = $('#crop-image-container').croppie({
        viewport: {
            width: width,
            height: height // Set the height to be 50% of the width
        },
    });
    $('.cr-slider-wrap').remove();
    $('.modal-body1').height(document.getElementById('crop-image-container').offsetHeight + 5 + 'px');
    croppi.croppie('bind', {
        url: window.src,
    }).then(function () {
        croppi.croppie('setZoom', 0);
    });
});

//destroy the rendered image in the modal croppie 
$('#imageModalContainer').on('hidden.bs.modal', function () {
    croppi.croppie('destroy');
});

// Function to animate the appearance and text of the button
function animateBtn(value) {
    const button = $('.scanCroppedButton'); // Selecting the button element

    // Checking the boolean value passed
    if (value === true) {
        // Changing classes and text for a successful result
        button.removeClass('orange-button').addClass('green-button'); // Changing button color to green
        button.text("Success");
    } else {
        // Changing classes and text for no results or failure
        button.removeClass('orange-button').addClass('red-button'); // Changing button color to red
        button.text("No Results");
    }
    // Setting a timeout to reset the button appearance and text after 3 seconds
    setTimeout(function () {
        // Removing success/failure classes and adding the default class back
        button.removeClass('green-button red-button').addClass('orange-button');
        button.text("Convert to text"); // Resetting button text to default
    }, 1500); // 1500 milliseconds (1.5 seconds)
}

// Function to run an OCR command by uploading the image file through XHR
$(document).on('click', '.scanCroppedButton', function (ev) {
    // Getting the cropped image using Croppie and converting it to a Blob
    croppi.croppie('result', {
        type: 'blob',
        format: 'jpeg',
        size: 'original'
    }).then(function (blob) {
        // Changing the text of the button to indicate scanning is in progress
        const button = $('.scanCroppedButton');
        button.text("Scanning...");
        // Creating a FormData object to send via XMLHttpRequest
        let formData = new FormData();
        formData.append('croppedImage', blob, 'cropped_image.jpg');
        // Appending 'iterationsMorph' value to the FormData
        var iterations = iterationsMorph;
        formData.append('iterations', iterations);
        // Creating an XMLHttpRequest object and opening a POST request to 'Test_crop.php'
        xhr = new XMLHttpRequest();
        xhr.open('POST', 'Test_crop.php', true);

        let threshold = 1200000; // 2 minutes in milliseconds
        // Setting a timeout to abort the request if it takes too long (2 minutes in this case)
        setTimeout(function () {
            xhr.abort(formData);
            console.log("Request aborted due to timeout");
            animateBtn(false);
        }, threshold);


        // Button handlers for aborting the OCR
        $(document).on('click', '.cancel-OCR-Button', function () {
            const button = $('.scanCroppedButton');
            xhr.abort(formData);
            button.removeClass('btn-success btn-danger').addClass('btn-dark');
            button.text("Convert to text"); // Resetting button text to default
        });

        // Handling the response when the request is complete
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                // Checking for errors in the response and displaying results accordingly
                if (response.error) {
                    animateBtn(false);
                } else {
                    displayResults(response);
                    animateBtn(true);
                }
            } else {
                // Handling errors if the response status is not 200 (OK)
                animateBtn(false);
            }
        };
        // Sending the FormData object via XMLHttpRequest
        xhr.send(formData);
    });
});

//displat results and reload the image 
function displayResults(data) {
    //create a temporary array to hold the result for filtering and storing into the array for populating text-fields
    var tempSurname = [];
    var tempFirstname = [];
    var tempMiddlename = [];
    var tempSuffixname = [];
    //list of unwanted characters for filtering the results
    var unwantedChars = /[^a-zA-Z0-9,. ]/;

    for (let i = 0; i < data.firstNames.length; i++) {
        let firstName = data.firstNames[i].trim().replace(/\s+/g, ' ').replace(unwantedChars, '');//remove double space, remove unwanted characters
        let lastName = data.lastNames[i].trim().replace(/\s+/g, ' ').replace(unwantedChars, '');//remove double space, remove unwanted characters
        let middleName = data.middleNames[i].trim().replace(/\s+/g, '').replace(unwantedChars, '');//remove double space, remove unwanted characters
        let suffixName = data.suffixNames[i].trim().replace(/\s+/g, '').replace(unwantedChars, '');//remove double space, remove unwanted characters 

        // Remove ',' characters at the end
        if (firstName.endsWith(',')) {
            firstName = firstName.slice(0, -1);// Remove trailing comma
        }
        if (lastName.endsWith(',')) {
            lastName = lastName.slice(0, -1); // Remove trailing comma
        } else if (lastName.startsWith(',')) {
            lastName = lastName.slice(1); // Remove leading comma
        }
        if (middleName.endsWith(',') || middleName.endsWith('.')) {
            middleName = middleName.slice(0, -1);// Remove trailing comma
        }
        if (suffixName.endsWith(',') || suffixName.endsWith('.')) {
            suffixName = suffixName.slice(0, -1);// Remove trailing comma
        }

        tempFirstname.push(firstName); //push the filtered first name
        tempSurname.push(lastName);// push the filtered last name
        tempMiddlename.push(middleName);// push the filtered middle name
        tempSuffixname.push(suffixName);// push the filtered suffix
    }
    // Check for empty input
    var InputEmpty = tempSurname.every(value => value === "" || value === undefined) && tempFirstname.every(value => value === "" || value === undefined);

    if (!InputEmpty) {
        // Check if both arrays have all empty or undefined values
        var tableEmpty = surnameTable.every(value => value === "" || value === undefined) && firstnameTable.every(value => value === "" || value === undefined);
        //clear rows when input surnames and firstnames are empty
        cleanupTable();
        // If the arrays are not empty, run the redundancy check and update tables
        if (!tableEmpty) {
            for (var i = 0; i < tempSurname.length; i++) {
                var isDuplicate = true;
                for (var j = 0; j < surnameTable.length; j++) {
                    // Check for duplicates based on all table values
                    if (
                        surnameTable[j] === tempSurname[i] &&
                        firstnameTable[j] === tempFirstname[i] &&
                        midnameTable[j] === tempMiddlename[i] &&
                        suffixTable[j] === tempSuffixname[i]
                    ) {
                        isDuplicate = true;
                        break;
                    } else {
                        isDuplicate = false;
                    }
                }
                // If not a duplicate, push new data to respective tables
                if (!isDuplicate) {
                    surnameTable.push(tempSurname[i]);
                    firstnameTable.push(tempFirstname[i]);
                    midnameTable.push(tempMiddlename[i]);
                    suffixTable.push(tempSuffixname[i]);
                }
            }
        } else {
            // Clear the tables
            firstnameTable = [];
            surnameTable = [];
            midnameTable = [];
            suffixTable = [];

            // Update the arrays by making them empty before updating new data
            tempSurname.forEach(function (surname, i) {
                surnameTable.push(surname);
                firstnameTable.push(tempFirstname[i]);
                midnameTable.push(tempMiddlename[i]);
                suffixTable.push(tempSuffixname[i]);
            });
        }

        // Call the populateTable function to update the tables with new data
        populateTable();

    } else {
        animateBtn(false);
    }
}
