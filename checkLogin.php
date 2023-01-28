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
	$qry = "SELECT sc.ShopCartID, SUM(sci.Quantity) AS NumItems
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
?>
<script>

//Redirect to the home page
window.location = "index.php";
</script>
<?php
	// exit;
}
echo "<p class='text-center mt-6'style='color:red; margin-top: 50px;'>Invalid login credentials.<br>
    <a href='login.php'>Return to login page</a></p>";;

//Include the Page Layout footer
include("footer.php");
?>