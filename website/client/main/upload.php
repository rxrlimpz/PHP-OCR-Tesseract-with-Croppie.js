<?php
include 'connect.php';
include 'config.php';

$query = "SELECT * FROM college ORDER BY CollegeName ASC";
$result = $con->query($query);
$colleges = [];

while ($row = $result->fetch_assoc()) {
  $colleges[] = $row;
}

$queries = "SELECT * FROM coll_course";
$outcome = mysqli_query($con, $queries);

$courses = [];

while ($row = $outcome->fetch_assoc()) {
  $courses[] = $row;
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>add-records</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../../bootstrap/fonts/quicksand-font.css">
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../node_modules/croppie/croppie.css">
  <script src="../../bootstrap/js/jquery.min.js"></script>
  <script src="../../bootstrap/js/bootstrap.min.js"></script>
  <script src="../../node_modules/croppie/croppie.min.js"></script>
  <link rel="stylesheet" href=" ../../interface/styles/upload-page.css">
  <link rel="stylesheet" href="../../interface/styles/web-modal.css">
</head>

<body class="container-fluid">
  <div class=" input-container-form row">
    <div class="container col image-display">
      <div class=" container row image-container">
        <form id="uploadForm" enctype="multipart/form-data" action="insert.php">
          <div class="image-display-dropzone">
            <div id="dropzone" class="drop-zone dragscroll" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);">
              Drop PDF or Image here
            </div>
            <input type="file" name="imagefile" id="fileInput" style="display: none;" accept=".pdf, .jpg, .jpeg, .png">
          </div>
        </form>

        <div id="zoomBar" style="display: none;" class="justify-content-evenly">
          <button id="zoomOutButton" class="zooom-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 12 12">
              <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
            </svg>
          </button>
          <input type="range" id="zoomRange" class="zooom-item" min="1" max="5" value="1" step="0.1" oninput="zoomImage()">
          <button id="zoomInButton" class="zooom-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 12 12">
              <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
          </button>
        </div>

        <button id="cropButton" class="btn red-button" style="display: none;" data-bs-toggle="modal" data-bs-target="#imageModalContainer">
          <span class="scan-icon-ocr">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-file-earmark-break-fill" viewBox="0 0 18 18">
              <path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V9H2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM2 12h12v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zM.5 10a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1z" />
            </svg>
          </span>
          OCR
        </button>
        <div class="cancel-myUpload container d-flex justify-content-end">
          <a type="button" id="cancelButton" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
              <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
            </svg>
          </a>
        </div>
      </div>
    </div>

    <div class="container col input-Form">
      <div id="records-data" class="container">
        <div class=" document-header container" id="recordsheader">
          <div class="container row" id="recordsInfo">
            <div class="records_info Docname col" id="docInfo">
              <label for="docname" class="fw-bold">Document Name</label>
              <input type="text" name="docsname" id="docsname" class="form-control" readonly required>
            </div>
          </div>
          <div class="container row" id="recordsInfo">
            <div class="records_info CollegeName col " id="collname">
              <label for="college" class="fw-bold">College</label>
              <select name="college" class="form-control form-select form-select-sm" id="college" required>
                <input type="text" name="college" id="customCollege" class="form-control" placeholder="Custom" style="display: none;">
              </select>
            </div>
            <div class="records_info courseName col " id="coursename">
              <label for="course" class="fw-bold">Course</label>
              <select name="course" class="form-control form-select form-select-sm" id="course" required>
                <input type="text" name="course" id="customCourse" class=" form-control" placeholder="Custom" style="display: none;">
              </select>
            </div>
            <div class=" records_info Year col " id="YearLevel">
              <label for="Year" class="fw-bold">Year Level</label>
              <select name="Year" class="form-control form-select form-select-sm" id="Year" required>
                <option value="1">1st</option>
                <option value="2">2nd</option>
                <option value="3">3rd</option>
                <option value="4">4th</option>
                <option value="5">5th</option>
                <option value="6">6th</option>
                <option value="7">Masteral</option>
              </select>
            </div>
            <div class="records_info schoolyear col " id="Schoolyear">
              <label for="schoolyear" class="fw-bold">Academic Year</label>
              <input type="text" name="schoolyear" id="schoolyear" class=" form-control" required>
            </div>

          </div>
          <div class="row container" id="recordsInfo">
            <div class=" records_info subject col " id="Subject">
              <label for="subject" class="fw-bold">Subject</label>
              <input type="text" name="subj" id="subj" class=" form-control" required>
            </div>
            <div class=" records_info subjType col " id="SubjType">
              <label for="subjType" class="fw-bold">Class Type</label>
              <select name="subj_type" class="form-control form-select form-select-sm" id="subj_type" required>
                <option value="0"></option>
                <option value="1">Lecture</option>
                <option value="2">Laboratory</option>
              </select>
            </div>
            <div class=" records_info semester col " id="Semester">
              <label for="semester" class="fw-bold">Semester</label>
              <select name="sem" class="form-control form-select form-select-sm" id="sem" required>
                <option value="1">1st</option>
                <option value="2">2nd</option>
                <option value="0">Off-Sem</option>
              </select>
            </div>
          </div>
          <br />
        </div>

        <div class="table-responsive container ">
          <table class="table " id="recordsTable">
            <thead>
              <tr>
                <th data-field="state" data-checkbox="true">
                  <input id="select-all" type="checkbox" class="checkbox">
                </th>
                <th>#</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th> MI</th>
                <th> Suffix </th>
                <th>
                  <button id="addButton">
                    <i class="addStudent">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                      </svg>
                    </i>
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="addRowcontainer container d-flex justify-content-end">
          <div id="addrowForm" class="d-fex space-between">
            <input type="number" class="inputAddRow " id="toPopulate">
            <button id="populateByNum">
              <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 18 18">
                  <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                  <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                </svg>
              </i>
            </button>
          </div>
          <div id="clearButton-Modal" class="space-between">
            <button data-bs-toggle="modal" data-bs-target="#clearNow" id="clearModal">
              <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eraser-fill" viewBox="0 0 18 18">
                  <path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm.66 11.34L3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z" />
                </svg>
              </i>
            </button>
          </div>
          <div id="divcheck" class="space-between">
            <button data-bs-toggle="modal" data-bs-target="#deletSome" id="deleteModal">
              <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-dash-fill text-danger" viewBox="0 0 18 18">
                  <path fill-rule="evenodd" d="M11 7.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                  <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                </svg>
              </i>
            </button>
          </div>
          <div id="submit-button-modal" class="space-between">
            <button id="upload-button" type="button" class="btn red-button" data-bs-toggle="modal" data-bs-target="#submitNow" name="submit"> Upload </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deletSome" tabindex="-1" aria-labelledby="deletSome" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title fs-5" id="deletSome">Remove Rows?</h5>
          <br>
          <p>Selected Rows will be removed, All selected Rows will be loss</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn red-button  " data-bs-dismiss="modal" id="deleteSelected">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="clearNow" tabindex="-1" aria-labelledby="clearNow" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title fs-5" id="clearNow">Clear Data</h5>
          <br>
          <p> Clearing All Rows may remove its data and contents. Do you want to proceed with clearing?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn red-button" data-bs-dismiss="modal" id="clearSelectedButton">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="submitNow" tabindex="-1" aria-labelledby="submitNow" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title fs-5" id="submitNow">Upload Document</h5>
          <br>
          <p>Please make sure that all data in the table is indicated correctly</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn green-button" data-bs-dismiss="modal" onclick="saveButton()">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="imageModalContainer" tabindex="-1" aria-labelledby="croppie" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-body modal-body1 row">
          <div id='crop-image-container' class="col"></div>
          <div id="buttons-list-OCR" class="col">
            <div class="row container custom-image-edit">
              <div>
                <div class="d-flex justify-content-between">
                  <h5 class="fw-bold">OCR Image-to-Text</h5>
                </div>
                <p>Check out the highlighted part and zoom in or out as needed to specify the table with student names.</p>
              </div>
            </div>

            <div class="row container custom-image-edit" id="filter-buttons">
              <h6 class="fw-bold"> Modify font-weight</h6>
              <button id="edit-default" type="button" class="btn btn-light text-md-start">Default</button>
              <button id="edit-thin" type="button" class="btn btn-light text-md-start">Regular</button>
              <button id="edit-Thicc" type="button" class="btn btn-light text-md-start">Bolder</button>
            </div>

            <div class="row container custom-image-edit">
              <h6 class="fw-bold">Custom Image </h6>
              <p>Adjust as necessary for different font weights.</p>
              <div class="container" style="margin-bottom: 1rem;">
                Thickness : <span id="iteration"> 0 </span>
              </div>
              <input type="range" id="dilation-erode" min="-5" max="5" value="0">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary cancel-OCR-Button" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-dark orange-button scanCroppedButton">Convert to Text</button>
        </div>
      </div>
    </div>
  </div>

