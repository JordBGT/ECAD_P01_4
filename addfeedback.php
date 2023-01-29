<?php
session_start(); // Detect the current session

// Read the data input from previous page
$subject = $_POST["subject"];
$content = $_POST["content"];
$rank = $_POST["rank"];

// Create a password hash using the default bcrypt algorithm
// $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$qry = "INSERT INTO Feedback(ShopperID, Subject, Content, Rank, DateTimeCreated) VALUES ((?),(?),(?),(?),CURRENT_TIMESTAMP)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("issi", $_SESSION["ShopperID"], $subject, $content, $rank);

if ($stmt->execute()) { // SQL statement executed successfully
    // Successful message and Shopper ID
    $message = "<p class='text-center mt-5' style='color:red'>Feedback submission successful!<br>
        <a href='feedback.php'>View feedbacks</a></p>";

}
else { // Error Message
    $message = "<p class='text-center' style='color:red'>Error in inserting records. <br>
    <a href='feedback.php'>Return to feedback page</a></p>";
}
// Release the resource allocated for prepared statement
$stmt-> close();
// Close the database connection
$conn->close();

include("header.php");
//Display message
echo $message;
//Display Page Layout footer
include("footer.php");
?>

