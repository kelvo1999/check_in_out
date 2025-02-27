<?php
// Start session
session_start();

// Include database config
require_once "../includes/config.php";

// Define variables and initialize with empty values
$name = $student_id = $class = $parent_phone = "";
$name_err = $student_id_err = $class_err = $parent_phone_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the student's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate student ID (must be unique)
    if (empty(trim($_POST["student_id"]))) {
        $student_id_err = "Please enter a student ID.";
    } else {
        // Check if student ID already exists
        $sql = "SELECT id FROM students WHERE student_id = ?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_student_id);
            $param_student_id = trim($_POST["student_id"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $student_id_err = "This student ID is already registered.";
                } else {
                    $student_id = trim($_POST["student_id"]);
                }
            } else {
                echo "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate class
    if (empty(trim($_POST["class"]))) {
        $class_err = "Please enter the class.";
    } else {
        $class = trim($_POST["class"]);
    }

    // Validate parent's phone number
    if (empty(trim($_POST["parent_phone"]))) {
        $parent_phone_err = "Please enter the parent's phone number.";
    } elseif (!preg_match('/^\d{10,15}$/', trim($_POST["parent_phone"]))) {
        $parent_phone_err = "Invalid phone number format.";
    } else {
        $parent_phone = trim($_POST["parent_phone"]);
    }

    // If no errors, insert into the database
    if (empty($name_err) && empty($student_id_err) && empty($class_err) && empty($parent_phone_err)) {
        $sql = "INSERT INTO students (name, student_id, class, parent_phone) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_student_id, $param_class, $param_parent_phone);

            // Set parameters
            $param_name = $name;
            $param_student_id = $student_id;
            $param_class = $class;
            $param_parent_phone = $parent_phone;

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to student list page
                header("location: student_list.php");
                exit();
            } else {
                echo "Something went wrong. Please try again.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Close database connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            font-size: 14px;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #333;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Register Student</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <div class="error"><?php echo $name_err; ?></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" class="form-control <?php echo (!empty($student_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $student_id; ?>">
            <div class="error"><?php echo $student_id_err; ?></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Class</label>
            <input type="text" name="class" class="form-control <?php echo (!empty($class_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $class; ?>">
            <div class="error"><?php echo $class_err; ?></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Parent's Phone</label>
            <input type="text" name="parent_phone" class="form-control <?php echo (!empty($parent_phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $parent_phone; ?>">
            <div class="error"><?php echo $parent_phone_err; ?></div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>

<footer>
    <p>Created by Kelvin &copy; <?php echo date("Y"); ?></p>
</footer>

</body>
</html>