</body>

<!-- College Condition for courses-->
<script>
  var colleges = <?= json_encode($colleges) ?>;
  var courses = <?= json_encode($courses) ?>;
  var coll_select = document.getElementById("college");
  var course_select = document.getElementById("course");

  var option = document.createElement("option");
  option.value = "";
  option.text = "";
  coll_select.appendChild(option);

  for (var i = 0; i < colleges.length; i++) {
    var option = document.createElement("option");
    option.value = colleges[i].CollegeName;
    option.text = colleges[i].CollegeName;
    coll_select.appendChild(option);
  }

  coll_select.addEventListener("change", function() {
    var selectedCollege = coll_select.value;

    while (course_select.firstChild) {
      course_select.removeChild(course_select.firstChild);
    }

    if (selectedCollege) {

      var option = document.createElement("option");
      option.value = "";
      option.text = "";
      course_select.appendChild(option);

      for (var i = 0; i < courses.length; i++) {

        if (courses[i].CollegeName == selectedCollege) {
          var option = document.createElement("option");
          option.value = courses[i].CourseName;
          option.text = courses[i].CourseName;
          course_select.appendChild(option);
        }
      }
    }

  });
</script>
<?php $con->close(); ?>

<!-- Document Name-->
<script>
  var documentName = ['', '', '', '', '', '', ''];

  function updateDocName() {
    var allEmpty = documentName.every(function(value) {
      return value.trim() === '';
    });

    if (allEmpty) {
      $("#docsname").val("");
    } else {
      var schoolYear = documentName[0] ? documentName[0].trim() + "_" : "";
      var college = documentName[1] ? documentName[1].trim() + "_" : "";
      var course = documentName[2] ? documentName[2].trim() + "_" : "";
      var year = documentName[3] ? documentName[3].trim() + "_" : "";
      var sem = documentName[4] ? documentName[4].trim() + "_" : "";
      var subj = documentName[5] ? documentName[5] + "_" : "";
      var subjType = documentName[6] ? documentName[6].trim() + "_" : "";

      var doc_name = `${schoolYear}${college}${course}${year}${sem}${subj}${subjType}`;
      $("#docsname").val(doc_name);
    }
  }

  $("#college").change(function() {
    $("#course").val("");
    documentName[2] = "";
    updateDocName();
  });

  // Event listeners for select elements
  $("select").change(function() {
    updateArray();
    updateDocName();
  });

  $("#customCollege").change(function() {
    updateArray2();
    updateDocName();
  });

  $("#customCourse").change(function() {
    updateArray2();
    updateDocName();
  });

  // Event listeners for input elements
  $("input").keyup(function() {
    updateArray();
    updateDocName();
  });

  function updateArray() {
    var schoolYear = $("input#schoolyear").val().replace(/\s/g, '') || "";
    var college = $("select#college").val() || "";
    var course = $("select#course").val() || "";
    var subj = $("input#subj").val() || "";

    // Formatting year level
    var year_level = "";
    var year = $("select#Year").val().replace(/\s/g, '') || "";
    if (parseInt(year) < 7) {
      var ordinal = ["st", "nd", "rd", "th"];
      var index = parseInt(year) - 1;
      if (index >= 3 && index < 6) {
        year_level = (index + 1) + "th";
      } else {
        year_level = (index < ordinal.length) ? (index + 1) + ordinal[index] : year;
      }
    } else {
      year_level = "Masteral";
    }

    // Formatting semester
    var semester = "";
    var sem = $("select#sem").val() || "";
    if (sem === "0") {
      semester = "Off Sem";
    } else if (sem === "1") {
      semester = "1stSem";
    } else if (sem === "2") {
      semester = "2ndSem";
    }

    // Formatting subject type
    var type = $("select#subj_type").val() || "";
    switch (type) {
      case "0":
        subjType = "";
        break;
      case "1":
        subjType = "Lect";
        break;
      case "2":
        subjType = "Lab";
        break;
      default:
        subjType = "";
        break;
    }

    documentName = [schoolYear, college, course, year_level, semester, subj, subjType];
  }


  updateDocName();
