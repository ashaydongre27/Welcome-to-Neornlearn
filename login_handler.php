<?php
// Start session
session_start();

// Include database configuration
require_once 'config.php';

// Initialize variables
$error = "";
$success = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    
    // Validation
    if (empty($username)) {
        $error = "Username is required";
    } elseif (empty($password)) {
        $error = "Password is required";
    } else {
        
        // Check if user exists
        $sql = "SELECT id, username, password, first_name, last_name, email FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                
                // Password correct - Create session
                // $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                // $_SESSION['first_name'] = $user['first_name'];
                // $_SESSION['last_name'] = $user['last_name'];
                // $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                $success = true;
                
                // Redirect to dashboard
                header("refresh:2;url=dashboard.php");
                
            } else {
                $error = "Invalid password";
            }
            
        } else {
            $error = "Username not found";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Result - NeonLearn</title>
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
            
            .loader {
                border: 4px solid rgba(255,255,255,0.1);
                border-top: 4px solid #00ff88;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                animation: spin 1s linear infinite;
                margin: 20px auto;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <?php if ($success): ?>
            <div class="container success">
                <div class="icon">✓</div>
                <h1>Welcome Back!</h1>
                <p>Hello, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</p>
                <p>Redirecting to dashboard...</p>
                <div class="loader"></div>
                <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
            </div>
        <?php else: ?>
            <div class="container error">
                <div class="icon">✗</div>
                <h1>Login Failed</h1>
                <p><?php echo htmlspecialchars($error); ?></p>
                <a href="login.html" class="btn btn-primary">Try Again</a>
                <a href="register.html" class="btn btn-secondary">Register</a>
            </div>
        <?php endif; ?>
    </body>
</html>