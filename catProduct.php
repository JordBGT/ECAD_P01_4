<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style='width:60%; margin:auto;'>
<!-- Display Page Header - Category's name is read 
     from the query string passed from previous page -->
<div class="row" style="padding:5px">
	<div class="col-12 text-center">
		<span class="page-title" style="color: #4E004A;"><?php echo "$_GET[catName]"; ?></span>
		<p>Select a product to view its details</p>
	</div>
</div>

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do:  Starting ....
$cid=$_GET['cid'];
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.Offered, p.OfferedPrice,p.OfferStartDate,p.OfferEndDate
		FROM CatProduct cp INNER JOIN Product p ON cp.ProductID = p.ProductID
		WHERE cp.CategoryID =? Order By p.ProductTitle";

$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid); //"i" means integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

//Display each product in a row
while ($row = $result ->fetch_array()){
	echo "<div class='row' style='padding:5px'>"; // Start of row
	$product = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row['Price'], 2);
	$formattedOfferedPrice = number_format($row['OfferedPrice'], 2);
	$isOffered = $row['Offered'];
	$offerStartDate = $row['OfferStartDate'];
	$offerEndDate = $row['OfferEndDate'];

	echo "<div class='col-12 productitem-container'>";
    echo "<div class='row p-4'>";
    echo "<div class='col-md-2'></div>";
    echo "<div class='col-md-4 my-auto'>";

	if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<p class='text-left m-2'><a class='category-link' href='$product' style='font-size:1.5em'>$row[ProductTitle]<span style='color:red'>(Now On Offer!!)</a></p>";
	}else{
		echo "<p class='text-left m-2'><a class='category-link' href='$product' style='font-size:1.5em'>$row[ProductTitle]</a></p>";

	}

	if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<p class='text-left m-2'>Original Price: <span style='text-decoration:line-through'>S$ $formattedPrice</span> </p>";
		echo "<p class='text-left m-2'><span style='font-weight:bold; color:red; font-size:1.5em'>Offered Price: S$ $formattedOfferedPrice</span></p>";
	}else{
		echo "<p class='text-left m-2' style='font-size:1.5em'>Price: <span style='font-weight:bold; color:red;'> S$ $formattedPrice</span></p>";
	}




	
    echo "</div>"; 

	$img = "./Images/products/$row[ProductImage]";
    echo "<div class='col-md-4 text-center'><a href='$product'><img src='$img' width='200px'></a></div>";
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
