const openListButton = document.getElementById("openList");
const buttonList = document.getElementById("button-list");
const listItems = Array.from(buttonList.querySelectorAll("li")).reverse(); // Reverse the list items

let isOpen = false;

openListButton.addEventListener("click", function (e) {
  if (!isOpen) {
    this.classList.add("open");

    buttonList.style.display = "block";

    listItems.forEach((item, index) => {
      setTimeout(() => {
        item.style.opacity = 1;
      }, 200 + index * 100);
    });
  } else {
    this.classList.remove("open");

    // Hide the button-list with delayed opacity
    listItems.forEach((item, index) => {
      setTimeout(() => {
        item.style.opacity = 0;
        if (index === listItems.length - 1) {
          setTimeout(() => {
            buttonList.style.display = "none";
          }, 200 + (listItems.length - 1) * 100);
        }
      }, index * 50);
    });
  }

  isOpen = !isOpen; // Toggle the state
});

// Add event listeners to the buttons inside button-list
listItems.forEach((item) => {
  const button = item.querySelector("button");
  button.addEventListener("click", function (e) {
    e.stopPropagation(); // Stop propagation to prevent list from closing
    openListButton.click(); // Simulate a click on openListButton
  });
});

var lastCollArray = [];
var lastCollIDArray = [];
var lastCourseArray = [];
var currentCollArray = [];
var currentCollIDArray = [];
var currentCourseArray = [];

function loadColleges() {
  var collegeContainer = document.getElementById("college-container");

  var xhr = new XMLHttpRequest();
  xhr.open("GET", "get_colleges.php", true);

  xhr.onload = function () {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);

      if (
        lastCollArray.length === 0 &&
        lastCollIDArray.length === 0 &&
        lastCourseArray.length === 0
      ) {
        updateColleges(collegeContainer, response);
        lastCollArray = currentCollArray.slice();
        lastCollIDArray = currentCollIDArray.slice();
        lastCourseArray = currentCourseArray.slice();
      } else {
        if (
          hasChanges(
            response,
            currentCollArray,
            currentCollIDArray,
            currentCourseArray
          )
        ) {
          updateColleges(collegeContainer, response);
          lastCollArray = currentCollArray.slice();
          lastCollIDArray = currentCollIDArray.slice();
          lastCourseArray = currentCourseArray.slice();
        }
      }
    } else {
      console.error("Request failed with status:", xhr.status);
    }
  };

  xhr.send();
}

function hasChanges(
  response,
  currentCollArray,
  currentCollIDArray,
  currentCourseArray
) {
  var newCollArray = [];
  var newCollIDArray = [];
  var newCourseArray = [];

  response.forEach(function (college) {
    newCollArray.push(college.CollegeName);
    newCollIDArray.push(college.CollegeID);
    college.Courses.forEach(function (course) {
      newCourseArray.push(course.CourseName);
    });
  });

  return (
    !arraysEqual(newCollArray, currentCollArray) ||
    !arraysEqual(newCollIDArray, currentCollIDArray) ||
    !arraysEqual(newCourseArray, currentCourseArray)
  );
}

function arraysEqual(arr1, arr2) {
  return JSON.stringify(arr1) === JSON.stringify(arr2);
}

function updateColleges(container, data) {
  container.innerHTML = "";
  currentCollArray = [];
  currentCollIDArray = [];
  currentCourseArray = [];

  data.forEach(function (college) {
    var card = createCollegeCard(college);
    container.appendChild(card);

    currentCollArray.push(college.CollegeName);
    currentCollIDArray.push(college.CollegeID);
    college.Courses.forEach(function (course) {
      currentCourseArray.push(course.CourseName);
    });
  });
}

