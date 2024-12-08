<?php

    require_once('config.php');
    require_once '../vendor/autoload.php';
    

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $sendgrid_api_key = $_ENV['SENDGRID_API_KEY'];

    // Initialize variables and error messages
    $email = $password = $confirm_password = "";
    $email_err = $password_err = $confirm_password_err = "";
    $first_name = $last_name = $phone_number = "";
    $first_name_err = $last_name_err = $phone_number_err = "";


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        // Validate email
        if(empty(trim($_POST["email"]))){
            $email_err = "Please enter an RPI email.";
        } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
            $email_err = "Please enter a valid email address.";
        } elseif(!preg_match('/@rpi\.edu$/', trim($_POST["email"]))){
            $email_err = "Please use your RPI email address (your_email@rpi.edu).";
        } else {
            // Prepare a select statement to check if email is already taken
            $sql = "SELECT UserId FROM users WHERE email = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // Set parameters
                $param_email = trim($_POST["email"]);

                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "This email is already registered.";
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
        }  else{
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Passwords do not match.";
            }
        }

        // Validate first name
        if (empty(trim($_POST["first_name"]))) {
            $first_name_err = "Please enter your first name.";
        } else {
            $first_name = trim($_POST["first_name"]);
        }

        // Validate last name
        if (empty(trim($_POST["last_name"]))) {
            $last_name_err = "Please enter your last name.";
        } else {
            $last_name = trim($_POST["last_name"]);
        }

        // Validate phone number
        if (empty(trim($_POST["phone_number"]))) {
            $phone_number_err = "Please enter your phone number.";
        } elseif (!preg_match('/^\d{10}$/', trim($_POST["phone_number"]))) {
            $phone_number_err = "Please enter a valid 10-digit phone number.";
        } else {
            $phone_number = trim($_POST["phone_number"]);
        }
    
    
        $verification_token = bin2hex(random_bytes(32));
        
        if (empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($phone_number_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO users (first_name, last_name, phone_number, email, password, verification_token) VALUES (?, ?, ?, ?, ?, ?)";
            
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssss", $param_first_name, $param_last_name, $param_phone_number, $param_email, $param_password, $param_verification_token);
                
                $param_verification_token = $verification_token;
                
                // Set parameters
                $param_first_name = $first_name;
                $param_last_name = $last_name;
                $param_phone_number = $phone_number;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
                
                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    $verification_link = "https://firetrucks.eastus.cloudapp.azure.com/ITWS-2110-F24-FireTrucks/login/verify_email.php?token=$verification_token";

                    $email = new \SendGrid\Mail\Mail();
                    $email->setFrom("rpimarketplace18@gmail.com", "RPI Marketplace");
                    $email->setSubject("Verify Your Email - RPI Marketplace");
                    $email->addTo($param_email, $param_first_name);
                    $email->addContent(
                        "text/html",
                        "<h1>Welcome, $param_first_name!</h1>
                        <p>Thank you for signing up. Please verify your email address by clicking the link below:</p>
                        <p>Please check your junk mail and allow some time for the email to be sent.<\p>
                        <a href='$verification_link'>Verify Email</a>"
                );

                // Send email
                $sendgrid = new \SendGrid($sendgrid_api_key); // Replace with your API key
                try {
                    $response = $sendgrid->send($email);
                    if ($response->statusCode() == 202) {
                        echo "Welcome email sent to $param_email.<br>";
                    } else {
                        echo "Failed to send email. Status code: " . $response->statusCode() . "<br>";
                    }
                } catch (Exception $e) {
                    echo "Email sending error: " . $e->getMessage() . "<br>";
                }

                // Redirect to login page after sending email
                header("location: login.php");
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Create Account</title>
   <link rel="stylesheet" href="../main.css">
   <link rel="stylesheet" href="register.css">
</head>


<body>
    <!-- Website Header -->
    <div id="header">
      <img src="../images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='../index.php'">
      <div id="header-buttons">
         <button class="headerbutton" alt="Goods Page Button" onclick="window.location.href='../goods/index.html'">Goods</button>
         <button class="headerbutton" alt="Services Page Button" onclick="window.location.href='../services/index.html'">Services</button>
         <button class="headerbutton" alt="Create Listing Button" onclick="window.location.href='../listingform/index.php'">Create Listing</button>
         <button class="headerbutton" alt="Profile Page Button" onclick="window.location.href='../profile/profile.php'">Profile</button>
         <button class="headerbutton" alt="Login Button" onclick="window.location.href='..login/login.php' "> Login</button>
      </div>
      <script src="../main.js"></script>
   </div>
   <main>
   <div class="account-container">
      <h2>Create an Account</h2>
      
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="input-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
        <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
    </div>
    <div class="input-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
        <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
    </div>
    <div class="input-group">
        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
        <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
    </div>
    <div class="input-group">
        <label for="email">RPI Email</label>
        <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
    </div>
    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="input-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
    </div>
    <button type="submit" class="create-btn">Create Account</button>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</form>

  </div>
</main>
<div id="footer" class="login-footer">
      <p>2024 RPI Marketplace. All rights reserved.</p>
   </div>
</body>
</html>

