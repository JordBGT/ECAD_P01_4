<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
	if (document.register.password.value != document.register.password2.value) {
        alert("Passwords not matched!");
        return false;
    }
	// To Do 2 - Check if telephone number entered correctly
	//           Singapore telephone number consists of 8 digits,
	//           start with 6, 8 or 9
    if (document.register.phone.value != "") {
        var str = document.register.phone.value;
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
    // Check that both question field and answer is either both null or both not null
    if ((document.register.question.value != "" && document.register.answer.value == "") || (document.register.question.value == "" && document.register.answer.value != "")) {
        alert("Please check that both questions and answers are entered correctly!");
        return false;
    }

    return true;  // No error found
}
</script>

<div class="container-l p-5">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-7 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body container" style="background-color: #DAAFF4; border-radius: 5px;">
                    <div class="m-auto">
                        <svg class="d-block m-auto pb-2" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#4E004A" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                        <h2 class="text-center pb-3" style="color: #4E004A;">Register</h2>
                    </div>
                    <form name="register" action="addMember.php" method="post" onsubmit="return validateForm()">
                    <div class="row">
                        <div class="col-4">
                            <h6 class="title pl-1 mb-3 text-center">
                                Account Details
                            </h6>
                            <label class="registerform-label pl-1" for="name">Name <span class="required-indicator">*</span></label>
                            <input type="name" name="name" id="name" class="form-control mb-4 py-2" placeholder="John Doe" required/>
                            <label class="registerform-label pl-1" for="email">Email <span class="required-indicator">*</span></label>
                            <input type="email" name="email" id="email" class="form-control mb-4 py-2" placeholder="johndoe@gmail.com" required/>
                            <label class="registerform-label pl-1" for="password">Password <span class="required-indicator">*</span></label>
                            <input type="password" name="password" id="password" class="form-control mb-4 py-2" placeholder="**********" required/>
                            <label class="registerform-label pl-1" for="password2">Re-type password <span class="required-indicator">*</span></label>
                            <input type="password" name="password2" id="password2" class="form-control mb-4 py-2" placeholder="**********" required/>
                            <p style="font-weight: bold;">* Compulsory fields</p>
                        </div>
                        <div class="col-4">
                            <h6 class="title pl-1 mb-3 text-center">
                                Personal Details
                            </h6>
                            <label class="registerform-label pl-1" for="birthdate">Birthdate</label>
                            <input type="date" name="birthdate" id="birthdate" class="form-control mb-4 py-2" placeholder="John Doe">
                            <label class="registerform-label pl-1" for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control mb-4 py-2" placeholder="Singapore">
                            <label class="registerform-label pl-1" for="address">Address</label>
                            <textarea type="text" name="address" id="address" class="form-control mb-4 py-2" placeholder="123 Belastier Rd"></textarea>
                            <label class="registerform-label pl-1" for="phone">Phone Number (8 digits)</label>
                            <input type="text" name="phone" id="phone" class="form-control mb-4 py-2" placeholder="81234567">
                        </div>
                        <div class="col-4">
                            <h6 class="title pl-1 mb-3 text-center">
                                Password Recovery
                            </h6>
                            <label class="registerform-label pl-1" for="question">Choose a question</label>
                            <input type="text" name="question" id="question" class="form-control mb-4 py-2" placeholder="What is my favourite food?" required>
                            <label class="registerform-label pl-1" for="answer">Enter the answer for your question</label>
                            <input type="text" name="answer" id="answer" class="form-control mb-4 py-2" placeholder="Chicken rice" required>
                        </div>
                        <div class="text-center mx-auto mt-3">
                            <button class="btn btn-primary register-button">Register</button>
                        </div>
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