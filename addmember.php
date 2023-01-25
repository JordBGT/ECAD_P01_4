<?php
session_start(); // Detect the current session

// Read the data input from previous page
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$birthdate = $_POST["birthdate"];
$country = $_POST["country"];
$address = $_POST["address"];
$phone = $_POST["phone"];
$question = $_POST["question"];
$answer = $_POST["answer"];

// Create a password hash using the default bcrypt algorithm
// $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Check if user email existed in database

$qry = "SELECT * FROM Shopper WHERE Email = (?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows <= 0) {
    // Define the INSERT SQL Statement
    $qry = "INSERT INTO Shopper (Name, Email, Password, BirthDate, Country, Address, Phone, PwdQuestion, PwdAnswer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($qry);
    // "ssssss" - 9 string parameters
    $stmt->bind_param("sssdsssss", $name, $email, $password, $birthdate, $country, $address, $phone, $question, $answer);

    if ($stmt->execute()) { // SQL statement executed successfully
        // Retrieve the Shopper ID assigned to the new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry); // Execute the SQL and get the returned result
        while($row=$result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        // Successful message and Shopper ID
        $message = "Registration successful!<br>
                    Your ShopperID is $_SESSION[ShopperID]<br/>";

        // // Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;
    }
    else { // Error Message
        $message = "<p class='text-center' style='color:red'>Error in inserting records. <br>
        <a href='register.php'>Return to register page</a></p>";
    }
    // Release the resource allocated for prepared statement
    $stmt-> close();
    // Close the database connection
    $conn->close();
}
else {
    $message = "<p class='text-center mt-6'style='color:red; margin-top: 50px;'>Email already exists in database.<br>
    <a href='register.php'>Return to register page</a></p>";
}
//Display Page Layout header with updated session stae and links
include("header.php");
//Display message
echo $message;
//Display Page Layout footer
include("footer.php");
?>

