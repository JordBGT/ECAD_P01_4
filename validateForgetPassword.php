<?php
session_start(); // Detect the current session

// Read the data input from previous page
$inputanswer = $_POST["answer"];
$inputemail = $_POST["email"];

// Create a password hash using the default bcrypt algorithm
// $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$qry = "SELECT * FROM Shopper WHERE Email = (?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $inputemail);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_array()) {
    $answer = $row["PwdAnswer"];
    $password = $row["Password"];
}

if ($answer == $inputanswer) {
    // Return answer
    $message = "<div class='d-flex flex-column justify-content-center'>
    <h3 class='text-center mt-6'style='color:red; margin-top: 50px;'>Password Recovery Success!</h3>
    <p class='text-center'> Your password is: <span style='color:red'>$password</span></p>
    <a class='text-center' href='login.php'>Return to login page</a></div>";

    // Close the database connection
    $conn->close();
}
else {
    $message = "<p class='text-center mt-6'style='color:red; margin-top: 50px;'>Inserted answer does not match current answer in database, please <a href='identifyUser.php'>try again</a>!</p>";
}
//Display Page Layout header with updated session stae and links
include("header.php");
//Display message
echo $message;
//Display Page Layout footer
include("footer.php");
?>