</script>
<!-- custom-->
<script>
  const selectCollege = document.getElementById('college');
  const selectCourse = document.getElementById('course');

  const customCollegeInput = document.getElementById('customCollege');
  const customCourseInput = document.getElementById('customCourse');

  // Add an event listener to the select element
  selectCollege.addEventListener('change', function() {

    if (selectCollege.value === 'custom') {
      selectCollege.style.display = 'none';
      selectCourse.style.display = 'none';
      customCollegeInput.style.display = 'block';
      customCourseInput.style.display = 'block';
      selectCollege.value = 'custom';

    } else {
      customCollegeInput.style.display = 'none';
      customCourseInput.style.display = 'none';
    }
  });
</script>
<!-- populate and delete functions-->
<script>
  var firstnameTable = [];
  var surnameTable = [];
  var midnameTable = [];
  var suffixTable = [];
  var suffixesList = ["JR.", "SR.", "II", "III", "IV", "V", "VI"];

  if (firstnameTable.length === 0 && midnameTable.length === 0 && surnameTable.length === 0) {
    firstnameTable = ["", "", "", "", "", "", "", "", "", ""];
    surnameTable = ["", "", "", "", "", "", "", "", "", ""];
    midnameTable = ["", "", "", "", "", "", "", "", "", ""];
    suffixTable = ["", "", "", "", "", "", "", "", "", ""];
  }

  var gradeTable = document.getElementById("recordsTable");
  var tbody = gradeTable.querySelector("tbody");

  function populateTable() {

    tbody.innerHTML = "";

    for (var i = 0; i < firstnameTable.length; i++) {

      var newRow = document.createElement("tr");

      newRow.setAttribute("data-index", i);
      var checkboxCell = document.createElement("td");
      var checkboxInput = document.createElement("input");
      checkboxInput.type = "checkbox";
      checkboxInput.id = "ischeck";
      checkboxInput.setAttribute("name", "btSelectItem");
      checkboxInput.setAttribute("data-index", i);
      checkboxInput.setAttribute("onclick", "checkMe(this.checked)");
      checkboxCell.appendChild(checkboxInput);
      newRow.appendChild(checkboxCell);

      var indexCell = document.createElement("td");
      indexCell.textContent = i + 1;
      indexCell.id = "numberInput";
      newRow.appendChild(indexCell);

      var surnameCell = document.createElement("td");
      var surnameField = document.createElement("input");
      surnameField.type = "text";
      surnameField.classList.add("text-field");
      surnameField.name = "sname";
      surnameField.id = "sname";
      surnameField.required = true;
      surnameField.value = surnameTable[i];
      surnameField.addEventListener("input", createInputListener(surnameTable, i));
      surnameCell.appendChild(surnameField);
      newRow.appendChild(surnameCell);

      var firstnameCell = document.createElement("td");
      var firstnameField = document.createElement("input");
      firstnameField.type = "text";
      firstnameField.classList.add("text-field");
      firstnameField.name = "fname";
      firstnameField.id = "fname";
      firstnameField.required = true;
      firstnameField.value = firstnameTable[i];
      firstnameField.addEventListener("input", createInputListener(firstnameTable, i));
      firstnameCell.appendChild(firstnameField);
      newRow.appendChild(firstnameCell);

      var midnameCell = document.createElement("td");
      var midnameField = document.createElement("input");
      midnameField.type = "text";
      midnameField.classList.add("text-field");
      midnameField.name = "middlename";
      midnameField.id = "midname";
      surnameField.required = false;
      midnameField.value = midnameTable[i];
      midnameField.addEventListener("input", createInputListener(midnameTable, i));
      midnameCell.appendChild(midnameField);
      newRow.appendChild(midnameCell);

      var suffixCell = document.createElement("td");
      var suffixField = document.createElement("input");
      suffixField.type = "text";
      suffixField.classList.add("text-field");
      suffixField.name = "suffixname";
      suffixField.id = "suffix";
      suffixField.required = false;
      suffixField.value = suffixTable[i];
      suffixField.addEventListener("input", createInputListener(suffixTable, i));
      suffixCell.appendChild(suffixField);
      newRow.appendChild(suffixCell);

      suffixField.addEventListener("input", function(event) {
        var inputText = event.target.value.replace(/\s+/g, '').toUpperCase(); // Convert input to uppercase
        event.target.value = inputText; // Update input value to uppercase text
      });

      var deleteCell = document.createElement("td");
      var deleteButton = document.createElement("button");
      deleteButton.classList.add("delete-this");
      deleteButton.classList.add("red-button");
      deleteButton.addEventListener("click", createDeleteListener(i));
      deleteButton.innerHTML = '<span class="ion-icon"><i id="deleteButton"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg></i></span><span class="long-text"> Delete </span>';
      deleteCell.appendChild(deleteButton);
      newRow.appendChild(deleteCell);

      tbody.appendChild(newRow);
    }
  }

  function createInputListener(array, index, datalistId) {
    return function(event) {
      if (datalistId && event.key === "Enter") {
        event.preventDefault();
        return;
      }

      array[index] = event.target.value;

      if (datalistId) {
        var datalist = document.getElementById(datalistId);
        var input = event.target;

        var options = datalist.querySelectorAll("option");
        options.forEach(function(option) {
          option.hidden = option.value.toLowerCase().indexOf(input.value.toLowerCase()) === -1;
        });
      }
    };
  }

  function createSelectListener(array, index) {
    return function(event) {
      var selectedValue = event.target.value;
      array[index] = selectedValue !== "" ? selectedValue : " ";
    };
  }

  populateTable();
  var addButton = document.getElementById("addButton");
  addButton.addEventListener("click", function() {
    firstnameTable.push("");
    surnameTable.push("");
    midnameTable.push("");
    suffixTable.push("");
    populateTable();
  });

  function createDeleteListener(index) {
    return function() {
      firstnameTable.splice(index, 1);
      surnameTable.splice(index, 1);
      midnameTable.splice(index, 1);
      suffixTable.splice(index, 1);
      populateTable();
    };
  }

  function cleanupTable() {
    for (var i = 0; i < firstnameTable.length; i++) {
      if ((firstnameTable[i] === "" && surnameTable[i] === "") || (firstnameTable[i] === undefined && surnameTable[i] === undefined)) {
        firstnameTable.splice(i, 1);
        surnameTable.splice(i, 1);
        midnameTable.splice(i, 1);
        suffixTable.splice(i, 1);
        i--;
      }
    }
  }
