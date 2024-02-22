<?php
// Check if a file was uploaded
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES['croppedImage']['error'] === 0) {
        // Upload the image
        $uploadedFile = $_FILES['croppedImage']['tmp_name'];

        $rawIteration = $_POST['iterations']; //filter value from -5 to 5 total of 11 iterations value 
        $iterations = 0;
        $methodType = '';

        if ($rawIteration >= -10 && $rawIteration <= 10) {
            if ($rawIteration > 0) { //filter value is positive select erosion morphology method
                $methodType = "";
                $methodType = "erosion";
                $iterations = $rawIteration;
            } elseif ($rawIteration < 0) { // filter value is negative select dilation morphology method
                $methodType = "";
                $methodType = "dilation";
                $iterations = abs($rawIteration);
            } elseif ($rawIteration === 0) { //filter no method selected
                $methodType = "";
                $iterations = 0;
            }
        }


        $image = imagecreatefromstring(file_get_contents($uploadedFile));

        if ($image) {

            imagefilter($image, IMG_FILTER_GRAYSCALE); // Convert the image to grayscale
            $threshold = 100;

            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);

            for ($x = 0; $x < $imageWidth; $x++) {
                for ($y = 0; $y < $imageHeight; $y++) {
                    $color = imagecolorat($image, $x, $y);
                    $gray = ($color >> 16) & 0xFF; // Get the grayscale value

                    if ($gray < $threshold) {
                        $newColor = imagecolorallocate($image, 0, 0, 0); // Black
                    } else {
                        $newColor = imagecolorallocate($image, 255, 255, 255); // White
                    }

                    imagesetpixel($image, $x, $y, $newColor);
                }
            }
            // Morphology Methods: Erosion and Dilation
            // Erosion method implementation
            function erosion($image, $iterations)
            {
                for ($i = 0; $i < $iterations; $i++) {
                    // Create a new image of the same size as the input image
                    $output = imagecreatetruecolor(imagesx($image), imagesy($image));

                    // Define white as the background color for the new image
                    $bgColor = imagecolorallocate($output, 255, 255, 255); // White background

                    // Fill the new image with the background color
                    imagefill($output, 0, 0, $bgColor);

                    // Loop through each pixel of the input image
                    for ($x = 1; $x < imagesx($image) - 1; $x++) {
                        for ($y = 1; $y < imagesy($image) - 1; $y++) {
                            $pixelNeighbors = array();

                            // Loop through the neighbors of the current pixel
                            for ($kX = -1; $kX <= 1; $kX++) {
                                for ($kY = -1; $kY <= 1; $kY++) {
                                    // Get the color of the neighboring pixel
                                    $pixel = imagecolorat($image, $x + $kX, $y + $kY);
                                    // Extract the red channel (assuming RGB)
                                    $pixelNeighbors[] = ($pixel >> 16) & 0xFF;
                                }
                            }

                            // Find the minimum pixel value among the neighbors
                            $minPixelValue = min($pixelNeighbors);

                            // Create a new color with the minimum value
                            $newColor = imagecolorallocate($output, $minPixelValue, $minPixelValue, $minPixelValue);

                            // Set the pixel in the new image to the new color
                            imagesetpixel($output, $x, $y, $newColor);
                        }
                    }

                    // Update the image for the next iteration
                    $image = $output;
                }

                return $image;
            }

            // Dilation method implementation
            function dilation($image, $iterations)
            {
                for ($i = 0; $i < $iterations; $i++) {
                    // Create a new image of the same size as the input image
                    $output = imagecreatetruecolor(imagesx($image), imagesy($image));

                    // Define white as the background color for the new image
                    $bgColor = imagecolorallocate($output, 255, 255, 255); // White background

                    // Fill the new image with the background color
                    imagefill($output, 0, 0, $bgColor);

                    // Loop through each pixel of the input image
                    for ($x = 1; $x < imagesx($image) - 1; $x++) {
                        for ($y = 1; $y < imagesy($image) - 1; $y++) {
                            $pixelNeighbors = array();

                            // Loop through the neighbors of the current pixel
                            for ($kX = -1; $kX <= 1; $kX++) {
                                for ($kY = -1; $kY <= 1; $kY++) {
                                    // Get the color of the neighboring pixel
                                    $pixel = imagecolorat($image, $x + $kX, $y + $kY);
                                    // Extract the red channel (assuming RGB)
                                    $pixelNeighbors[] = ($pixel >> 16) & 0xFF;
                                }
                            }

                            // Find the maximum pixel value among the neighbors
                            $maxPixelValue = max($pixelNeighbors);

                            // Create a new color with the maximum value
                            $newColor = imagecolorallocate($output, $maxPixelValue, $maxPixelValue, $maxPixelValue);

                            // Set the pixel in the new image to the new color
                            imagesetpixel($output, $x, $y, $newColor);
                        }
                    }

                    // Update the image for the next iteration
                    $image = $output;
                }

                return $image;
            }


            // Determine the morphology method based on user input
            if (!empty($methodType)) {
                // Perform erosion

                switch ($methodType) {
                    case 'dilation':
                        $image = dilation($image, $iterations);
                        break;
                    case 'erosion':
                        $image = erosion($image, $iterations);
                        break;
                    default:
                        // No method selected
                        break;
                }
            }

            // Save the processed image to a temporary file
            $processedFilePath = 'temp/processed_image.png'; // Specify the path where you want to save the processed image
            imagepng($image, $processedFilePath);

            // Use Tesseract OCR to extract text from the uploaded image
            $command = '"C:\Program Files (x86)\Tesseract-OCR\tesseract.exe" ' . escapeshellarg($processedFilePath) . ' -';

            // Execute the command and capture the output
            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                $textData = array();
                $firstNames = array();
                $lastNames = array();
                $middleNames = array();
                $suffixname = array();
                // Iterate through the $output array line by line
                foreach ($output as $line) {

                    // Remove non-alphabetical characters except for '.', ',' and spaces
                    $cleanedLine = preg_replace('/[^A-Za-z.,\s]/', '', $line);
                    // Replace multiple spaces with a single space)
                    $cleanedLine = preg_replace('/\s+/', ' ', $cleanedLine);
                    $cleanedLine = trim($cleanedLine);


                    $words = explode(' ', $cleanedLine);
                    $capitalizedWords = array_map('ucwords', $words);
                    $cleanedLine = implode(' ', $capitalizedWords);

                    $textData[] = $cleanedLine;
                }

                foreach ($textData as $line) {
                    // Use regular expressions to extract the desired information
                    $suffixname = "";

                    if (preg_match('/([A-Za-z,\s]+)\s*,\s*([A-Za-z\s]+)\s*([A-Za-z]\.)$/', $line, $matches)) {
                        // Handle format: "One or many surname, one or many first names, middle initial (if present)."
                        $surname = $matches[1];
                        $firstname = $matches[2];
                        $middlename = isset($matches[3]) ? $matches[3] : "";;
                        // Append the extracted values to the respective arrays
                        $lastNames[] = $surname;
                        $firstNames[] = $firstname;
                        $middleNames[] = $middlename;
                        $suffixNames[] = $suffixname;
                    } elseif (preg_match('/([A-Za-z,\s]+)\s*,\s*([A-Za-z\s]+)$/', $line, $matches)) {
                        // Handle format: "One or many surname, one or many first names, no middle initial."
                        $surname = $matches[1];
                        $firstname = $matches[2];
                        $middlename = "";
                        // Append the extracted values to the respective arrays
                        $lastNames[] = $surname;
                        $firstNames[] = $firstname;
                        $middleNames[] = $middlename;
                        $suffixNames[] = $suffixname;
                    } elseif (preg_match('/([A-Za-z,\s]+)\s*,\s*(Ma\.\s*[A-Za-z\s]+)\s*([A-Za-z]\.)$/', $line, $matches)) {
                        // Handle format: "One or many surname, Ma. one or many first names, middle initial (if present)."
                        $surname = $matches[1];
                        $firstname = $matches[2];
                        $middlename = isset($matches[3]) ? $matches[3] : "";
                        // Append the extracted values to the respective arrays
                        $lastNames[] = $surname;
                        $firstNames[] = $firstname;
                        $middleNames[] = $middlename;
                        $suffixNames[] = $suffixname;
                    } elseif (preg_match('/([A-Za-z,\s]+)\s*,\s*(Ma\.\s*[A-Za-z\s]+)$/', $line, $matches)) {
                        // Handle format: "One or many surname, Ma. one or many first names, no middle initial."
                        $surname = $matches[1];
                        $firstname = $matches[2];
                        $middlename = isset($matches[3]) ? $matches[3] : "";
                        // Append the extracted values to the respective arrays
                        $lastNames[] = $surname;
                        $firstNames[] = $firstname;
                        $middleNames[] = $middlename;
                        $suffixNames[] = $suffixname;
                    } else {
                        // Handle cases where the pattern is not matched
                        continue;
                    }
                }

                $data = array(
                    'firstNames' => $firstNames,
                    'lastNames' => $lastNames,
                    'middleNames' => $middleNames,
                    'suffixNames' => $suffixNames
                );

                header('Content-Type: application/json');
                echo json_encode($data);
            } else {
                $ocrResult = "Error performing OCR on the uploaded image.";
                $response['error'] = $ocrResult;
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            $ocrResult = "Error creating a GD image resource from the uploaded file.";
            $response['error'] = $ocrResult;
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    } else {
        $ocrResult = "No imaage File";
        $response['error'] = $ocrResult;
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    $ocrResult = "Error Server";
    $response['error'] = $ocrResult;
    header('Content-Type: application/json');
    echo json_encode($response);
}
