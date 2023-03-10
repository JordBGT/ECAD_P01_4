<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:60%; margin:auto;">
<!-- Display Page Header -->
<div class="row" style="padding:5px"> <!-- Start of header row -->
    <div class="col-12 text-center">
        <span class="page-title" style="color: #4E004A;">Product Categories</span>
        <p>Select a category listed below</p>
    </div>
</div> <!-- End of header row -->

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do:  Starting ....
$qry = "SELECT * FROM Category ORDER BY CatName";
$result = $conn->query($qry);

//Display each category in a row
while ($row = $result ->fetch_array()){
    echo "<div class='row' style='padding:5px'>"; // Start of row
    $catname = urlencode($row['CatName']);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    echo "<div class='col-12 productitem-container'>";
    echo "<div class='row p-4'>";
    // Display the category name with a link, enlarge the link
    echo "<div class='col-md-2'></div>";
    echo "<div class='col-md-4 my-auto'>";
    echo "<p class='text-left m-2'><a class='category-link' href='$catproduct' style='font-size:1.5em'>$row[CatName]</a></p>";
    echo "<p class='text-left m-2'>$row[CatDesc]</p>";
    echo "</div>"; 
    $img = "./Images/category/$row[CatImage]";
    //display the category image, add the link to the category product page, set the image size to 200px
    echo "<div class='col-md-4 text-center'><a href='$catproduct'><img src='$img' width='200px'></a></div>";
    echo "<div class='col-md-2'></div>";
    echo "</div>";
    echo "</div>"; 
    echo "</div>"; // End of row
    // add some space between each category, with a horizontal line
    // echo "<hr style='border:1px solid #ccc'>";
    echo "<div style='height:20px'></div>";


    
}

// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
