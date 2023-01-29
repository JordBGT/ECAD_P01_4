<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>

<div class="container p-5">
    <div class="row">
        <div class="col-8 m-auto">
            <div class="card shadow">
                <div class="card-body" style="background-color: #DAAFF4; border-radius: 5px;">
                    <div class="row">
                        <h2 class="col-8 loginheader text-center" style="color: #4E004A;">Account Details</h2>
                        <div class="col-4 text-center m-auto">
                            <a href="editProfile.php">
                                <button class="btn btn-primary editprofile-button">Edit Profile</button>
                            </a>
                        </div>
                    </div>
<?php
//include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//define the SELECT SQL statement
$qry = "SELECT * FROM Shopper WHERE ShopperID=(?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $_SESSION["ShopperID"]); //"i" means integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
while ($row = $result->fetch_array()) {
    $shopperName = $row['Name'];
    $birthDate = $row["BirthDate"];
    $address = $row["Address"];
    $country = $row["Country"];
    $phone = $row["Phone"];
    $email = $row["Email"];
    $password = $row["Password"];
    $question = $row["PwdQuestion"];
    $answer = $row["PwdAnswer"];
    echo "<div class='d-flex-col justify-content-center mb-3'><p class='font-weight-bold my-1 text-center'>Name</p>";
    echo "<div class='detailborder px-5'><p class='text-center my-auto p-2'>$shopperName</p></div></div>";
    echo "<div class='d-flex-col justify-content-center mb-3'><p class='font-weight-bold my-1 text-center'>E-mail Address</p>";
    echo "<div class='detailborder px-5'><p class='text-center my-auto p-2'>$email</p></div></div>";
    echo "<div class='d-flex-col justify-content-center mb-3'><p class='font-weight-bold my-1 text-center'>Country</p>";
    echo "<div class='detailborder px-5'><p class='text-center my-auto p-2'>$country</p></div></div>";
    echo "<div class='d-flex-col justify-content-center mb-3'><p class='font-weight-bold my-1 text-center'>Address</p>";
    echo "<div class='detailborder px-5'><p class='text-center my-auto p-2'>$address</p></div></div>";
    echo "<div class='d-flex-col justify-content-center mb-3'><p class='font-weight-bold my-1 text-center'>Phone Number</p>";
    echo "<div class='detailborder px-5'><p class='text-center my-auto p-2'>$phone</p></div></div>";
    echo "<div class='d-flex-col justify-content-center mb-3'><p class='font-weight-bold my-1 text-center'>Birth Date</p>";
    echo "<div class='detailborder px-5'><p class='text-center my-auto p-2'>$birthDate</p></div></div>";
}
?>            
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Include the Page Layout header
include("footer.php");
?>