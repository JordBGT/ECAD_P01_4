<!-- display products on offer-->
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

    // Display the list of products in a table
    if ($result->num_rows > 0) {
        echo "<div class='row'>";
        echo "<div class='col-sm-12'></div>";
        echo "</div>";
        echo "<div style='width:50%; margin:auto; margin-top: 2.5%'>";
        echo "<div class='col-sm-2'></div>";
        echo "<div class='col-sm-8' style='padding:5px'>";
        echo "<span class='page-title' style='font-size:2em'>Search result for <span style = 'color: red;'>$_GET[keywords]</span>:</span>";
        echo "</div>";
        echo "<div class='col-sm-2'></div>";
        echo "</div>";

        while ($row = $result->fetch_array()) {
            echo "<div class='row'>";
            echo "<div class='col-sm-12'></div>";
            echo "</div>";
            echo "<div style='width:50%; margin:auto; margin-top: 0.5%'>";
            echo "<div class='col-sm-12' style='padding:5px'>";
            echo "<p><a href='productDetails.php?pid=$row[ProductID]' style='font-size:1.5em'>$row[ProductTitle]</a></p>";
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
	// To Do (DIY): End of Code
}

include("footer.php"); // Include the Page Layout footer
?>