<?php
session_start();
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

include_once("mysql_conn.php");
// To Do 1 (Practical 4): 
// Retrieve from database and display shopping cart in a table
$qry = "SELECT *, (Price*Quantity) AS Total
            FROM ShopCartItem WHERE ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["Cart"]); //"i" - integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

if ($result->num_rows > 0) {
    // To Do 2 (Practical 4): Format and display 
    // the page header and header row of shopping cart 
    echo "<p class='page-title' style='text-align:center'>Choose your delivery mode!</p>"; 
    echo "<div class='table-responsive' >"; // Bootstrap responsive table
    echo "<table class='table table-hover'>"; // Start of table
    echo "<thead class='cart-header'>"; // Start of table's header section
    echo "<tr>"; // Start of header row
    echo "<th width='650px'>Item</th>";
    echo "<th width='90px'>Price (S$)</th>";
    echo "<th width='60px'>Quantity</th>";
    echo "<th width='120px'>Total (S$)</th>";
    echo "<th>&nbsp;</th>";
    echo "</tr>"; // End of header row
    echo "</thead>"; // End of table's header section
    
    // To Do 5 (Practical 5):
    // Declare an array to store the shopping cart items in session variable 
    $_SESSION["Items"]=array();


    // To Do 3 (Practical 4): 
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width: 50 %'> $row[Name]<br />";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row ["Price"], 2);
			echo "<td>$formattedPrice</td>";
			echo "<td> $row[Quantity]</td>";
			$formattedTotal = number_format($row["Total"], 2);
			echo "<td>$formattedTotal</td>";
			echo "</tr>";

            // To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][]= array("productID"=>$row["ProductID"],
            "name"=>$row["Name"],
            "price"=>$row["Price"],
            "quantity"=>$row["Quantity"]);

            // Accumulate the running sub-total
            $subTotal += $row["Total"];
        }
        echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
				
		// To Do 4 (Practical 4): 
		// Display the subtotal at the end of the shopping cart
		echo "<p style='text-align:right; font-size:20px'> 
				Subtotal = S$".number_format($subTotal,2);
		$_SESSION["SubTotal"] = round($subTotal,2);
        
        //Displaying Mode of Delivery option
        echo "<br/>";
        echo"<td>";
        echo "<form method = 'post'>";
        echo"Mode of Delivery: ";
        echo "<select name= 'mod'>";
        echo "<option value='Normal'>Normal Delivery</option>";
        echo "<option value ='Express'>Express Delivery</option>";
        echo "</select>";

        echo "<input type='submit' name='submit'>";
        echo "</form>";
        echo"<td>";


        if(isset($_POST['mod'])){
            if($_POST['mod'] == "Normal"){
                $_SESSION["ModeOfDelivery"] = "Normal";
                echo "You have chosen the Normal Delivery for your Order!";
                echo"<br/>";
                echo "Sub Total: S$".number_format($_SESSION["SubTotal"],2);
                echo"<br/>";
                echo "Delivery Fee: S$5";
                $totalAmount = $_SESSION["SubTotal"] + 5;

                echo "<p style='font-size:20px'> 
				Total = S$".number_format($totalAmount,2);

                // $_SESSION["TotalAmount"] = $totalAmount;

                // Add PayPal Checkout button on the shopping cart page
		        echo "<form method='post' action='checkoutProcess.php'>";
		        echo "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		        echo "</form></p>";
            }
            else
            {
                $_SESSION["ModeOfDelivery"] = "Express";
                echo "You have chosen the Express Delivery for your Order!"; 
                echo"<br/>";
                echo "Sub Total: S$".number_format($_SESSION["SubTotal"],2);
                echo"<br/>";
                echo "Delivery Fee: S$10";
                $totalAmount = $_SESSION["SubTotal"] + 10;

                echo "<p style='font-size:20px'> 
				Total = S$".number_format($totalAmount,2);
                // $_SESSION["TotalAmount"] = $totalAmount;

                // Add PayPal Checkout button on the shopping cart page
		        echo "<form method='post' action='checkoutProcess.php'>";
		        echo "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		        echo "</form></p>";
            }

            $qry2 = "SELECT * 
            FROM Shopper WHERE ShopperID=?";
            $stmt = $conn->prepare($qry2);
            $stmt->bind_param("i", $_SESSION["ShopperID"]); //"i" - integer
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

	        if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $SESSION["ShopperName"] = $row["Name"];
                }
		        
            }

        }


echo "</div>"; // End of container
echo "</br>";
include("footer.php"); // Include the Page Layout footer
}
$conn->close(); // Close database connection
?>