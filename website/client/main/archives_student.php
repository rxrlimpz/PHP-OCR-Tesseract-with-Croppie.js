<?php
include 'config.php';
include 'connect.php';

$connect = mysqli_connect("localhost", "root", "", "rog");
$query = "SELECT * FROM College ORDER BY CollegeName ASC";
$result = mysqli_query($connect, $query);

$queries = "SELECT * FROM Course ORDER BY CourseName ASC";
$outcome = mysqli_query($connect, $queries);

include('connect.php');
include('config.php');
?>

<!doctype html>
<html>

<head>
    <title>student-records</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../bootstrap/fonts/quicksand-font.css">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link href='../../bootstrap/DataTables/datatables.min.css' rel='stylesheet'>
    <script src="../../bootstrap/js/jquery.min.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <script src="../../bootstrap/DataTables/datatables.min.js"></script>
    <link rel="stylesheet" href="../../interface/styles/data-table.css">
    <link rel="stylesheet" href="../../interface/styles/student_archive.css">
    <link rel="stylesheet" href="../../interface/styles/web-modal.css">
</head>

<body>
    <style>
        #studTable_filter {
            display: none;
        }

        #studTable_length {
            padding: 0.5rem;
        }
    </style>

    <br>
    <div class="container-content-table">
        <div class="header-table d-flex justify-content-between">
            <div id="container-search">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </span>
                <input type="search" placeholder="Search..." id="searchByName">
            </div>
            <div class="container-filter d-flex justify-content-between">
                <div id="filter1">
                    <label class="fw-bold">College: </label>
                    <select id='searchByCollege'>
                        <option value=''></option>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<option value="' . $row["CollegeName"] . '">' . $row["CollegeName"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div id="filter2">
                    <label class="fw-bold">Course: </label>
                    <select id="searchByCourse">
                        <option value=''></option>
                        <?php
                        while ($row = mysqli_fetch_array($outcome)) {
                            echo '<option value="' . $row["CourseName"] . '">' . $row["CourseName"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div id="filter3">
                    <label class="fw-bold">Year Level: </label>
                    <select id='searchByYearL'>
                        <option value=''></option>
                        <option value='1st'>1st Year</option>
                        <option value='2nd'>2nd Year</option>
                        <option value='3rd'>3rd Year</option>
                        <option value='4th'>4th Year</option>
                        <option value='5th'>5th Year</option>
                        <option value='6th'>6th Year</option>
                        <option value='Masteral'>Masteral</option>
                        ?>
                    </select>
                </div>
                <div id="filter4">
                    <label class="fw-bold">Semester: </label>
                    <select id='searchBySemester'>
                        <option value=''></option>
                        <option value='1st'>1st</option>
                        <option value='2nd'>2nd</option>
                        <option value='Off-Semester'>Off-Sem</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <!-- Table -->
        <div class="">
            <table id='studTable' class='display dataTable' cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID </th>
                        <th>Name</th>
                        <th>College</th>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Year Level</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Script -->
    <script>
        $(document).ready(function() {
            var dataTable = $('#studTable').DataTable({
                stripeClasses: [],
                language: {
                    paginate: {
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/></svg>',
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16"><path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/></svg>'
                    }
                },
                'processing': true,
                'responsive': true,
                'serverSide': true,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajaxfile.php',
                    'data': function(data) {
                        // Read values
                        var College = $('#searchByCollege').val();
                        var name = $('#searchByName').val();
                        var Course = $('#searchByCourse').val();
                        var year_level = $('#searchByYearL').val();
                        var semeester = $('#searchBySemester').val();
                        // Append to data
                        data.searchByCollege = College;
                        data.searchByName = name;
                        data.searchByCourse = Course;
                        data.searchByYearL = year_level;
                        data.searchBySemester = semeester;
                    }
                },
                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'FullName'
                    },
                    {
                        data: 'College'
                    },
                    {
                        data: 'Course'
                    },
                    {
                        data: 'Subject'
                    },
                    {
                        data: 'year_level'
                    },
                    {
                        data: 'Semester'
                    },
                    {
                        data: 'SchoolYear'
                    },

                    {
                        'data': 'id',
                        title: 'Action',
                        wrap: true,
                        "render": function(data, type, row) {
                            const viewIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>';
                            const editIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16"><path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z"/></svg>';
                            const deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16"><path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/></svg>';

                            const viewLink = '<a id="viewThis" data-bs-toggle="modal" data-bs-target="#viewModal" class= "' + data + '">' + viewIcon + '</a>';
                            const editLink = '<a id="editThis" data-bs-toggle="modal" data-bs-target="#editModal" class= "' + data + '">' + editIcon + '</a>';
                            const deleteLink = '<a id="deleteThis" data-bs-toggle="modal" data-bs-target="#deleteModal" class= "' + data + '">' + deleteIcon + '</a>';

                            return viewLink + ' ' + editLink + ' ' + deleteLink;
                        }

                    }

                ],
                aoColumnDefs: [{
                    bSortable: false,
                    aTargets: [-1]
                }]
            });

            $('#searchByName').keyup(function() {
                dataTable.draw();
            });

            $('#searchByCollege').change(function() {
                dataTable.draw();

            });
            $('#searchByCourse').change(function() {
                dataTable.draw();
            });
            $('#searchByYearL').change(function() {
                dataTable.draw();
            });
            $('#searchBySemester').change(function() {
                dataTable.draw();
            });
        });
    </script>

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="viewModalID"></p> <!-- Display the ID here -->
                    <div class="content container-fluid flex-nowrap">
                        <img id="documentImage" src="" alt="Document Image" style="width: 100%; height:100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Canvas -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="applyChanges" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="fw-bold text-secondary">Edit Student Name</p>
                    <p id="editID"></p> <!-- Display the ID here -->
                </div>
                <div class="modal-body">
                    <div id="modal-container-content">
                        <input type="text" id="records_id" name="surname" readonly>
                        <div><label class="fw-bold" id="editLabel">Surname:</label> <input type="text" id="surname_edit" name="surname"></div>
                        <div><label class="fw-bold" id="editLabel">FirstName:</label><input type="text" id="firstname_edit" name="firstname"></div>
                        <div><label class="fw-bold" id="editLabel">MI:</label><input type="text" id="middlename_edit" name="middlename"></div>
                        <div><label class="fw-bold" id="editLabel">Suffix :</label><input type="text" id="suffixname_edit" name="suffixname"></div>
                    </div>
                    <br />
                    <div id="status"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn red-button updateThisRow" data-bs-toggle="modal" data-bs-target="#saveModal">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- save Modal -->
    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveChanges" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Are you sure you want to save changes?
                </div>
                <div class="modal-body">
                    You will change the record details, are you sure you want to continue update these information?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn green-button updateThisRow" id="updateThisRow" data-bs-dismiss="modal">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <br>
                    <h5 class="modal-title fs-5" id="deleteModalLabel">You want to delete this Student Record?</h5>
                    <p id="deleteModalID"></p> <!-- Display the ID here -->
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn red-button deleteThisRow" data-bs-dismiss="modal" id="">Delete</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="../../interface/scripts/student_archives.js"></script>

</html>