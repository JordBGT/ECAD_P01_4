<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<script type="text/javascript">
function validateForm()
{

    // Check rating min = 5 max = 0
    if (document.feedbackform.question.rank < 0 || document.feedbackform.question.rank > 5) {
        alert("Please enter a number between 0-5 for ranking!");
        return false;
    }

    return true;  // No error found
}
</script>
<div class="container p-5">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-7 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body" style="background-color: #DAAFF4; border-radius: 5px;">
                    <div class="m-auto">
                        <h2 class="loginheader text-center" style="color: #4E004A;">Submit Feedback</h2>
                    </div>
                    <form name='feedbackform' action="addfeedback.php" method="post" onsubmit="return validateForm()" >
                        <input type="text" name="subject" id="subject" class="form-control my-4 py-2" placeholder="Feedback Subject" required/>
                        <input type="text" name="content" id="content" class="form-control my-4 py-2" placeholder="Feedback Content" required/>
                        <input type="number" name="rank" id="rank" class="form-control my-4 py-2" min="0" max="5" placeholder="Ranking" required/>
                        <div class="text-center mt-3">
                        <button class="btn btn-primary login-button">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="width:60%; margin:auto;">
    <div class="row" style="padding:5px"> <!-- Start of header row -->
        <div class="col-12 text-center">
            <span class="page-title" style="color: #4E004A;">What They Say About Us</span>
        </div>
    </div>
    <div class="row">
        <div class='col-12 productitem-container'>
            <div class="row feedback-content">
<?php 
include_once("mysql_conn.php");
$qry = "SELECT * FROM Feedback";
$result = $conn->query($qry);
while ($row = $result->fetch_array()) {
    $subject = $row["Subject"];
    $content = $row["Content"];
    $rank = $row["Rank"];
    $datetime = $row["DateTimeCreated"];

    echo "<div class='col-8'>
    <p class='feedback-subject text-left my-2 font-weight-bold'>$subject</p></div>
    <div class='col-4'>
        <p class='text-right feedback-rating my-2 font-weight-bold' style='font-size: large; color: #4E004A;'>Rating: $rank/5</p>
    </div>
    <div class='col-12'>
    <p class='text-left'>$content</p>
    </div>
    <div class='col-12'>
    <p class='text-right' style='opacity:60%'><span class='font-weight-bold'>Received on: </span>$datetime</p>
    </div>
    <hr class='col-11 mx-auto'>";
}
?>
            </div>
        </div>
    </div>
</div>
<?php
//Include the Page Layout footer
include("footer.php");
?>