</script>
<!-- adjust the header width-->
<script>
  function adjustHeaderColumnWidths() {
    var tbody = document.querySelector(".table-responsive tbody");
    var headerColumns = document.querySelectorAll(".table-responsive thead th");

    headerColumns.forEach(function(th, index) {
      var maxWidth = 0;

      tbody.querySelectorAll("tr").forEach(function(row) {
        var td = row.querySelectorAll("td")[index];
        if (td) {
          var tdWidth = td.getBoundingClientRect().width;
          maxWidth = Math.max(maxWidth, tdWidth);
        }
      });
      th.style.width = maxWidth + "px";
    });
  }
  window.addEventListener("load", adjustHeaderColumnWidths);
</script>
<!-- toogle select all for delete functions-->
<script>
  function toggleSelectAll() {
    var selectAllCheckbox = document.getElementById("select-all");
    var checkboxes = document.querySelectorAll('input[type="checkbox"][data-index]');

    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = selectAllCheckbox.checked;
    }
  }

  var selectAllCheckbox = document.getElementById("select-all");
  selectAllCheckbox.addEventListener("click", toggleSelectAll);

  function deleteSelectedRows() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][data-index]:checked');

    var selectedIndices = Array.from(checkboxes).map(function(checkbox) {
      return parseInt(checkbox.getAttribute("data-index"));
    }).sort(function(a, b) {
      return b - a;
    });

    selectedIndices.forEach(function(index) {
      firstnameTable.splice(index, 1);
      surnameTable.splice(index, 1);
      midnameTable.splice(index, 1);
      suffixTable.splice(index, 1);
    });

    populateTable();
  }

  var deleteSelectedButton = document.getElementById("deleteSelected");
  deleteSelectedButton.addEventListener("click", deleteSelectedRows);
