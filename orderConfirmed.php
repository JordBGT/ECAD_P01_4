<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if(isset($_SESSION["OrderID"])) {	

	echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
	echo"<p>Delivering To: $_SESSION[ShopperName]</p>";
    echo"<p>Order Summary:</p>";
    foreach($_SESSION['Items'] as $key=>$item) {
        $a = $item["quantity"];
        $b = $item["name"];
        echo "<p>$b x $a</p>";
    }
    
    if ($_SESSION["ShipCharge"] == "5") {
        echo" As you have chosen Normal Delivery, Your items will be delivered within 2 working days after your order is placed! 
        Thank you for your purchase!</p>";
    }
    else {
        echo" As you have chosen Express Delivery, Your items will be delivered within 24 hours. Thank you for your purchase!</br>";
    }

    //unset session variables


    echo "<a href='index.php'>Continue shopping</a></p>";
} 

include("footer.php"); // Include the Page Layout footer
?>
