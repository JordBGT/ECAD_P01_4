<?php
session_start(); // Detect the current session

// Read the data input from previous page
$currentpassword = $_POST["currentpassword"];
$newpassword = $_POST["newpassword"];

// Create a password hash using the default bcrypt algorithm
// $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$qry = "SELECT * FROM Shopper WHERE ShopperID = (?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $_SESSION["ShopperID"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_array()) {
    $password = $row["Password"];
}


if ($password == $currentpassword) {
    // Define the UPDATE SQL Statement
    $qry = "UPDATE Shopper
    SET Password = (?)
    WHERE ShopperID = (?)";
    $stmt = $conn->prepare($qry);
    // "ssssss" - 9 string parameters
    $stmt->bind_param("si",$newpassword, $_SESSION["ShopperID"]);

    if ($stmt->execute()) { // SQL statement executed successfully
        // Successful message and Shopper ID
        $message = "<div class='d-flex flex-column justify-content-center'>
        <h3 class='text-center mt-6'style='color:red; margin-top: 50px;'>Password update successful!</h3>
		<a class='text-center' href='index.php'>Return to homepage</a></div>";
    }

    else { // Error Message
        $message = "<p class='text-center' style='color:red'>Error in inserting records. <br>
        <a href='editPassword.php'>Return to edit password page</a></p>";
    }
    // Release the resource allocated for prepared statement
    $stmt-> close();
    // Close the database connection
    $conn->close();
}
else {
    $message = "<p class='text-center mt-6'style='color:red; margin-top: 50px;'>Inserted current password does not match in database, please <a href='editPassword.php'>try again</a>!</p>";
}
//Display Page Layout header with updated session stae and links
include("header.php");
//Display message
echo $message;
//Display Page Layout footer
include("footer.php");
?>

