<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

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
    $isOffered = $row['Offered'];
    $formattedPrice = number_format($row["Price"], 2);
    $formattedOfferedPrice = number_format($row['OfferedPrice'], 2);
	$offerStartDate = $row['OfferStartDate'];
	$offerEndDate = $row['OfferEndDate'];
    //Display page header
    //Product name is read from the "ProductTitle" column of "product" table
    echo "<div class='row' >";
    echo "<div class='col-sm-12' style='padding:5px'>";
    if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<p style='font-size:2em;'>$row[ProductTitle] <span style='color:red'>(Now On Offer!!)</span></p>";
	}else{
		echo "<pstyle='font-size:2em;'>$row[ProductTitle]</p>";
	}

    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";

    //Left column - display the product description
    echo "<div class='col-sm-9' style='padding:5px'>";
    echo "<p>$row[ProductDesc]</p>";

    //Display the product's specifications
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

    //Right column - display the product image
    $img = "./Images/products/$row[ProductImage]";
    echo "<div class='col-sm-3' style='vertical-align:top padding:5px'>";
    echo "<p><img src=$img  /></p>";

    //Right column - Display the product's price

    // echo "Price:<span style='font-weight:bold; color:red'>
    //  S$ $formattedPrice</span>";
    if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<p>Original Price: <span style='text-decoration:line-through'>S$ $formattedPrice</span> </p>";
		echo "<p><span style='font-weight:bold; color:red; font-size:1.5em'>Offered Price: S$ $formattedOfferedPrice</span></p>";
	}else{
		echo "<p style='font-size:1.5em'>Price: <span style='font-weight:bold; color:red;'> S$ $formattedPrice</span></p>";
	}
}

// To Do 1:  Ending ....

//check the stock of the product with $pid, if the stock is 0, then display "Out of Stock", and disable the "Add to Cart" button
$qry = "SELECT Quantity from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_array()){
    if ($row["Quantity"] <= 0){
        echo "<p style='color:red'>Out of Stock</p>";
    }else{
        echo "<form action='cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";
        echo "Quantity: <input type='number' name='quantity' value='1'
            min='1' max='10' style='width:40px' required/>";
        echo "<button type='submit'>Add to Cart</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        }
}


// To Do 2:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