// Create a card for each college
function createCollegeCard(college) {
  var card = document.createElement("div");
  card.className = "card m-3";
  card.style =
    "width: 20rem; box-shadow: 0px 0px 12px -5px rgba(0, 0, 0, 0.5);";
  card.id = "" + college.CollegeID;

  var cardHeader = document.createElement("div");
  cardHeader.className = "card-header";
  cardHeader.style =
    "height:6rem; background-color: rgb(40, 45, 53); padding:1rem 1.5rem";

  var collegeName = document.createElement("h4");
  collegeName.textContent = college.CollegeName;
  collegeName.style = "color:white;";

  var description = document.createElement("p");
  description.textContent = college.Description;
  description.style = "color:rgba(245, 245, 245, 0.5);";

  var deleteButton = document.createElement("button");
  deleteButton.classList = "btn";
  deleteButton.id = "" + college.CollegeID;
  deleteButton.setAttribute("data-bs-toggle", "modal"); // Open the modal
  deleteButton.setAttribute("data-bs-target", "#deleteModal"); // Specify the modal to open
  deleteButton.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>';
  deleteButton.style =
    "position:absolute; top:0; right:0rem; padding:0rem; background-color:transparent; color:white;";

  deleteButton.onclick = function () {
    var modal = document.getElementById("deleteModal");
    var collegeNameElement = modal.querySelector("#collegeName");
    var deleteModalIDElement = modal.querySelector("#deleteModalID");
    var deleteThisRowButton = modal.querySelector(".deleteThisRow");

    collegeNameElement.textContent = college.CollegeName;
    deleteModalIDElement.textContent = "" + college.CollegeID;
    deleteThisRowButton.setAttribute("id", college.CollegeID);
  };

  var editButton = document.createElement("button");
  editButton.classList = "btn";
  editButton.id = "" + college.CollegeID;
  editButton.setAttribute("data-bs-toggle", "modal");
  editButton.setAttribute("data-bs-target", "#editModal");
  editButton.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" width="0.6rem" height="0.6rem" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg>';
  editButton.style =
    "position:absolute; top:0; right:2em; padding:0rem; background-color:transparent; color:white;";

  editButton.onclick = function () {
    var modal = document.getElementById("editModal");
    $("#selectedCollegeID").val(college.CollegeID);
    $("#editCollegeName").val(college.CollegeName);
    $("#editCollDescription").val(college.Description);
    $("#edit-status").text("");
    // Clear the existing course list in the edit modal
    var editCourseTableBody = document.querySelector("#collegeEdit tbody");
    editCourseTableBody.innerHTML = "";

    // Populate the courses for the selected college in the edit modal
    college.Courses.forEach(function (course) {
      var editCourseRow = editCourseTableBody.insertRow();
      var editCourseCell1 = editCourseRow.insertCell(0);
      var editCourseCell2 = editCourseRow.insertCell(1);

      editCourseCell1.innerHTML =
        '<label class="fw-bold" id="editLabel">Course Name: </label>' +
        '<input type="text" name="col_courseID" value="' +
        course.CourseID +
        '" style="display:none">' +
        '<input type="text" name="col_courseName" value="' +
        course.CourseName +
        '" id="col_courseName">';
      editCourseCell2.innerHTML =
        '<button class="btn deleteCourseID" onclick="removeRow(this)" id="' +
        course.CourseID +
        '">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">' +
        '<path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/></svg></button>';
    });
  };

  cardHeader.appendChild(collegeName);
  cardHeader.appendChild(description);
  cardHeader.appendChild(deleteButton);
  cardHeader.appendChild(editButton);
  card.appendChild(cardHeader);

  var cardBody = document.createElement("div");
  cardBody.className = "card-body";
  cardBody.style = "height:auto; padding:0.7rem;";

  var courseList = document.createElement("ul");
  courseList.className = "list-group list-group-flush";
  courseList.style = "font-weight:bold;";

  college.Courses.forEach(function (course) {
    var courseItem = document.createElement("li");
    courseItem.className = "list-group-item";
    courseItem.textContent = course.CourseName;
    courseList.appendChild(courseItem);
  });

  cardBody.appendChild(courseList);

  card.appendChild(cardHeader);
  card.appendChild(cardBody);

  return card;
}

loadColleges();

setInterval(checkForChangesAndReload, 5000);

function checkForChangesAndReload() {
  loadColleges();
}

function checkRequiredInputs(modal) {
  const requiredInputs = modal.querySelectorAll(
    "input[required], select[required]"
  );
  const confirmButton = modal.querySelector("#open_modal-confirm");

  for (const input of requiredInputs) {
    if (!input.checkValidity()) {
      confirmButton.disabled = true;
      return;
    }
  }

  confirmButton.disabled = false;
}