</script>
<!-- for populate by number selected to add-->
<script>
  var populateButton = document.getElementById("populateByNum");
  populateButton.addEventListener("click", function() {
    var toPopulateInput = document.getElementById("toPopulate");
    var numToPopulate = parseInt(toPopulateInput.value);

    if (!isNaN(numToPopulate) && numToPopulate > 0) {
      for (var i = 0; i < numToPopulate; i++) {
        firstnameTable.push("");
        surnameTable.push("");
        midnameTable.push("");
        suffixTable.push("");
      }

      populateTable();
    } else {
      alert("Please enter a valid number greater than 0.");
    }
    // Clear the input field
    toPopulateInput.value = "";
  });
</script>
<!-- clear button of all table content-->
<script>
  function clearTextFields() {
    var checkboxes = document.querySelectorAll('#recordsTable input[type="checkbox"][data-index]:checked');

    var selectedIndices = Array.from(checkboxes).map(function(checkbox) {
      return parseInt(checkbox.getAttribute("data-index"));
    }).sort(function(a, b) {
      return b - a;
    });

    selectedIndices.forEach(function(index) {
      firstnameTable[index] = "";
      surnameTable[index] = "";
      midnameTable[index] = "";
      suffixTable[index] = "";
    });

    populateTable();
  }

  var clearButton = document.getElementById("clearSelectedButton");
  clearButton.addEventListener("click", clearTextFields);
