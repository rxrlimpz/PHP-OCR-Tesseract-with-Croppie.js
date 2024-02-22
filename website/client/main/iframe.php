<?php
Session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Textract</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../bootstrap/icons/logo.png">
    <link rel="stylesheet" href="../../bootstrap/fonts/quicksand-font.css">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <script src="../../bootstrap/js/jquery.min.js"></script>
    <link rel="stylesheet" href="../../interface/styles/web-sidebar.css">
    <link rel="stylesheet" href="../../interface/styles/web-header.css">
    <link rel="stylesheet" href="../../interface/styles/web-modal.css">
    <script src="../../interface/scripts/sidebar.js" defer></script>
    <script src="../../interface/scripts/text-count.js" defer></script>
</head>

<body>
    <header class="d-flex justify-content-between">
        <div class="d-flex">
            <button class="menu-icon-btn hide-navbar-state" data-menu-icon-btn>
                <svg viewBox="0 0 20 20" preserveAspectRatio="xMidYMid meet" focusable="false" class="menu-icon">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
                </svg>
            </button>
            <div id="usep-info" class="d-flex">
                <div class="space-between-logo"></div>
                <div id="header_usep_title">
                    <div class="header-title-onedata">
                        <span style="color:rgb(229, 156, 36);">Image</span>
                        <span style="color:rgb(151, 57, 57);">Data.</span>
                        <span style="color:rgb(229, 156, 36);">Text</span> 
                        <span style="color:rgb(151, 57, 57);">Data.</span>
                    </div>
                    <div class="header-title-text-sysInfo">
                        TexTract
                    </div>
                </div>
            </div>
        </div>
        <div>
        </div>
    </header>

    <div class="container-content">
        <aside class="sidebar" data-sidebar>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item">
                        <a target="nav-link-frame" href="archives_student.php" class="sidebar-link active" id="Records_Sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" class=" sidebar-icon bi bi-person-lines-fill" viewBox="0 0 18 18">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z" />
                            </svg>
                            <div class="hidden-sidebar">Records</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item">
                        <a target="nav-link-frame" href="archives_document.php" class="sidebar-link" id="Documents_Sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" class=" sidebar-icon bi-file-text" viewBox="0 0 18 18">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z" />
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                            </svg>
                            <div class="hidden-sidebar">Documents</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item">
                        <a target="nav-link-frame" href="upload.php" class="sidebar-link" id="Upload_Sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" class=" sidebar-icon bi bi-file-earmark-plus" viewBox="0 0 18 18" focusable="false">
                                <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z" />
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                            </svg>
                            <div class="hidden-sidebar">Upload</div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item">
                    </li>
                </ul>
            </div>
        </aside>
        <main class="container-fluid flex-nowrap" data-bs-target="#navi-sidebar-admin" data-bs-offset="0">
            <iframe name="nav-link-frame" id="myFrame" src="archives_student.php" class="iframe-border-none" style="width: 100%; height:100%;">
            </iframe>
        </main>
    </div>

    <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="logout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <h6 class="modal-title fs-5" id="logout">Sign Out</h6>
                    <br>
                    <p>Are you sure you want to Sign Out </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn red-button " onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="somethiIsWrong" tabindex="-1" role="dialog" aria-labelledby="somethiIsWrong" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold" id="somethiIsWrong">Feedback and Issue Reporting</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container" id="feedback-modal-container">
                        <div>
                            We greatly value your feedback! It helps us enhance your experience on our website.
                            Please take a moment to share any issues you encounter or provide suggestions on how we can improve our site to better meet your needs.
                            Your insights are crucial in shaping a better online experience for everyone. Thank you for contributing to the improvement of our website!
                        </div>
                        <br>
                        <textarea type="text" class="form-control" id="countThis" placeholder="..."></textarea>
                        <div id="counter-container">
                            <span id="charCount">0</span> <span>/ 250 characters</span>
                        </div>
                        <div id="message"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn orange-button" id="report">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const profileButton = document.getElementById("profile_view");

        profileButton.addEventListener("click", function() {
            const sidebarLinks = document.querySelectorAll(".sidebar-link");
            sidebarLinks.forEach(function(link) {
                link.classList.remove("active");
            });
        });

        document
            .getElementById("Records_Sidebar")
            .addEventListener("click", function() {
                updateTitle("OUR-ArchiD | Records");
                updateCurrentPageSession("archives_student.php");
            });

        document
            .getElementById("Documents_Sidebar")
            .addEventListener("click", function() {
                updateTitle("OUR-ArchiD | Documents");
                updateCurrentPageSession("archives_document.php");
            });

        document
            .getElementById("Upload_Sidebar")
            .addEventListener("click", function() {
                updateTitle("OUR-ArchiD | Upload");
                updateCurrentPageSession("upload.php");
            });

        function updateTitle(newTitle) {
            document.title = newTitle;
        }

        function updateCurrentPageSession(newPage) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "set_currentPage.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {};
            xhr.send("currentPage=" + newPage);
        }

        // Variable to store the current page
        var CurrentPage = "archives_student.php";

        // Function to get the current page session from the server
        function getCurrentPageSession(callback) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_currentPage.php?currentPage=true", true); // Pass currentPage=true as a parameter
            xhr.onreadystatechange = function() {
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
            getCurrentPageSession(function(currentPage) {
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

        $(document).ready(function() {
            $("#report").click(function() {
                var textareaData = $("#countThis").val();

                var dataToSend = {
                    textareaData: textareaData,
                };

                // Perform an AJAX POST request
                $.ajax({
                    type: "POST",
                    url: "report.php",
                    data: dataToSend,
                    success: function(response) {
                        if (response === "success") {
                            $("#message").html("<p style='color: green;'>Successful</p>");
                            $("#countThis").val("");
                        } else {
                            $("#message").html(
                                "<p style='color: red;'>Error: " + response + "</p>"
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    },
                });
            });
        });
    </script>
</body>

</html>