<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>

<div class="container p-5">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-7 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body" style="background-color: #DAAFF4; border-radius: 5px;">
                    <div class="m-auto">
                        <svg class="d-block m-auto pb-2" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#4E004A" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                        <h2 class="loginheader text-center" style="color: #4E004A;">Login</h2>
                    </div>
                    <form action="checkLogin.php" method="post" >
                        <input type="email" name="email" id="email" class="form-control my-4 py-2" placeholder="Email" required/>
                        <input type="password" name="password" id="password" class="form-control my-4 py-2" placeholder="Password" required/>
                        <a href="identifyUser.php" class="forgetpassword-link pl-1">Forget password?</a>
                        <div class="text-center mt-3">
                        <button class="btn btn-primary login-button">Login</button>
                        </div>
                        <a href="register.php" class="register-link d-block text-center pt-2">Not registered yet? Sign up now!</a>
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