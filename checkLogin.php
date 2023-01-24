<?php
//Detect the current session
session_start();
//Include the Page Layout header
include("header.php"); 

//Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

//Validate login credentials with database
//include the PHP file that establishes database connection handle: $conn
include_once( "mysql_conn.php");

//define the SELECT SQL statement
$qry = "SELECT * FROM Shopper WHERE Email = '$email' AND Password = '$pwd'";
$stmt = $conn->query($qry);

while (($row = $stmt->fetch_array())
	&& ($row["Email"] == $email) && ($row["Password"] == $pwd)) {

	//Save user's info in session variables
	$_SESSION["ShopperName"] = $row["Name"];
	$_SESSION["ShopperID"] = $row["ShopperID"];

	//Get active shopping cart
	$qry = "SELECT sc.ShopCartID, COUNT(sci.ProductID) AS NumItems
			FROM shopcart sc LEFT JOIN shopcartitem sci
			ON sc.ShopCartID=sci.ShopCartID
			WHERE sc.OrderPlaced=0 AND sc.ShopperID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["ShopperID"]); //"i" - integer
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	if ($result->num_rows > 0) {
		$row2 = $result->fetch_array();
		$_SESSION["Cart"] = $row2["ShopCartID"];
		$_SESSION["NumCartItem"] = $row2["NumItems"];
	}
	//close database connection
	$conn->close();

	//Redirect to home page
	header("Location: index.php");
	exit;
}
echo "<h3 style='color:red'>Invalid Login Credentials</h3>";

//Include the Page Layout footer
include("footer.php");
?>