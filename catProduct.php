<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style='width:60%; margin:auto;'>
<!-- Display Page Header - Category's name is read 
     from the query string passed from previous page -->
<div class="row" style="padding:5px">
	<div class="col-12">
		<span class="page-title"><?php echo "$_GET[catName]"; ?></span>
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
	//Left column - display a text link showing product's name,
	//     			display the selling price in a new paragraph
	$product = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row['Price'], 2);
	$formattedOfferedPrice = number_format($row['OfferedPrice'], 2);
	$isOffered = $row['Offered'];
	$offerStartDate = $row['OfferStartDate'];
	$offerEndDate = $row['OfferEndDate'];
	echo "<div class='col-8'>";
	// Display the product name with a link, enlarge the link
	//if the product is offered, append a "on offer" after the product name with red color
	if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<p><a href='$product' style='font-size:1.5em; color:blue'>$row[ProductTitle] <span style='color:red'>(Now On Offer!!)</span></a></p>";
	}else{
		echo "<p><a href='$product' style='font-size:1.5em; color:blue'>$row[ProductTitle]</a></p>";
	}

	if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<p>Original Price: <span style='text-decoration:line-through'>S$ $formattedPrice</span> </p>";
		echo "<p><span style='font-weight:bold; color:red; font-size:1.5em'>Offered Price: S$ $formattedOfferedPrice</span></p>";
	}else{
		echo "<p style='font-size:1.5em'>Price: <span style='font-weight:bold; color:red;'> S$ $formattedPrice</span></p>";
	}
	

	$img = "./Images/products/$row[ProductImage]";
	//display image with a link to product details page, set the image size to 200px
	echo "<div><a href=$product><img src=$img width='200px'></a></div>";
	echo "</div>";
	//Right column - display the add to cart function
	echo "<div class='col-4'>";
	

	echo "</div>";
	echo "</div>"; // End of row
	echo "<hr style='border:1px solid #ccc'>";
    echo "<div style='height:20px'></div>";
	
}

// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
