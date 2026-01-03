<?php
// Database Configuration
$host = "localhost";
$username = "root";
$password = "ASHAY";
$database = "website";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Initialize variables
$errors = [];
$success = false;
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data - MATCHING YOUR HTML FORM NAMES
    $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $user_name = mysqli_real_escape_string($conn, trim($_POST['username']));      // HTML: name="username"
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['phone']));       // HTML: name="phone"
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    
    // Validation
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($user_name)) {
        $errors[] = "Username is required";
    }
    
    if (empty($phone_number)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    // Check if username exists - USING CORRECT TABLE AND COLUMN
    if (empty($errors)) {
        $check_user = "SELECT user_name FROM user_credentials WHERE user_name = '$user_name'";
        $result = $conn->query($check_user);
        if ($result->num_rows > 0) {
            $errors[] = "Username already exists";
        }
    }
    
    // Check if email exists - USING CORRECT TABLE
    if (empty($errors)) {
        $check_email = "SELECT email FROM user_credentials WHERE email = '$email'";
        $result = $conn->query($check_email);
        if ($result->num_rows > 0) {
            $errors[] = "Email already registered";
        }
    }
    
    // Check if phone exists - USING CORRECT TABLE
    if (empty($errors)) {
        $check_phone = "SELECT phone_number FROM user_credentials WHERE phone_number = '$phone_number'";
        $result = $conn->query($check_phone);
        if ($result->num_rows > 0) {
            $errors[] = "Phone number already registered";
        }
    }
    
    // If no errors, insert user
    if (empty($errors)) {
        
        // Insert query - MATCHING YOUR DATABASE COLUMNS
        $sql = "INSERT INTO user_credentials (first_name, last_name, user_name, phone_number, email, password) 
                VALUES ('$first_name', '$last_name', '$user_name', '$phone_number', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            $success = true;
            $message = "Registration successful!";
        } else {
            $success = false;
            $message = "Error: " . $conn->error;
        }
    } else {
        $success = false;
    }
    
} else {
    // If not POST request, redirect to register page
    header("Location: register.html");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Result - NeonLearn</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a3e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            max-width: 450px;
            width: 90%;
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        
        .success .icon { color: #00ff88; }
        .error .icon { color: #ff4757; }
        
        h1 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 28px;
        }
        
        .success h1 { color: #00ff88; }
        .error h1 { color: #ff4757; }
        
        p {
            color: #ccc;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .error-list {
            list-style: none;
            margin: 20px 0;
            text-align: left;
        }
        
        .error-list li {
            background: rgba(255, 71, 87, 0.2);
            color: #ff6b7a;
            padding: 12px 15px;
            margin: 8px 0;
            border-radius: 8px;
            border-left: 4px solid #ff4757;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 40px;
            margin: 10px 5px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        
        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <?php if ($success): ?>
        <div class="container success">
            <div class="icon">✓</div>
            <h1>Success!</h1>
            <p><?php echo $message; ?></p>
            <p>You can now login to your account.</p>
            <a href="login.html" class="btn btn-primary">Login Now</a>
            <a href="index.html" class="btn btn-secondary">Go Home</a>
        </div>
    <?php else: ?>
        <div class="container error">
            <div class="icon">✗</div>
            <h1>Registration Failed</h1>
            <?php if (!empty($errors)): ?>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p><?php echo $message ?? 'An error occurred'; ?></p>
            <?php endif; ?>
            <a href="register.html" class="btn btn-primary">Try Again</a>
            <a href="index.html" class="btn btn-secondary">Go Home</a>
        </div>
    <?php endif; ?>
</body>
</html>