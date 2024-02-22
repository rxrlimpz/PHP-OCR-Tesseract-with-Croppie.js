const profileButton = document.getElementById("profile_view");

profileButton.addEventListener("click", function () {
  const sidebarLinks = document.querySelectorAll(".sidebar-link");
  sidebarLinks.forEach(function (link) {
    link.classList.remove("active");
  });
});

document
  .getElementById("Records_Sidebar")
  .addEventListener("click", function () {
    updateTitle("OUR-ArchiD | Records");
    updateCurrentPageSession("archives_student.php");
  });

document
  .getElementById("Documents_Sidebar")
  .addEventListener("click", function () {
    updateTitle("OUR-ArchiD | Documents");
    updateCurrentPageSession("archives_document.php");
  });

document
  .getElementById("Upload_Sidebar")
  .addEventListener("click", function () {
    updateTitle("OUR-ArchiD | Upload");
    updateCurrentPageSession("upload.php");
  });

document.getElementById("profile_view").addEventListener("click", function () {
  updateTitle("OUR-ArchiD | Profile");
  updateCurrentPageSession("profile.php");
});

function updateTitle(newTitle) {
  document.title = newTitle;
}

function updateCurrentPageSession(newPage) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "set_currentPage.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      if (xhr.responseText === "Success") {
        // Successfully updated the session variable
        console.log("Session variable updated.");
        // You can add additional logic here if needed.
      } else {
        // Handle the error if the request fails
        console.log("Error updating session variable.");
      }
    }
  };
  xhr.send("currentPage=" + newPage);
}

// Variable to store the current page
var CurrentPage = "archives_student.php";

// Function to get the current page session from the server
function getCurrentPageSession(callback) {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "get_currentPage.php?currentPage=true", true); // Pass currentPage=true as a parameter
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        if (xhr.responseText) {
          callback(xhr.responseText);
        } else {
          console.log("Error: Unable to retrieve the session variable.");
          callback(null);
        }
      } else {
        console.log("Error: Request failed with status: " + xhr.status);
        callback(null);
      }
    }
  };
  xhr.send();
}

var lastPage = "";

// Function to update the iframe source and div content
function updateIframeSrcAndDiv() {
  getCurrentPageSession(function (currentPage) {
    if (currentPage !== null) {
      CurrentPage = currentPage;
      const iframe = document.getElementById("myFrame");
      if (iframe) {
        if (lastPage !== CurrentPage) {
          iframe.src = CurrentPage;
          lastPage = CurrentPage;
          updateCurrentPageDivAndSidebar();
        } else {
          lastPage = CurrentPage;
        }
      }
    }
  });
}

// Function to update the sidebar active
const sidebarElements = {
  "archives_student.php": document.getElementById("Records_Sidebar"),
  "archives_document.php": document.getElementById("Documents_Sidebar"),
  "upload.php": document.getElementById("Upload_Sidebar"),
};

function updateSideBarActive() {
  for (const page in sidebarElements) {
    if (sidebarElements.hasOwnProperty(page)) {
      if (CurrentPage === page) {
        sidebarElements[page].classList.add("active");
      } else {
        sidebarElements[page].classList.remove("active");
      }
    }
  }
}

// Function to update the Title of Page
function updateCurrentPageTitle() {
  switch (CurrentPage) {
    case "archives_student.php":
      updateTitle("OUR-ArchiD | Records");
      break;

    case "archives_document.php":
      updateTitle("OUR-ArchiD | Documents");
      break;
    case "upload.php":
      updateTitle("OUR-ArchiD | Upload");
      break;
    case "profile.php":
      updateTitle("OUR-ArchiD | Profile");
      break;
  }
}

// Function to update the div content
function updateCurrentPageDiv() {
  const currentPageDiv = document.getElementById("currentPageDiv");
  if (currentPageDiv) {
    currentPageDiv.textContent = CurrentPage;
  }
}

// Function to update the div content and sidebar
function updateCurrentPageDivAndSidebar() {
  updateCurrentPageDiv();
  updateSideBarActive();
  updateCurrentPageTitle();
}

// Initially update the iframe source and div content
updateIframeSrcAndDiv();

// JavaScript to handle the dropdown click event
const dropdown = document.querySelector(".dropdown");
dropdown.addEventListener("click", () => {
  dropdown.classList.toggle("is-open");
});

function logout() {
  window.location.href = "logout.php";
}

$(document).ready(function () {
  $("#report").click(function () {
    var textareaData = $("#countThis").val();

    var dataToSend = {
      textareaData: textareaData,
    };

    // Perform an AJAX POST request
    $.ajax({
      type: "POST",
      url: "report.php",
      data: dataToSend,
      success: function (response) {
        if (response === "success") {
          $("#message").html("<p style='color: green;'>Successful</p>");
          $("#countThis").val("");
        } else {
          $("#message").html(
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