function setupModalListeners(modal) {
  const confirmButton = modal.querySelector("#open_modal-confirm");
  confirmButton.addEventListener("click", () => {
    checkRequiredInputs(modal);
  });

  const inputs = modal.querySelectorAll("input[required], select[required]");
  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      checkRequiredInputs(modal);
    });
  });

  // Initially, disable the button
  checkRequiredInputs(modal);
}

const modals = document.querySelectorAll(".modal");
modals.forEach((modal) => {
  setupModalListeners(modal);
});

$(document).ready(function () {
  var selectedRowID;
  var docsName;

  $("#deleteModal").on("click", ".deleteThisRow", function (event) {
    event.preventDefault();

    rowData = $(this).attr("id");
    selectedRowID = "";
    selectedRowID = rowData;

    $.ajax({
      type: "GET",
      url: "delete_college.php?id=" + selectedRowID,
      dataType: "json",

      success: function () {
        loadColleges();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert("Error: " + errorThrown);
      },
    });
  });
});

var collegecourseNames = ["", "", "", "", ""];

document.getElementById("col_addButton").addEventListener("click", function () {
  var col_tableBody = document.querySelector("#newCollege tbody");
  var col_newRow = col_tableBody.insertRow();

  // Column 1
  var col_col1 = col_newRow.insertCell(0);
  col_col1.innerHTML =
    '<label class="fw-bold" id="editLabel">Course Name: </label><input type="text" id="col_courseName" name="col_courseName">';

  // Column 2
  var col_col2 = col_newRow.insertCell(1);
  col_courseCell2.innerHTML =
    '<button class="btn" >' +
    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">' +
    '<path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/></svg></button>';

  deleteButton.addEventListener("click", function () {
    col_newRow.remove();
  });
});

for (var i = 0; i < collegecourseNames.length; i++) {
  document.getElementById("col_addButton").click();
  var col_inputFields = document.getElementsByName("col_courseName");
  col_inputFields[i].value = collegecourseNames[i];
}

var courseNames = ["", "", "", "", ""];

document.getElementById("addButton").addEventListener("click", function () {
  var tableBody = document.querySelector("#newCourse tbody");
  var newRow = tableBody.insertRow();

  // Column 1
  var col1 = newRow.insertCell(0);
  col1.innerHTML =
    '<label class="fw-bold" id="editLabel">Course Name: </label><input type="text" id="courseName" name="courseName">';

  // Column 2
  var col2 = newRow.insertCell(1);
  courseCell2.innerHTML =
    '<button class="btn" >' +
    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">' +
    '<path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/></svg></button>';

  deleteButton.addEventListener("click", function () {
    newRow.remove();
  });
});

for (var i = 0; i < courseNames.length; i++) {
  document.getElementById("addButton").click();
  var inputFields = document.getElementsByName("courseName");
  inputFields[i].value = courseNames[i];
}

var deletedCourses = [];

function removeRow(button) {
  event.preventDefault();
  var delete_ID = button.id;

  var row = button.closest("tr");
  row.remove();

  deletedCourses.push(delete_ID);
}

function checkForCourseDuplicate(inputField) {
  var currentValue = inputField.value;
  var inputFields = document.getElementsByName("courseName");
  var index = Array.from(inputFields).indexOf(inputField);

  // Make an AJAX request to check for duplicates
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "checkDuplicate_course.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      if (xhr.responseText === "duplicate") {
        inputField.style.color = "red";
      } else {
        inputField.style.color = "";
      }
    }
  };
  xhr.send("courseName=" + currentValue);
}

for (var i = 0; i < courseNames.length; i++) {
  var inputFields = document.getElementsByName("courseName");
  inputFields[i].addEventListener("input", function (event) {
    checkForCourseDuplicate(event.target);
  });
}

function checkForCollegeDuplicate(inputField) {
  var coll_currentValue = inputField.value;

  // Make an AJAX request to check for duplicates
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "checkDuplicate_college.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      if (xhr.responseText === "duplicate") {
        inputField.style.color = "red";
        document.getElementById("submitAddColl").disabled = true;
      } else {
        inputField.style.color = "";
        document.getElementById("submitAddColl").disabled = false;
      }
    }
  };
  xhr.send("CollegeName=" + coll_currentValue);
}