</script>
<!--for drag & drop funtions-->
<script>
  const dropzone = document.getElementById('dropzone');
  const fileInput = document.getElementById('fileInput');
  const cancelButton = document.getElementById('cancelButton');
  const cropButton = document.getElementById('cropButton');
  const my_zoomBar = document.getElementById('zoomBar');

  dropzone.addEventListener('click', () => {
    fileInput.click();
  });

  cancelButton.addEventListener('click', () => {
    fileInput.value = '';
    dropzone.innerHTML = 'Click or Drag and Drop to Upload';
    cancelButton.style.display = 'none';
    cropButton.style.display = 'none';
    my_zoomBar.style.display = 'none';
    imgData = '';
  });

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (file) {
      displayFile(file);
      cancelButton.style.display = 'block';
      cropButton.style.display = 'block';
      my_zoomBar.style.display = 'block';
    }
  });

  dropzone.addEventListener('dragover', dragOverHandler);
  dropzone.addEventListener('drop', dropHandler);

  function displayFile(file) {
    const fileType = file.type;
    if (fileType.includes('image')) {
      const reader = new FileReader();
      reader.onload = function(event) {
        const fileContent = event.target.result;
        dropzone.innerHTML = `<img src="${fileContent}" width="100%" height="100%" id="zoomImage" alt="Uploaded Image">`;
      };
      reader.readAsDataURL(file);
    } else {
      alert('Only image files are supported.');
    }

  }

  function dropHandler(event) {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    displayFile(file);
    cancelButton.style.display = 'block';
    cropButton.style.display = 'block';
    my_zoomBar.style.display = 'block';
    let reader = new FileReader();
    reader.addEventListener("load", function() {
      window.src = reader.result;
      $('#fileInput').val('');
    }, false);
    reader.readAsDataURL(file);

  }

  function dragOverHandler(event) {
    event.preventDefault();
  }

  let imgData = '';
