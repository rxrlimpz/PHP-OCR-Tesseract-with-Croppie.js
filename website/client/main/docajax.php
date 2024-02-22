<?php
include 'config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Custom Field value
$searchByName = $_POST['searchByName'];
$searchByCollege = $_POST['searchByCollege'];
$searchByCourse = $_POST['searchByCourse'];
$searchByYearL = $_POST['searchByYearL'];
$searchBySemester = $_POST['searchBySemester'];

## Search 
$searchQuery = " ";
if ($searchByName != '') {

    // Split the searchValue into multiple terms
    $searchTerms = explode(' ', $searchByName);

    // Perform a global search across all relevant columns for each term
    foreach ($searchTerms as $term) {
        $searchQuery .= " and (DocName like '%" . $term . "%' 
                            or College like '%" . $term . "%' 
                            or Course like '%" . $term . "%' 
                            or Subject like '%" . $term . "%' 
                            or SchoolYear like '%" . $term . "%' 
                            or Semester like '%" . $term . "%' 
                            or year_level like '%" . $term . "%') ";
    }
}
if ($searchByCourse != '') { // Add this block
    $searchQuery .= " and (Course='" . $searchByCourse . "') ";
}
if ($searchByCollege != '') { // Add this block
    $searchQuery .= " and (College='" . $searchByCollege . "') ";
}
if ($searchByYearL != '') { // Add this block
    $searchQuery .= " and (year_level='" . $searchByYearL . "') ";
}
if ($searchBySemester != '') { // Add this block
    $searchQuery .= " and (Semester='" . $searchBySemester . "') ";
}


## Total number of records without filtering

$sel = mysqli_query($con, "SELECT count(*) as allcount from ardoctbl");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($con, "SELECT count(*) as allcount from ardoctbl WHERE 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$studQuery = "SELECT * FROM ardoctbl WHERE 1 " . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
$studRecords = mysqli_query($con, $studQuery);
$data = array();

while ($row = mysqli_fetch_assoc($studRecords)) {

    $data[] = array(
        "id" => $row["id"],
        "DocName" => $row['DocName'],
        "College" => $row['College'],
        "Course" => $row['Course'],
        "Subject" => $row['Subject'],
        "SchoolYear" => $row['SchoolYear'],
        "Semester" => $row['Semester'],
        "year_level" => $row['year_level'],
    );
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
