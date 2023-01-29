<?php
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest!<br />";
$content2 = "<li class='nav-item'>
            <a class='nav-link' href='register.php'>Sign Up</a></li>
            <li class='nav-item'>
            <a class='nav-link' href='login.php'>Login</a></li>";


if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]!</b>";
    $content2 = "<li class='nav-item'>
                 <a class='nav-link' href='viewprofile.php'>View Profile</a></li>
                 <li class='nav-item'>
                 <a class='nav-link' href='logout.php'>Logout</a></li>
                 <li class='nav-item'>
                <a class='nav-link' href='shoppingCart.php'>
                <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-bag-fill' viewBox='0 0 16 16'>
                    <path d='M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z'/>
                </svg>
                <span class='badge badge-warning' id='lblCartCount'> $_SESSION[NumCartItem]</span>
                 </span></a></li>";
}
?>
<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
<nav class="navbar navbar-expand-lg bg-custom navbar-light">
    <a href="index.php" class="navbar-brand mb-0">
    <img src="Images/logo.png" alt="Logo" class="d-inline-block align-top">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-lg bg-custom sticky-top" style="border-bottom: 1px solid #4E004A">
    <!-- Collapsible part of navbar -->
    <div class="collapse navbar-collapse bg-custom" id="collapsibleNavbar">
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="navsearch mx-auto"> 
                    <form name="frmSearch " method="get" action="search.php">
                        <div class="form-group row m-0 ">
                            <div class="col-sm-4 px-0">
                                <input class="form-control searchbar" name="keywords" id="keywords" type="search" placeholder="Search for a product here" />
                            </div>

                            <div class="col-sm-2 px-0 input-group-append">
                                <input class="form-control minbar" name="min_price" id="min_price" type="number" placeholder="Min" />
                            </div>

                            <div class="col-sm-2 px-0 input-group-append">
                                <input class="form-control maxbar" name="max_price" id="max_price" type="number" placeholder="Max" />
                            </div>
                            <div class="col-sm-2 px-0 input-group-append">
                                <button class="search-button px-3" type="submit">Search</button>
                                </button>
                            </div>
                            <div class="col-sm-2 px-0 input-group-append">
                            <input type="checkbox" id="on-offer" name="on-offer" value="1">
                            <label class="my-auto px-1 text-center" for="on-offer">Sale?</label> 
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="category.php">Product Categories</a>
            </li>
            <?php echo $content2; ?>
        </ul>
    </div>
</nav>