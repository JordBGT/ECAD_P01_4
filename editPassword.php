<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<script type="text/javascript">
function validateForm()
{
    // Check if password matched
	if (document.editpassword.newpassword.value != document.editpassword.newpassword2.value) {
        alert("Passwords not matched!");
        return false;
    }
	
    return true;  // No error found
}
</script>
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
    $displayed_password = str_repeat ('*', strlen ($password));
    $question = $row["PwdQuestion"];
    $answer = $row["PwdAnswer"];
}

echo "
<div class='container p-5'>
    <div class='row'>
        <div class='col-12 m-auto'>
            <div class='card border-0 shadow'>
                <div class='card-body container' style='background-color: #DAAFF4; border-radius: 5px;'>
                    <div class='m-auto'>
                        <svg class='d-block m-auto pb-2' xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='#4E004A' class='bi bi-person-circle' viewBox='0 0 16 16'>
                            <path d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z' />
                            <path fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z' />
                        </svg>
                        <h2 class='text-center pb-3' style='color: #4E004A;'>Change Password</h2>
                    </div>
                    <form name='editpassword' action='validatePasswordEdit.php' method='post' onsubmit='return validateForm()'>
                        <div class='row'>
                            <div class='col-4 mx-auto'>
                                <label class='registerform-label pl-1' for='password'>Current password</label>
                                <input type='password' name='currentpassword' id='currentpassword' class='form-control mb-4 py-2' placeholder='**********' required/>
                                <label class='registerform-label pl-1' for='password'>New password</label>
                                <input type='password' name='newpassword' id='newpassword' class='form-control mb-4 py-2' placeholder='**********' required/>
                                <label class='registerform-label pl-1' for='password2'>Re-enter new password</label>
                                <input type='password' name='newpassword2' id='newpassword2' class='form-control mb-4 py-2' placeholder='**********' required/>
                            </div>
                        </div>
                        <div class='text-center mx-auto mt-3'>
                                <button class='btn btn-primary register-button'>Confirm</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>"
?>

<?php
// Include the Page Layout header
include("footer.php");
?>