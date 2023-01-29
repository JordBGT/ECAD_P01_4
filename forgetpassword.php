<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>

<?php

// Read the data input from previous page
$email = $_POST["email"];

//include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//check if email exist in database
$qry = "SELECT * FROM Shopper WHERE Email=(?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email); 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows != 0) {
    while ($row = $result->fetch_array()) {
        $question = $row["PwdQuestion"];
        $answer = $row["PwdAnswer"];
    }

    echo "<div class='container p-5'>
    <div class='row'>
        <div class='col-12 col-sm-6 col-md-7 m-auto'>
            <div class='card border-0 shadow'>
                <div class='card-body' style='background-color: #DAAFF4; border-radius: 5px;'>
                    <div class='m-auto'>
                        <h2 class='loginheader text-center mb-4' style='color: #4E004A;'>Password Recovery</h2>
                    </div>
                    <form name='validateforgetpassword' action='validateForgetPassword.php' method='post'>
                        <div class='row'>
                            <div class='col-10 mx-auto'>
                                <input type='email' name='email' id='email' class='form-control mb-4 py-2' placeholder='$email' value='$email' hidden/>
                                <label class='registerform-label pl-1' for='question'>Password Recovery Question</label>
                                <input type='question' name='question' id='question' class='form-control mb-4 py-2' placeholder='$question' readonly/>
                                <label class='registerform-label pl-1' for='answer'>Answer for Password Recovery Question</label>
                                <input type='answer' name='answer' id='answer' class='form-control mb-4 py-2' placeholder='Enter your answer here' required/>
                            </div>
                        </div>
                        <div class='text-center mx-auto mt-3'>
                                <button class='btn btn-primary register-button'>Recover Password</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>";
}

else {
     $message = "<p class='text-center mt-6'style='color:red; margin-top: 50px;'>Email does not exist in database.<br>
    <a href='index.php'>Return to homepage</a></p>";
    //Display Page Layout header with updated session stae and links
    echo $message;
}


    //Display Page Layout footer
    include("footer.php");
?>