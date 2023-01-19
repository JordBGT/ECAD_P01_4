<!-- 3rd row-->
            <div class="row">
                <div class="col-sm-12"></div>
            </div>
            <!-- Search Bar -->
                <div style="width:80%; margin:auto; margin-top: 2.5%;"> <!-- Container -->
                    <form name="frmSearch" method="get" action="">
                        <div class="form-group row">
                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" name="keywords" id="keywords" type="search" placeholder="Search for a product here" />
                            </div>
                            <div class="col-sm-3">
                                <button class="search-button" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Product Catalog On Offer -->
<?php
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
    // Starting ....
    $keywords = $_GET["keywords"];
    $keywords = "%$keywords%";

    include_once("mysql_conn.php"); // Include the database connection

    //search for product with product description that matches the keyword


    $qry = "SELECT * FROM product WHERE ProductTitle LIKE ? OR ProductDesc LIKE ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ss", $keywords, $keywords);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();

    // Display the list of products in a table
    if ($result->num_rows > 0) {
        echo "<div class='row'>";
        //display "Search result for keyword: <keyword>"
        echo "<div class='col-sm-12' style='padding:5px'>";
        echo "<span class='page-title
                '>Search result for $_GET[keywords]:</span>";
        echo "</div>";
        echo "</div>";

        while ($row = $result->fetch_array()) {
            echo "<div class='row'>";
            echo "<div class='col-sm-12' style='padding:5px'>";
            echo "<a href='productDetails.php?pid=$row[ProductID]'>$row[ProductTitle]</a>";
            echo "</div>";
            echo "</div>";
        }


    } else {
        //display "No search result for keyword: <keyword>"
        echo "<div class='row'>";
        echo "<div class='col-sm-12' style='padding:5px'>";
        echo "<span class='page-title
                '>No search result for keyword: $_GET[keywords]</span>";
        echo "</div>";
        echo "</div>";

    }

    


	// To Do (DIY): End of Code
}