$(document).ready(function () {
  var selectedRowID;
  var docsName;

  // Handle the click event for the "View" button
  $("#studTable").on("click", "#viewThis", function (event) {
    event.preventDefault();

    var rowData = $(this).attr("class");
    var selectedRowID = rowData;

    $("#viewModalID").text("Record ID: " + selectedRowID);

    $.ajax({
      type: "GET",
      url: "student_geDocument.php?id=" + selectedRowID,
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

    $("#records_id").val(selectedRowID);
    $.ajax({
      type: "GET",
      url: "editstudent.php?id=" + selectedRowID,
      success: function (response) {
        var data = JSON.parse(response);
        if (data.error) {
          alert(data.error);
        } else {
          $("#surname_edit").val(data.surname);
          $("#firstname_edit").val(data.firstname);
          $("#middlename_edit").val(data.middlename);
          $("#suffixname_edit").val(data.suffixname);
        }
      },
      error: function () {
        alert("Error fetching data.");
      },
    });
    $("#editID").text("Record ID: " + selectedRowID);
  });

  // Handle the click event for the "Delete" button
  $("#studTable").on("click", "#deleteThis", function (event) {
    event.preventDefault();

    var rowData = $(this).attr("class");
    selectedRowID = "";
    selectedRowID = rowData;

    $("#deleteModalID").text("Record ID: " + selectedRowID);

    var deleteThisRow = document.querySelector(".deleteThisRow");
    deleteThisRow.setAttribute("id", selectedRowID);
  });

  //Button delete this row
  $("#deleteModal").on("click", ".deleteThisRow", function (event) {
    event.preventDefault();
    var rowData = $(this).attr("id");
    selectedRowID = rowData;

    $.ajax({
      type: "GET",
      url: "deletestudent.php?id=" + selectedRowID,
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

$(document).ready(function () {
  $("#updateThisRow").click(function () {
    var record_id = $("#records_id").val();
    var surname = $("#surname_edit").val();
    var firstname = $("#firstname_edit").val();
    var middlename = $("#middlename_edit").val();
    var suffixname = $("#suffixname_edit").val();

    var dataToSend = {
      record_id: record_id,
      surname: surname,
      firstname: firstname,
      middlename: middlename,
      suffixname:suffixname,
    };

    // Perform an AJAX POST request
    $.ajax({
      type: "POST",
      url: "update_student.php",
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
