<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../index.html");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your RPI email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL) || strpos(trim($_POST["email"]), '@rpi.edu') === false) {
        $email_err = "Please enter a valid RPI email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT UserId, email, password, email_verified FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($db, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $email_verified);
                    if (mysqli_stmt_fetch($stmt)) {
                        if ($email_verified == 0) {
                            $login_err = "Please verify your email address before logging in.";
                        } elseif (password_verify($password, $hashed_password)) {
                              // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            header("location: ../index.html");
                            exit;
                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($db);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <Title>Login Page</Title>
   <link rel="stylesheet" href="../main.css">
   <link rel="stylesheet" href="login.css">

</head>

<body> 
   <!-- Website Header -->
   <div id="header">
      <img src="../images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='../index.html'">
      <div id="header-buttons">
         <button class="headerbutton" alt="Goods Page Button" onclick="window.location.href='../goods/goods.html'">Goods Page</button>
         <button class="headerbutton" alt="Services Page Button" onclick="window.location.href='../services/services.html'">Services Page</button>
         <div id="login-button">
            <!-- Button will be created here -->
         </div>
      </div>
      <script src="../main.js"></script>
   </div>
   <!-- Login -->
   <div class="login-container">
      <h2>RPI Login</h2>
      <?php 
        if(!empty($login_err)){
            echo '<div class="alert">' . $login_err . '</div>';
        }        
        ?>
       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="input-group">
              <label for="email">RPI Email</label>
              <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
          </div>
          <div class="input-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
          </div>
          <button type="submit" class="login-btn">Login</button>

          <p>Don't have an account? <a href="create_account.php">Sign up now</a>.</p>
      </form>
  </div>
</body>
</html>