</script>
<!-- for uploading to the server -->
<script>
  //clear the form as success
  function clearForm() {

    const inputElements = document.querySelectorAll('input[type="text"], input[type="number"]');
    const selectElement = document.querySelectorAll('select');
    const fileInput = document.querySelector('input[type="file"]');
    const dropzone = document.getElementById('dropzone');
    const cancelButton = document.getElementById('cancelButton');
    const cropButton = document.getElementById('cropButton');

    const zoomBar = document.getElementById('zoomBar');
    let imgData = ''; // Make sure imgData is defined

    fileInput.value = '';
    dropzone.innerHTML = 'Click or Drag and Drop to Upload';
    cancelButton.style.display = 'none';
    cropButton.style.display = 'none';

    zoomBar.style.display = 'none';
    imgData = '';

    inputElements.forEach(function(input) {
      input.value = '';
    });

    selectElement.forEach(function(select) {
      select.value = 'default';
    });
  }

  function saveButton() {
    let formData = new FormData();
    var file = $('#fileInput')[0].files[0];
    formData.append('fileInput', file);

    if (file) {
      formData.append('fileInput', file);

      var docName = $("input#docsname").val();
      var College = $("select#college").val();
      var Course = $("select#course").val();
      var Year = $("select#Year").val();
      var Semester = $("select#sem").val();
      var schoolYear = $("input#schoolyear").val();
      var Subject = $("input#subj").val();
      var Class_Type = $("select#subj_type").val();

      var headerData = {
        docName: docName,
        College: College,
        Course: Course,
        Year: Year,
        Semester: Semester,
        schoolYear: schoolYear,
        Subject: Subject,
        Class_Type: Class_Type,
      };

      formData.append('headerData', JSON.stringify(headerData));
      var data = [];

      var inputs = document.querySelectorAll('#recordsTable input[type="text"]');
      for (var i = 0; i < inputs.length; i++) {
        var name = inputs[i].getAttribute('name');
        var value = inputs[i].value.replace(/[,.\d]/g, '');
          if (name === 'suffixname') {
            if (suffixesList.includes(value)) {
              value = value.replace(/\s+/g, '').replace(/[,.\d]/g, ''); // Capitalize the first letter, lowercase the rest, remove spaces
            } else {
              value = ""; // If the value is not in the suffixesList, set it to an empty string
            }
          }

          data.push({
            name: name,
            value: value
          });
      }
      formData.append('data', JSON.stringify(data));

console.log(data);
      if (!Subject || !schoolYear || !Course || !docName || data.length === 0) {
        alert('Please fill in all required fields.');
      } else {

        $.ajax({
          url: 'insert.php',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            clearForm();
          }
        });
      }

    } else {
      alert('Please select an Image file');
    }

  }
