<div>
            <!-- Search Bar -->
                <div style="width:80%; margin:auto; margin-top: 2.5%;"> <!-- Container -->
                    <form name="frmSearch" method="get" action="">
                        <div class="form-group row">


                            <div class="col-sm-6">
                                <label for="txtSearch">Search keywords</label>
                                <input class="form-control" name="keywords" id="keywords" type="search" placeholder="Search for a product here" />
                            </div>

                            <div class="col-sm-1">
                            <!-- User input a minimum price -->
                                <label for="minPrice">Min Price</label>
                                <input class="form-control" name="min_price" id="min_price" type="number" placeholder="Min" />
                            </div>

                            <div class="col-sm-1">
                            <!-- User input a maximum price -->
                                <label for="maxPrice">Max Price</label>
                                <input class="form-control" name="max_price" id="max_price" type="number" placeholder="Max" />
                            </div>

                            <div class="col-sm-2">
                                <input type="checkbox" id="on-offer" name="on-offer" value="1">
                                <label for="on-offer">Currently On Offer</label>
                            </div>

                            <div class="col-sm-2">
                                <button class="search-button" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            <!-- Product Catalog On Offer -->
</div>
<?php
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
        $qry = "SELECT * FROM product WHERE (Offered = 0 AND (ProductTitle LIKE ? OR ProductDesc LIKE ?) AND Price >= ? AND Price <= ?) OR
         (Offered = 1 AND (OfferStartDate > NOW() AND OfferEndDate < NOW()) AND (ProductTitle LIKE ? OR ProductDesc LIKE ?) AND OfferedPrice >= ? AND OfferedPrice <= ?) OR
            (Offered = 1 AND (OfferStartDate < NOW() AND OfferEndDate < NOW()) OR (OfferStartDate > NOW() AND OfferEndDate > NOW()) AND (ProductTitle LIKE ? OR ProductDesc LIKE ?) AND Price >= ? AND Price <= ?)";
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