<?php
// Include config file
require_once "../includes/config.php";
 
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT Id FROM users WHERE Username = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Processing form data when form is submitted

 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT Id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username,email, password) VALUES (?,?, ?)";
         
        if($stmt = mysqli_prepare($connection, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username,$param_email, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($connection);
}
?>


 <html>
<head>
<title>Register Users</title>
<link rel="stylesheet" href="styles.css" >
<link rel="stylesheet" type="text/css" href="style.css">

 <style>
    /* Style the submit button with a specific background color etc */

    .login-box{
width: 300px;
position: absolute;
top:0%;
left: 50%;
transform: translate(-50%,20%);
color: black;
/* display: ; */

}
.login-box #hl{

	float: left;
	font-size: 40px;
	border-bottom: 6px solid #4caf50;
	margin-bottom: 50px;
	padding: 13px 0;

}

.textbox{
	width: 100%;
	overflow: hidden;
	font-size: 20px;
	padding:8px 0;
	margin: 8px 0;
	border-bottom:1px solid; 
}

.textbox i{
	width: 26px;
	float: left;text-align: center;
}

.textbox input{
	width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
}
.btn{
	width: 100%;
	background: none;
	border: 2px solid black;
	color: red;
	padding: 5px;
	font-size: 18px;
	cursor: pointer;
	margin: 12px;
}
input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    }
    /* When moving the mouse over the submit button, add a darker green color */
    input[type=submit]:hover {
    background-color: #45a049;
    }
    /* Style the submit button with a specific background color etc */
    input[type=reset] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    }
    /* When moving the mouse over the submit button, add a darker green color */
    input[type=reset]:hover {
    background-color: #45a049;
    }
    /* Add a background color and some padding around the form */
    .container {
    /* border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
    height:550px;
    width:550px;
    position: absolute;
    top:0%;
    left: 50%;
    transform: translate(-50%,20%);
    color: black; */
            width: 350px;
            margin: auto;
            margin-top: 100px;
            padding: 20px;
            background: #f8f8f8;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
    
    }/* CSS Document */
    
    </style>


</style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
<div class="container">
<h2 style="color: black;">Sign Up</h2>
        <p style="color: black;">Please fill this form to create an account.</p>
            <div class="textbox <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="username" class="form-control" placeholder="Enter Names" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div> 
            <br>   
            <div class="textbox <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" placeholder="email" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div> 
            <br>   
            <div class="textbox <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <br>
            <div class="textbox <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm it</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="confirm password" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            
            <div >
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>

            <p style="color: black;">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
</div> 
</body>
</html>