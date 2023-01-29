<?php
session_start(); // Detect the current session

// Read the data input from previous page
$newname = $_POST["name"];
$newemail = $_POST["email"];
$newbirthdate = $_POST["birthdate"];
$newcountry = $_POST["country"];
$newaddress = $_POST["address"];
$newphone = $_POST["phone"];

// Create a password hash using the default bcrypt algorithm
// $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// retrieve existing details
$qry = "SELECT * FROM Shopper WHERE ShopperID = (?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $_SESSION["ShopperID"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_array()) {
    $currentshopperid = $row['ShopperID'];
    $currentshopperName = $row['Name'];
    $currentbirthDate = $row["BirthDate"];
    $currentaddress = $row["Address"];
    $currentcountry = $row["Country"];
    $currentphone = $row["Phone"];
    $currentemail = $row["Email"];
    $currentpassword = $row["Password"];
    $currentquestion = $row["PwdQuestion"];
    $currentanswer = $row["PwdAnswer"];
}

// Check if new email existed in database
$qry = "SELECT * FROM Shopper WHERE Email = (?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $newemail);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows <= 1 && $newemail == $currentemail) {
    // Define the INSERT SQL Statement
    $qry = "UPDATE Shopper 
    SET Name = (?), Email= (?), BirthDate = (?), Country = (?), Address = (?), Phone = (?) 
    WHERE ShopperID = (?)";
    $stmt = $conn->prepare($qry);
    // "ssssss" - 9 string parameters
    $stmt->bind_param("ssdsssi", $newname, $newemail, $newbirthdate, $newcountry, $newaddress, $newphone,$currentshopperid);

    if ($stmt->execute()) { // SQL statement executed successfully
        // Successful message and Shopper ID
        $message = "<div class='d-flex flex-column justify-content-center'>
        <h3 class='text-center mt-6'style='color:red; margin-top: 50px;'>Update successful!</h3>
		<a class='text-center' href='index.php'>Return to homepage</a></div>";
        // // Save the Shopper Name in a session variable

        $_SESSION["ShopperName"] = $newname;
    } 
    else { // Error Message
        $message = "<p class='text-center' style='color:red'>Error in inserting records. <br>
        <a href='editProfile.php'>Return to edit profile page</a></p>";
    }
    // Release the resource allocated for prepared statement
    $stmt->close();
    // Close the database connection
    $conn->close();
}

// new email exists in database and is not current email
else {
    $message = "<p class='text-center mt-6'style='color:red; margin-top: 50px;'>Email already exists in database.<br>
    <a href='editProfile.php'>Return to edit profile page</a></p>";
}

//Display Page Layout header with updated session stae and links
    include("header.php");
    //Display message
    echo $message;
    //Display Page Layout footer
    include("footer.php");
?>