</script>
<!--zoom in function-->
<script>
  function zoomImage() {
    var zoomRange = document.getElementById("zoomRange");
    var zoomValue = parseFloat(zoomRange.value);
    var myImg = document.getElementById("zoomImage");
    var initialWidth = 100;

    var newWidth = initialWidth * zoomValue;
    myImg.style.width = newWidth + "%";
  }

  document.getElementById("zoomInButton").addEventListener("click", function() {
    var zoomRange = document.getElementById("zoomRange");
    var currentValue = parseFloat(zoomRange.value);
    if (currentValue < 5) {
      zoomRange.value = (currentValue + 0.1).toFixed(1);
      zoomImage();
    }
  });

  // Function to zoom out
  document.getElementById("zoomOutButton").addEventListener("click", function() {
    var zoomRange = document.getElementById("zoomRange");
    var currentValue = parseFloat(zoomRange.value);
    if (currentValue > 1) {
      zoomRange.value = (currentValue - 0.1).toFixed(1);
      zoomImage();
    }
  });

  document.getElementById('fileInput').addEventListener('change', function() {
    const cropButton = document.getElementById('cropButton');
    if (this.files.length > 0) {
      cropButton.style.display = 'block';
    } else {
      cropButton.style.display = 'none';
    }
  });
</script>

<script>
  //OCR Button to display the image from the src selected fileinput
  document.getElementById('cropButton').addEventListener('click', function() {
    const fileInput = document.getElementById('fileInput');
    const selectedFile = fileInput.files[0];

    if (selectedFile) {

      let reader = new FileReader();
      reader.addEventListener("load", function() {
        window.src = reader.result;
        $('#fileInput').val();
      }, false);
      reader.readAsDataURL(selectedFile);
    }
  });

  //range bar input for erosion and dilation image processing before OCR
  const rangeBar = document.getElementById("dilation-erode");
  const iterationText = document.getElementById("iteration");
  const defaultButton = document.getElementById("edit-default");
  const regularButton = document.getElementById("edit-thin");
  const boldButton = document.getElementById("edit-Thicc");
  var iterationsMorph = 0;

  // Add an event listener to the range input
  rangeBar.addEventListener("input", function() {
    const value = parseInt(this.value);
    iterationText.textContent = "" + value;

    if (value < 0) {
      iterationsMorph = value;
    } else if (value > 0) {
      iterationsMorph = value;
    } else {
      morophMethodType = "";
      iterationsMorph = 0;
    }
  });

  // Add click event listeners to the buttons
  defaultButton.addEventListener("click", function() {
    iterationsMorph = 0; // Set the value to 0 for "Default"
    rangeBar.value = 0;
    iterationText.textContent = "" + 0;
  });

  regularButton.addEventListener("click", function() {
    iterationsMorph = -1; // Set the value to -1 for "Regular"
    rangeBar.value = -1;
    iterationText.textContent = "" + -1;
  });

  boldButton.addEventListener("click", function() {
    iterationsMorph = 1; // Set the value to 1 for "Boolder"
    rangeBar.value = 1;
    iterationText.textContent = "" + 1;
  });

  const filterButtonsDiv = document.getElementById('filter-buttons');
  const filterbuttons = filterButtonsDiv.querySelectorAll('button');

  filterbuttons.forEach(button => {
    button.addEventListener('click', () => {
      filterbuttons.forEach(btn => {
        btn.classList.remove('red-button');
      });
      button.classList.add('red-button');
    });
  });
</script>

<script>
</script>



<script type="text/javascript" src="../../interface/scripts/croppie.js"></script>

</html>