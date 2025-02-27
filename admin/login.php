<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: dashboard.php");
  exit;
}

// Include config file
require_once "../includes/config.php";

// Define variables and initialize with empty values
$input = $password = "";
$input_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate input
    if(empty(trim($_POST["input"]))){
        $input_err = "Please enter your username or email.";
    } else {
        $input = trim($_POST["input"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // If no errors, check credentials
    if(empty($input_err) && empty($password_err)){
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_input, $param_input);
            $param_input = $input;
             
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Start session
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role;
                            
                            // Redirect to dashboard
                            header("location: dashboard.php");
                            exit;
                        } else {
                            $password_err = "Invalid password.";
                        }
                    }
                } else {
                    $input_err = "No account found with that username or email.";
                }
            } else {
                echo "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Center login form */
        .login-box {
            width: 350px;
            margin: auto;
            margin-top: 100px;
            padding: 20px;
            background: #f8f8f8;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        /* Style input fields */
        .textbox {
            margin-bottom: 15px;
        }

        .textbox input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Style buttons */
        .btn {
            width: 100%;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn:hover {
            background: #45a049;
        }

        /* Footer at bottom */
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background: #222;
            color: white;
        }

    </style>
</head>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="login-box">
        <h2>Login</h2>

        <div class="textbox <?php echo (!empty($input_err)) ? 'has-error' : ''; ?>">
            <input type="text" name="input" placeholder="Username or Email" value="<?php echo $input; ?>">
            <span class="help-block"><?php echo $input_err; ?></span>
        </div>

        <div class="textbox <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <input type="password" name="password" placeholder="Password">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>

        <input type="submit" class="btn" value="Login">
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
        <p>Forgot password? <a href="#">Reset</a></p>
    </div>
</form>

<footer>
    <p>Created by Kelvin &copy; <?php echo date("Y"); ?></p>
</footer>

</body>
</html>
