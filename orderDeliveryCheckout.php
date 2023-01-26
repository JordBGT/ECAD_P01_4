<?php
session_start();
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

include_once("mysql_conn.php");

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
    
    // Declare an array to store the shopping cart items in session variable 
    $_SESSION["Items"]=array();

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
				

		// Display the subtotal at the end of the shopping cart
		echo "<p style='text-align:right; font-size:20px'> 
				Subtotal = S$".number_format($subTotal,2);
		$_SESSION["SubTotal"] = round($subTotal,2);
        
        //Displaying and getting input for Mode of Delivery
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

        // Retrive gst from database
        $qry2 = "SELECT * FROM gst WHERE EffectiveDate < curdate()
        ORDER BY EffectiveDate DESC LIMIT 1";
        $stmt = $conn->prepare($qry2);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                $tax = $row["TaxRate"]; 
            }
        }

        // Checking Mode of Delivery
        if(isset($_POST['mod'])){
            //If Mode Of Delivery is Normal
            if($_POST['mod'] == "Normal"){
                $_SESSION["ModeOfDelivery"] = "Normal";
                $_SESSION["TaxFromCurrentYear"] = $tax;

                $taxAmount = round($_SESSION["SubTotal"] * ($_SESSION["TaxFromCurrentYear"]/100),2);

                echo "You have chosen the Normal Delivery for your Order!";
                echo"<br/>";
                echo "Sub Total: S$".number_format($_SESSION["SubTotal"],2);
                echo"<br/>";
                echo "Delivery Fee: S$5";
                echo "<br/>";
                echo "Tax (%) : $tax %";
                echo "<br/>";
                echo "Tax Amount: S$ $taxAmount";
                echo "<br/>";
                
                // Adding Delivery Fee and Tax Amount to Total Amount
                $totalAmount = $_SESSION["SubTotal"] + 5 + $taxAmount;

                echo "<p style='font-size:20px'> 
				Total = S$ $totalAmount";
                

                // Add Form and PayPal Checkout button on the OrderDeliveryCheckout page
		        echo "<form method='post' action='checkoutProcess.php'>";
                // Ask for Bill Name
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Deliver To&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' name='BillName' id='BillName' placeholder='John Ecader' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Recipient Mobile Number
                echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Recipient Mobile No&nbsp;<abbr style='color: red;'>*</abbr></label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='ShipPhone' name='ShipPhone' placeholder='(65) 1234 5678' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Recipient Email
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Recipient Email&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&emsp;&ensp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='ShipEmail' name='ShipEmail' placeholder='' required>";
                echo "<br/>";
                echo "<br/>";

                 //Ask for Delivery Date
                 echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Delivery Date&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&nbsp;</label>
                 <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='DeliveryDate' name='DeliveryDate' placeholder='YYYY-MM-DD' required>";
                 echo "<br/>";
                 echo "<br/>";

                 //Ask for Delivery Time
                  echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Delivery Time&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&nbsp;</label>
                  <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='DeliveryTime' name='DeliveryTime' placeholder='12pm-3pm' required>";
                  echo "<br/>";
                  echo "<br/>";

                //Ask for Message 
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Message&emsp;&emsp;&emsp;&emsp;</label>
                <textarea style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='Message' name='Message' placeholder='Merry Christmas!'></textarea>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Billing Address
                echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Billing Address&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&nbsp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='BillAddress' name='BillAddress' placeholder='' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Billing Number
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Billing Mobile No&nbsp;<abbr style='color: red;'>*</abbr></label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='BillPhone' name='BillPhone' placeholder='(65) 1234 5678' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Billing Email
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Billing Email&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&emsp;&ensp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='BillEmail' name='BillEmail' placeholder='' required>";
                echo "<br/>";
                echo "<br/>";


		        echo "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		        echo "</form></p>";
            }
            // If Mode of Delivery is not normal, Mode of Delivery = Express
            else
            {
                $_SESSION["ModeOfDelivery"] = "Express";
                $_SESSION["TaxFromCurrentYear"] = $tax;

                $taxAmount = round($_SESSION["SubTotal"] * ($_SESSION["TaxFromCurrentYear"]/100),2);

                echo "You have chosen the Express Delivery for your Order!"; 
                echo"<br/>";
                echo "Sub Total: S$".number_format($_SESSION["SubTotal"],2);
                echo"<br/>";
                echo "Delivery Fee: S$10";
                echo "<br/>";
                echo "Tax: $tax %";
                echo "<br/>";
                echo "Tax Amount: S$ $taxAmount";
                echo "<br/>";
                
                // Adding Delivery Fee and Tax Amount to Total Amount
                $totalAmount = $_SESSION["SubTotal"] + 10 + $taxAmount;

                echo "<p style='font-size:20px'> 
				Total = S$ $totalAmount";

                // Add Form and PayPal Checkout button on the OrderDeliveryCheckout page
		        echo "<form method='post' action='checkoutProcess.php'>";

                // Header
                echo" <h2 class='form-title' style='font-size: 36px; margin-bottom: 2.5rem; font-weight: 500; opacity: 0.8;'>Delivery Details</h2>";
                echo "<br/>";
                echo "<br/>";

                // Ask for Bill Name
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Deliver To&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' name='BillName' id='BillName' placeholder='John Ecader' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Recipient Mobile Number
                echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Recipient Mobile No&nbsp;<abbr style='color: red;'>*</abbr></label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='ShipPhone' name='ShipPhone' placeholder='(65) 1234 5678' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Recipient Email
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Recipient Email&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&emsp;&ensp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='ShipEmail' name='ShipEmail' placeholder='' required>";
                echo "<br/>";
                echo "<br/>";

                 //Ask for Delivery Date
                 echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Delivery Date&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&nbsp;</label>
                 <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='DeliveryDate' name='DeliveryDate' placeholder='YYYY-MM-DD' required>";
                 echo "<br/>";
                 echo "<br/>";

                 //Ask for Delivery Time
                  echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Delivery Time&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&nbsp;</label>
                  <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='DeliveryTime' name='DeliveryTime' placeholder='12pm-3pm' required>";
                  echo "<br/>";
                  echo "<br/>";

                //Ask for Message 
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Message&emsp;&emsp;&emsp;&emsp;</label>
                <textarea style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='Message' name='Message' placeholder='Merry Christmas!'></textarea>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Billing Address
                echo"<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Billing Address&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&nbsp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='BillAddress' name='BillAddress' placeholder='' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Billing Number
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Billing Mobile No&nbsp;<abbr style='color: red;'>*</abbr></label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='BillPhone' name='BillPhone' placeholder='(65) 1234 5678' required>";
                echo "<br/>";
                echo "<br/>";

                //Ask for Billing Email
                echo "<label for='name' style='font-size: 16px; color: white; margin-bottom: 0.5rem;'>Billing Email&nbsp;<abbr style='color: red;'>*</abbr>&emsp;&emsp;&ensp;</label>
                <input type='text' style='width: 100%; height: 40px; padding: 0 10px; background-color: #f2f2f2; border-radius: 5px; border: none; font-size: 18px;' id='BillEmail' name='BillEmail' placeholder='' required>";
                echo "<br/>";
                echo "<br/>";

		        echo "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		        echo "</form></p>";
            }

            // Getting Name for displaying at orderConfirmed.php
            $qry3 = "SELECT * 
            FROM Shopper WHERE ShopperID=?";
            $stmt = $conn->prepare($qry3);
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