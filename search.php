

  <!-- <div class="text-center container py-5"> -->
    <!-- <h4 class="mt-4 mb-5" style="color: #4E004A;"><strong>Products on offer</strong></h4> -->


<?php
session_start();
include("header.php"); // Include the Page Layout header


// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    $keywords = $_GET["keywords"];

    if (isset($_GET["min_price"]) && trim($_GET['min_price']) != "") {
        $min_price = $_GET["min_price"];
    } else {
        $min_price = 0;
    }

    if (isset($_GET["max_price"]) && trim($_GET['max_price']) != "") {
        $max_price = $_GET["max_price"];
    } else {
        $max_price = 20**20;
    }    

    //$on_offer is the checkbox value, check whether it is checked
    if (isset($_GET["on-offer"]) == 1) {
        $on_offer = 1;
    } else {
        $on_offer = 0;
    }
    $keywords = "%$keywords%";

    include_once("mysql_conn.php"); // Include the database connection

    //search for product with product description that matches the keyword


    if ($on_offer == 1) {
        $qry = "SELECT * FROM product WHERE (ProductTitle LIKE ? OR ProductDesc LIKE ? ) AND OfferedPrice >= ? AND OfferedPrice <= ? AND Offered = 1 AND OfferStartDate <= NOW() AND OfferEndDate >= NOW()";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ssii", $keywords, $keywords, $min_price, $max_price);
    }
     else {
        $qry = "SELECT * FROM product WHERE (Offered = 0 AND (ProductTitle LIKE ? OR ProductDesc LIKE ?) AND Price >= ? AND Price <= ?)
        UNION SELECT * FROM product WHERE (Offered = 1 AND (OfferStartDate <= NOW() AND OfferEndDate >= NOW()) AND (ProductTitle LIKE ? OR ProductDesc LIKE ?) AND OfferedPrice >= ? AND OfferedPrice <= ?)
        UNION SELECT * FROM product WHERE (Offered = 1 AND ((OfferStartDate < NOW() AND OfferEndDate < NOW()) OR (OfferStartDate > NOW() AND OfferEndDate > NOW())) AND (ProductTitle LIKE ? OR ProductDesc LIKE ?) AND Price >= ? AND Price <= ?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ssiissiissii", $keywords, $keywords, $min_price, $max_price, $keywords, $keywords, $min_price, $max_price, $keywords, $keywords, $min_price, $max_price);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    echo "<div class='text-center container py-5'>";
    echo "<div class='row'>";
    // Display the list of products in a table
    if ($result->num_rows > 0) {

        echo "<span class='page-title' style='font-size:2em'>Search result for <span style = 'color: red;'>$_GET[keywords]</span>:</span>";
        echo "</div>";

        echo "<div class='row'>";
        while ($row = $result ->fetch_array()){
            $product = "productDetails.php?pid=$row[ProductID]";
            $productName = $row['ProductTitle'];
            $originalPrice = number_format($row['Price'], 2);
            $formattedPrice = number_format($row['OfferedPrice'], 2);
            $productImage = $row['ProductImage'];
            echo "<div class='col-lg-4 col-md-12 mb-4 d-flex'>";
            echo "<div class='card'>";
            echo "<div class='bg-image hover-zoom ripple ripple-surface ripple-surface-light' data-mdb-ripple-color='light'>";
            echo "<img src='./Images/products/$productImage' class='w-100' />";
            echo "<a href='$product'>";
            echo "<div class='mask'>";
            echo "<div class='d-flex justify-content-center align-items-center h-100'>";
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
            if ($row['Offered'] == 1 && $row['OfferStartDate'] <= date("Y-m-d") && $row['OfferEndDate'] >= date("Y-m-d")) {
                echo "<h5><span class='badge bg-danger ms-2'>Offer</span></h5>";

                echo "<h6 class ='mb-3'><s>S$$originalPrice</s><strong class='ms-2 text-danger'> S$$formattedPrice</strong></h6>";
            } else {
                echo "<h6 class='mb-3'><span>$$originalPrice</span></h6>";
            }
            // echo "<h6 class ='mb-3'><s>S$$originalPrice</s><strong class='ms-2 text-danger'> S$$formattedPrice</strong></h6>";
            echo "<a href='$product' class='btn btn-primary editprofile-button'>View</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

        }

    } else {
        //display "No search result for keyword: <keyword>"
        echo "<div class='row'>";
        echo "<div class='col-sm-12'></div>";
        echo "</div>";

        echo "<div style='width:50%; margin:auto; margin-top: 2.5%'>";
        echo "<div class='col-sm-12' style='padding:5px'>";
        echo "<span class='page-title' style='font-size:2em'>No search result for <span style = 'color: red;'>$_GET[keywords]</span></span>";

        echo "</div>";
        echo "</div>";

    }
    echo "</div>";
    echo "</div>";
	// To Do (DIY): End of Code
}

include("footer.php"); // Include the Page Layout footer
?>