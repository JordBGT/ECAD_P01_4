<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<script type="text/javascript">
function validateForm()
{
	//           Check if telephone number entered correctly
	//           Singapore telephone number consists of 8 digits,
	//           start with 6, 8 or 9
    if (document.editprofile.phone.value != "") {
        var str = document.editprofile.phone.value;
        if (str.length != 8) {
            alert("Please enter an 8-digit phone number.");
            return false; // cancel submission
        }
        else if (str.substr(0,1) != "6" &&
        str.substr(0,1) != "8" &&
        str.substr(0,1) != "9") {
            alert("Phone number in Singapore should start with 6, 8 or 9.");
            return false; //cancel submission
        }
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
<div class='container-l p-5'>
    <div class='row'>
        <div class='col-12 col-sm-6 col-md-7 m-auto'>
            <div class='card border-0 shadow'>
                <div class='card-body container' style='background-color: #DAAFF4; border-radius: 5px;'>
                    <div class='m-auto'>
                        <svg class='d-block m-auto pb-2' xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='#4E004A' class='bi bi-person-circle' viewBox='0 0 16 16'>
                            <path d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z' />
                            <path fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z' />
                        </svg>
                        <h2 class='text-center pb-3' style='color: #4E004A;'>Edit Profile</h2>
                    </div>
                    <form name='editprofile' action='validateEditProfile.php' method='post' onsubmit='return validateForm()'>
                        <div class='row'>
                            <div class='col-4'>
                                <h6 class='title pl-1 mb-3 text-center'>
                                    Account Details
                                </h6>
                                <label class='registerform-label pl-1' for='name'>Name</span></label>
                                <input type='name' name='name' id='name' class='form-control mb-4 py-2' placeholder='$shopperName' value='$shopperName' />
                                <label class='registerform-label pl-1' for='email'>Email</label>
                                <input type='email' name='email' id='email' class='form-control mb-4 py-2' placeholder='$email' value='$email'/>
                            </div>
                            <div class='col-4'>
                                <h6 class='title pl-1 mb-3 text-center'>
                                    Personal Details
                                </h6>
                                <label class='registerform-label pl-1' for='birthdate'>Birthdate (dd/mm/yyyy)</label>
                                <input type='text' name='birthdate' id='birthdate' class='form-control mb-4 py-2' placeholder='$birthDate' value='$birthDate' onfocus='(this.type='date')' onblur='(this.type='text')'>
                                <label class='registerform-label pl-1' for='country'>Country</label>
                                <input type='text' name='country' id='country' class='form-control mb-4 py-2' placeholder='$country' value='$country'>
                                <label class='registerform-label pl-1' for='address'>Address</label>
                                <input type='text' name='address' id='address' class='form-control mb-4 py-2' placeholder='$address' value='$address'></input>
                                <label class='registerform-label pl-1' for='phone'>Phone Number (8 digits)</label>
                                <input type='text' name='phone' id='phone' class='form-control mb-4 py-2' placeholder='$phone' value='$phone'>
                            </div>
                            <div class='col-4'>
                                <h6 class='title pl-1 mb-3 text-center'>
                                    Password Recovery
                                </h6>
                                <label class='registerform-label pl-1' for='question'>Choose a question</label>
                                <input type='text' name='question' id='question' class='form-control mb-4 py-2' placeholder='$question' readonly>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='text-center mx-auto mt-3'>
                                <button class='btn btn-primary register-button'>Confirm Edit</button>
                            </div>                           
                        </div>
                    </form>
                    <div class='row'>
                        <div class='col-6'>
                            <div class='text-left ml-auto mt-3'>
                                <a href='editPassword.php'>
                                    <button class='btn btn-primary register-button'>Change Password</button>
                                </a>
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class='text-right mr-auto mt-3'>
                                <a href='editPasswordRecovery.php'>
                                    <button class='btn btn-primary register-button'>Edit Password Recovery Details</button>
                                </a>
                            </div>
                        </div>
                    </div>
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