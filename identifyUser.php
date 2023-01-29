<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>


<div class='container p-5'>
    <div class='row'>
        <div class='col-12 col-sm-6 col-md-7 m-auto'>
            <div class='card border-0 shadow'>
                <div class='card-body' style='background-color: #DAAFF4; border-radius: 5px;'>
                    <div class='m-auto'>
                        <h2 class='loginheader text-center' style='color: #4E004A;'>Verify E-Mail</h2>
                    </div>
                    <form action="forgetpassword.php" method="post" >
                        <input type="email" name="email" id="email" class="form-control my-4 py-2" placeholder="Enter the email you registered for the account" required/>
                        <div class="text-center mt-3">
                        <button class="btn btn-primary login-button">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//Include the Page Layout footer
include("footer.php");
?>