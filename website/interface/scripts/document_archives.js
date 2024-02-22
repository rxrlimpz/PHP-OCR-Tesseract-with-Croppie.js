$(document).ready(function () {
  var selectedRowID;
  var docsName;
  var rowData = $(this).attr("class");

  // Handle the click event for the "View" button
  $("#studTable").on("click", "#viewThis", function (event) {
    event.preventDefault();

    var rowData = $(this).attr("class");
    selectedRowID = "";
    selectedRowID = rowData;

    $("#viewModalID").text("Document ID: " + selectedRowID);

    $.ajax({
      type: "GET",
      url: "document_geDocument.php?id=" + selectedRowID,
      success: function (response) {
        //assign variable to display the image
        $("#documentImage").attr("src", "data:image/jpeg;base64," + response);
      },
      error: function () {
        alert("Error fetching image.");
      },
    });
  });

  // Handle the click event for the "Edit" button
  $("#studTable").on("click", "#editThis", function () {
    var rowData = $(this).attr("class");
    selectedRowID = "";
    selectedRowID = rowData;

    $("#editModalID").text("Document ID: " + selectedRowID);
    $("#document_id").val(selectedRowID);
    $.ajax({
      type: "GET",
      url: "editrecord.php?id=" + selectedRowID,
      success: function (response) {
        var data = JSON.parse(response);
        if (data.error) {
          alert(data.error);
        } else {
          $("#DocName_edit").val(data.docName);
          $("#college_edit").val(data.college);
          $("#course_edit").val(data.course);
          $("#yearLevel_edit").val(data.year);
          $("#subj_edit").val(data.subject);
          $("#schoolyear_edit").val(data.schoolyear);
          $("#sem_edit").val(data.semester);
          $("#class_edit").val(data.class);
        }
      },
      error: function () {
        alert("Error fetching data.");
      },
    });
  });

  // Handle the click event for the "Delete" button
  $("#studTable").on("click", "#deleteThis", function (event) {
    event.preventDefault();

    var rowData = $(this).attr("class");
    selectedRowID = "";
    selectedRowID = rowData;

    $("#deleteModalID").text("Document ID: " + selectedRowID);

    var deleteThisRow = document.querySelector(".deleteThisRow");
    deleteThisRow.setAttribute("id", selectedRowID);
  });

  $("#deleteModal").on("click", ".deleteThisRow", function (event) {
    event.preventDefault();
    var rowData = $(this).attr("id");
    selectedRowID = rowData;

    $.ajax({
      type: "GET",
      url: "deleterecord.php?id=" + selectedRowID,
      dataType: "json",

      success: function () {
        $("#studTable").DataTable().ajax.reload();
      },
      error: function (xhr, textStatus, errorThrown) {
        alert("Error: " + errorThrown);
      },
    });
  });
});

var documentName = [];

function updateDocName() {
  var allEmpty = documentName.every(function (value) {
    return !value.trim();
  });

  if (allEmpty) {
    $("#DocName_edit").val("");
  } else {
    var schoolYear = documentName[0] + "_";
    var college = documentName[1] + "_";
    var course = documentName[2] + "-";
    var year = documentName[3] + "_";
    var sem = documentName[4] + "sem_";
    var subj = documentName[5] + "-";
    var subjType = documentName[6];

    var doc_name = `${schoolYear}${college}${course}${year}${sem}${subj}${subjType}`;
    $("#DocName_edit").val(doc_name);
  }
}

function updateArray() {
  var college = $("select#college_edit").val();
  var course = $("select#course_edit").val();
  var year = $("select#yearLevel_edit").val();
  var sem = $("select#sem_edit").val();
  var schoolYear = $("input#schoolyear_edit").val();
  var subj = $("input#subj_edit").val();
  var subjType = $("select#class_edit").val();

  documentName = [schoolYear, college, course, year, sem, subj, subjType];
}

$("#editModal").on("change keyup", "select, input", function () {
  updateArray();
  updateDocName();
});

$(document).ready(function () {
  $("#updateThisRow").click(function () {
    var doc_id = $("#document_id").val();
    var docName = $("#DocName_edit").val();
    var college = $("#college_edit").val();
    var course = $("#course_edit").val();
    var yearLevel = $("#yearLevel_edit").val();
    var subject = $("#subj_edit").val();
    var schoolYear = $("#schoolyear_edit").val();
    var semester = $("#sem_edit").val();
    var classType = $("#class_edit").val();

    var dataToSend = {
      doc_id: doc_id,
      docName: docName,
      college: college,
      course: course,
      yearLevel: yearLevel,
      subject: subject,
      schoolYear: schoolYear,
      semester: semester,
      classType: classType,
    };

    // Perform an AJAX POST request
    $.ajax({
      type: "POST",
      url: "update_document.php",
      data: dataToSend,
      success: function (response) {
        if (response === "success") {
          $("#status").html("<p style='color: green;'>Successful</p>");
          $("#studTable").DataTable().ajax.reload();
        } else {
          $("#status").html(
            "<p style='color: red;'>Error: " + response + "</p>"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });
});
