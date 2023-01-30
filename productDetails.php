<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<!-- <div style='width:60%; margin:auto;'> -->

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
    echo "<section class='py-5'>";

    $isOffered = $row['Offered'];
    $formattedPrice = number_format($row["Price"], 2);
    $formattedOfferedPrice = number_format($row['OfferedPrice'], 2);
	$offerStartDate = $row['OfferStartDate'];
	$offerEndDate = $row['OfferEndDate'];
    $img = "./Images/products/$row[ProductImage]";

    echo "<div class='container px-4 px-lg-5 my-5'>";
    echo "<div class='row gx-4 gx-lg-5 align-items-center'>";
    echo "<div class='col-md-6'><img class='card-img-top mb-5 mb-md-0' src='$img' alt='Product image' /></div>";
    echo "<div class='col-md-6'>";
    
    if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
		echo "<h2 class='display-5 fw-bolder'>$row[ProductTitle] <span style='color:red'>(Now On Offer!!)</span></h2>";
	}else{
		echo "<h2 class='display-5 fw-bolder'>$row[ProductTitle]</h2>";
	}

    if($isOffered == 1 && $offerStartDate <= date("Y-m-d") && $offerEndDate >= date("Y-m-d")){
        echo "<div class='fs-5 mb-4'><h3 style='text-decoration:line-through'>Original price:S$$formattedPrice</h3> <h3 style='color:red'>Offered price:S$$formattedOfferedPrice</h3></div>";

	}else{
        echo "<div class='fs-5 mb-4'><h3 style='color:red'>Price:S$$formattedPrice</h3></div>";
	}
    echo "<p class='lead'>$row[ProductDesc]</p>";
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
        echo "<table class='table table-striped table-bordered table-hover'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Specification</th>";
        echo "<th>Value</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row2 = $result2->fetch_array()){
            echo "<tr>";
            echo "<td>$row2[SpecName]</td>";
            echo "<td>$row2[SpecVal]</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        
    echo "<div class='d-flex'>";

    $qry = "SELECT Quantity from product where ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    while ($row = $result->fetch_array()){
        if ($row["Quantity"] <= 0){
            echo "<button class='btn btn-outline-dark flex-shrink-0' type='button' disabled>Out of Stock</button>";
        }else{
            echo "<form action='cartFunctions.php' method='post'>";
            echo "<input type='hidden' name='action' value='add' />";
            echo "<input type='hidden' name='product_id' value='$pid' />";

            
            echo "<div class='input-group mb-3'>";
            echo "<label class='col-form-label' for='quantity'>Quantity:</label>";

            echo "<select name='quantity' class='form-select' aria-label='Default select example'>";
            if ($row["Quantity"] >= 10){
                for ($i=1; $i<=10; $i++){
                    echo "<option value='$i'>$i</option>";
                }
            }else{
                for ($i=1; $i<=$row["Quantity"]; $i++){
                    echo "<option value='$i'>$i</option>";
                }
            }
            echo "</select>";
            echo "<button class='btn btn-outline-dark flex-shrink-0' type='submit'>Add to Cart</button>";
            echo "</div>";
            echo "</form>";
        }
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</section>";
}

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
