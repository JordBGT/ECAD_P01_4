<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:60%; margin:auto;'>

<?php 
$pid=$_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// To Do 1:  Display Product information. Starting ....
while ($row = $result->fetch_array()){
    echo "<div class='row' style='padding:20px'>"; // Start of row

    $isOffered = $row['Offered'];
    $formattedPrice = number_format($row["Price"], 2);
    $formattedOfferedPrice = number_format($row['OfferedPrice'], 2);
	$offerStartDate = $row['OfferStartDate'];
	$offerEndDate = $row['OfferEndDate'];
    $img = "./Images/products/$row[ProductImage]";

    echo "<div class='col-12 productitem-container'>";
    echo "<div class='row'>";
    echo "<div class='col-md-4'>";
    $img = "./Images/products/$row[ProductImage]";
    echo "<img src='$img' class='img-responsive' alt='Product image'>";
    echo "</div>";

    echo "<div class='col-md-6'>";
    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<h2 style='font-size:2em;'>$row[ProductTitle] <span style='color:red'>(Now On Offer!!)</span></h2>";
	}else{
		echo "<h2 style='font-size:2em;'>$row[ProductTitle]</h2>";
	}

    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    echo "<p class='description'>$row[ProductDesc]</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";
    echo "<div class='col-md-12 buttom-rule'>";

    if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<h3>Original Price: <span style='text-decoration:line-through'>S$ $formattedPrice</span> </h3>";
		echo "<h2 class='product-price'><span style='font-weight:bold; color:red'>Offered Price: S$ $formattedOfferedPrice</span></h2>";
	}else{
		echo "<h2 class='product-price'>Price: <span style='font-weight:bold; color:red;'> S$ $formattedPrice</span></h2>";
	}

    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    $qry = "SELECT Quantity from product where ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    while ($row = $result->fetch_array()){
    if ($row["Quantity"] <= 0){
        echo "<h2 style='color:red'>Out of Stock</h2>";
    }else{
        echo "<form action='cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";

        echo "<span class='title'>Quantity:</span>";
        echo "<input type='number' name='quantity' value='1'min='1' max='10' style='width:60px' required/>";
        echo "<button class='btn btn-primary btn-lg btn-brand btn-full-width' type='submit'>Add to Cart</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";

    }
    //Display the product's specifications
    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    $qry = "SELECT s.SpecName, ps.SpecVal
            FROM productspec ps INNER JOIN specification s ON ps.SpecID = s.SpecID
            WHERE ps.ProductID =?
            Order by ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid); 
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    while ($row2 = $result2->fetch_array()){
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br />";
    }
    echo "</div>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    }
    echo "</div>";
}



// To Do 2:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
