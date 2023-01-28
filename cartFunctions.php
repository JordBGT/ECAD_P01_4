<?php 
session_start();
if (isset($_POST['action'])) {
 	switch ($_POST['action']) {
    	case 'add':
        	addItem();
            break;
        case 'update':
            updateItem();
            break;
		case 'remove':
            removeItem();
            break;
    }
}

function addItem() {
	//Check if user logged in 
	if (! isset($_SESSION["ShopperID"])) {
		//redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}
	//if a user clicks on "Add to Cart" button, insert/update the 
	//database and also the session variable for counting number of items in shopping cart.
	include_once("mysql_conn.php"); //Establish database connection handle: $conn
	//Check if a shopping cart exist, if not create a new shopping cart
	if (!isset($_SESSION["Cart"])) {
        //create a shopping cart for the shopper
        $qry = "INSERT INTO Shopcart(ShopperID) VALUES(?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $_SESSION["ShopperID"]); //"i" - integer
        $stmt->execute();
        $stmt->close();
        $qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
        $result = $conn->query($qry);
        $row = $result->fetch_array();
        $_SESSION["Cart"] = $row["ShopCartID"];
    }
  	//If the ProductID exists in the shopping cart, 
  	//update the quantity, else add the item to the Shopping Cart.
  	$pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $_SESSION["Cart"], $pid); //"i" - integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $addNewItem = 0;
    if ($result->num_rows > 0) { //selected product exists in shopping cart
        //increase the quantity of purchase

        //get current quantity
        $qry2 = "SELECT Quantity FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
        $stmt2 = $conn->prepare($qry2);
        $stmt2->bind_param("ii", $_SESSION["Cart"], $pid); //"i" - integer
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $stmt2->close();
        $row2 = $result2->fetch_array();
        $currentQuantity = $row2["Quantity"];

        $qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?, 10) WHERE ShopCartID=? AND ProductID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid); //"i" - integer
        $stmt->execute();
        $stmt->close();

        //get new quantity
        $qry3 = "SELECT Quantity FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
        $stmt3 = $conn->prepare($qry3);
        $stmt3->bind_param("ii", $_SESSION["Cart"], $pid); //"i" - integer
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $stmt3->close();
        $row3 = $result3->fetch_array();
        $newQuantity = $row3["Quantity"];


        if ($currentQuantity + $quantity > 10) {
            $itemsto = 10 - ($currentQuantity);
            $_SESSION["NumCartItem"] += $itemsto;
        }
        else{
            $_SESSION["NumCartItem"] += $quantity;
        }

    }
    else { //selected product has yet to be added to shopping cart
        //check if product is on offer and is within the promotion period
        $qryCheckOffer = "SELECT OfferedPrice FROM Product
                          WHERE ProductID=? AND OfferedPrice IS NOT NULL AND CURDATE() BETWEEN OfferStartDate AND OfferEndDate";
        $stmt = $conn->prepare($qryCheckOffer);
        $stmt->bind_param("i", $pid); //"i" - integer
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) { //product is on offer; use offer price instead
            $qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
            SELECT ?, ?, OfferedPrice, ProductTitle, ? FROM Product WHERE ProductID=?";
            $stmt = $conn->prepare($qry);
            //"iiii" - 4 integers
            $stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
            $stmt->execute();
            $stmt->close();
            $addNewItem = $quantity;
        }
        else { //product is not on offer; use normal price
            $qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
            SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
            $stmt = $conn->prepare($qry);
            //"iiii" - 4 integers
            $stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
            $stmt->execute();
            $stmt->close();
            $addNewItem = $quantity;
        }
    }
  	$conn->close();
  	//Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["NumCartItem"])) {
        $_SESSION["NumCartItem"] += $addNewItem;
    }
    else {
        $_SESSION["NumCartItem"] = $addNewItem;
    }
	//Redirect shopper to shopping cart page
	header ("Location: shoppingCart.php");
    exit;
}

function updateItem() {
	//Check if shopping cart exists 
	if (! isset($_SESSION["Cart"])) {
		//redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
    //if a user clicks on "Update" button, update the database
	//and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["Cart"];
    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    include_once("mysql_conn.php"); //establish database connection handle: $conn

    //retrieve quantity of item before deleting
    $qry = "SELECT Quantity FROM ShopCartItem WHERE ProductID=? AND ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $pid, $cartid); //"ii" - 2 integers
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $originalQuantity = $row['Quantity'];

    //update item quantity
    $qry = "UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $quantity, $pid, $cartid); //"i" - integer
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + ($quantity - $originalQuantity);
    header ("Location: shoppingCart.php");
    exit;
}

function removeItem() {
	if (! isset($_SESSION["Cart"])) {
		//redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	//if a user clicks on "Remove" button, update the database
	//and also the session variable for counting number of items in shopping cart.
    $cartid = $_SESSION["Cart"];
    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    include_once("mysql_conn.php"); //establish database connection handle: $conn

    //retrieve quantity of item before deleting
    $qry = "SELECT Quantity FROM ShopCartItem WHERE ProductID=? AND ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $pid, $cartid); //"ii" - 2 integers
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $quantity = $row['Quantity'];

    //delete item
    $qry = "DELETE FROM ShopCartItem WHERE ProductID=? AND ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $pid, $cartid); //"ii" - 2 integers
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] - $quantity;
    header ("Location: shoppingCart.php");
    exit;
}		
?>
