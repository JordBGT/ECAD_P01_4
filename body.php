<!-- display products on offer-->
<section>
  <div class="text-center container py-5">
    <h4 class="mt-4 mb-5"><strong>Products on offer</strong></h4>


<?php
include_once("mysql_conn.php");
$qry = "SELECT * FROM Product WHERE Offered = 1 AND OfferStartDate <= CURDATE() AND OfferEndDate >= CURDATE()";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$productCount = 0;
echo "<div class='row'>";
//Display products, 3 per row

while ($row = $result ->fetch_array()){
    $productCount = $productCount + 1;
    $product = "productDetails.php?pid=$row[ProductID]";
    $productName = $row['ProductTitle'];
    $originalPrice = number_format($row['Price'], 2);
    $formattedPrice = number_format($row['OfferedPrice'], 2);
    $productImage = $row['ProductImage'];

    if($productCount == 4){
        
        echo "</div>";
        echo "<div class='row'>";
    }

    echo "<div class='col-lg-4 col-md-12 mb-4 d-flex'>";
    echo "<div class='card'>";
    echo "<div class='bg-image hover-zoom ripple ripple-surface ripple-surface-light' data-mdb-ripple-color='light'>";
    echo "<img src='./Images/products/$productImage' class='w-100' />";
    echo "<a href='$product'>";
    echo "<div class='mask'>";
    echo "<div class='d-flex justify-content-center align-items-center h-100'>";
    echo "<h5><span class='badge bg-danger ms-2'>Offer</span></h5>";
    echo "</div>";
    echo "</div>";
    echo "<div class='hover-overlay'>";
    echo "<div class='mask' style='background-color: rgba(251, 251, 251, 0.15)'></div>";
    echo "</div>";
    echo "</a>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<a href='$product' class='text-reset text-decoration-none'>";
    echo "<h4 class='card-title mb-3'>$productName</h4>";
    echo "</a>";
    echo "<h6 class ='mb-3'><s>S$$originalPrice</s><strong class='ms-2 text-danger'> S$$formattedPrice</strong></h6>";
    echo "<a href='$product' class='btn btn-primary editprofile-button'>View</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    if($productCount == 4){
        echo "</div>";
        // echo "<div class='row'>";
    }
}

?>


