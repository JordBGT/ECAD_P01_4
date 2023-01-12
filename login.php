<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<div class="container">
    <form class="login-form mt-5 rounded" action="checkLogin.php" method="post">
        <div class="row">
            <div class="col-sm-6">
                <img class="img-fluid login-picture" src="Images/loginpicture.jpg" alt="loginpicture" style="border-radius: 5px;">
            </div>
            <div class="col-sm-6">
                <div class="form-group row p-3">
                    <div class="col-sm-12 text-center">
                        <span class="page-title">Member Login</span>
                    </div>
                </div>
                <!-- 2nd row - Entry of email address -->
                <label for="email" class="col-sm-3 offset-2 col-form-label">Email Address:</label>
                <div class="form-group row">
                    <div class="col-sm-8 offset-2">
                        <input type="email" class="form-control" name="email" id="email" placeholder="abc@email.com" required>
                    </div>
                </div>
                <!-- 3rd row - Entry of password -->
                <label for="password" class="col-sm-3 offset-2 col-form-label">Password:</label>
                <div class="form-group row">
                    <div class="col-sm-8 offset-2">
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                </div>
                <!-- 4th row - Login button -->
                <div class="form-group row">
                    <div class="col-sm-8 offset-sm-2">
                        <p><a href="forgetPassword.php">Forget Password</a></p>
                        <button type="submit" class="btn btn-primary">Login</button>
                        <p>Don't have an account?</p>
                        <a href="register.php">
                            <p>Sign Up!</p>    
                        </a>   
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
//Include the Page Layout footer
include("footer.php");
?>