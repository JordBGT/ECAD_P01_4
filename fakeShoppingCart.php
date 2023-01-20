<?php
include("header.php"); // Include the Page Layout header

include_once("mysql_conn.php");
// To Do 1 (Practical 4): 
// Retrieve from database and display shopping cart in a table
$qry = "SELECT *, (Price*Quantity) AS Total
        FROM ShopCartItem WHERE ShopCartID=1";
$stmt = $conn->prepare($qry);
// $stmt->bind_param("i", $_SESSION["Cart"]); //"i" - integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    // To Do 2 (Practical 4): Format and display 
    // the page header and header row of shopping cart 
    echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
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
			echo "<td>"; // Column for update quantity of purchase
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this. form.submit()'>";
			for ($i = 1; $i <= 10; $i++) { // To populate drop-down list from 1 to 10
				if ($i ==$row["Quantity"])
				// Select drop-down list item with value same as the quantity of purchase
				$selected="selected";
				else
					$selected = ""; // No specific item is selected
				echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			echo "<td>$formattedTotal</td>";
			echo "<td>"; // Column for remove item from shopping cart
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='images/trash-can.png' title='Remove Item' />";
			echo "</form>";
			echo "</td>";
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

        // To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		echo "<form method='post' action='orderDeliveryCheckout.php'>";
		echo "<input type='submit' style='float:right;'>";
		echo "</form></p>";

echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
}
?>