var inputField = document.getElementsByName("CollegeName")[0];
inputField.addEventListener("input", function (event) {
  checkForCollegeDuplicate(event.target);
});

function checkDuplicateCollegeCourseRealTime(inputField) {
  var courseName = inputField.value;
  var data = {
    courseName: courseName,
  };

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "checkDuplicate_course.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      if (xhr.responseText === "duplicate") {
        inputField.style.color = "red";
      } else {
        inputField.style.color = ""; // Reset the color
      }
    }
  };

  xhr.send("courseName=" + courseName);
}

var col_inputFields = document.getElementsByName("col_courseName");
for (var i = 0; i < col_inputFields.length; i++) {
  col_inputFields[i].addEventListener("input", function () {
    checkDuplicateCollegeCourseRealTime(this);
  });
}

function clearForm() {
  const inputElements = document.querySelectorAll(
    'input[type="text"], input[type="number"]'
  );
  const selectElement = document.querySelectorAll("select");
  inputElements.forEach(function (input) {
    input.value = "";
  });
  selectElement.forEach(function (select) {
    select.value = "default";
  });
}

function college_saveButton() {
  // Create a new FormData object
  var collegeData = new FormData();

  // Get CollID from the select element
  var CollegeName = document.querySelector("input#CollegeName").value;
  var Description = document.querySelector("input#CollDescription").value;

  var dataToSend = {
    CollegeName: CollegeName,
    Description: Description,
  };

  collegeData.append("dataToSend", JSON.stringify(dataToSend));

  var data = [];

  var inputs = document.querySelectorAll("#newCollege input");
  for (var i = 0; i < inputs.length; i++) {
    var name = inputs[i].getAttribute("name");
    var value = inputs[i].value;
    data.push({
      name: name,
      value: value,
    });
  }

  collegeData.append("data", JSON.stringify(data));

  // Perform an AJAX POST request

  $.ajax({
    type: "POST",
    url: "add_college.php",
    data: collegeData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response === "success") {
        loadColleges();
        clearForm();
      } else {
        // Display an error message
        alert("Error: " + response);
      }
    },
  });
}

function course_saveButton() {
  // Create a new FormData object
  var courseData = new FormData();

  // Get CollID from the select element
  var CollID = document.querySelector("select#CollID").value;

  var dataToSend = {
    CollID: CollID,
  };

  courseData.append("dataToSend", JSON.stringify(dataToSend));

  var data = [];

  var inputs = document.querySelectorAll("#newCourse input");
  for (var i = 0; i < inputs.length; i++) {
    var name = inputs[i].getAttribute("name");
    var value = inputs[i].value;
    data.push({
      name: name,
      value: value,
    });
  }

  courseData.append("data", JSON.stringify(data));

  $.ajax({
    type: "POST",
    url: "add_course.php",
    data: courseData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response === "success") {
        loadColleges();
        clearForm();
      } else {
        // Display an error message
        alert("Error: " + response);
      }
    },
  });
}

function updateCollegeData() {
  // Create a new FormData object
  var updateData = new FormData();
  var statusElement = $("#edit-status");
  var collegeID = $("input#selectedCollegeID").val();
  var newCollegeName = $("input#editCollegeName").val();
  var newDescription = $("input#editCollDescription").val();

  var header = {
    collegeID: collegeID,
    newCollegeName: newCollegeName,
    newDescription: newDescription,
  };

  updateData.append("header", JSON.stringify(header));

  var data = [];

  var inputs = document.querySelectorAll("#collegeEdit input");
  for (var i = 0; i < inputs.length; i++) {
    var name = inputs[i].getAttribute("name");
    var value = inputs[i].value;
    data.push({
      id: name,
      value: value,
    });
  }

  updateData.append("data", JSON.stringify(data));
  updateData.append("deletedCourses", JSON.stringify(deletedCourses));

  $.ajax({
    type: "POST",
    url: "update_college.php",
    data: updateData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response === "success") {
        statusElement.text("Updated").css("color", "green");
        loadColleges();
      } else {
        // Display an error message
        statusElement.text("Error: " + response).css("color", "red");
      }
    },
  });
}
