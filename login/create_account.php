<?php

    require_once('config.php');

    // Initialize variables and error messages
    $email = $password = $confirm_password = "";
    $email_err = $password_err = $confirm_password_err = "";

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
            $sql = "SELECT id FROM users WHERE email = ?";
            
            if($stmt = mysqli_prepare($db, $sql)){
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
        
        // Check for input errors before inserting into the database
        if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($db, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
                
                // Set parameters
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    header("location: login.php");
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        // Close database connection
        mysqli_close($db);
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
   <div class="account-container">
      <h2>Create an Account</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
          </div>
          <button type="submit" class="create-btn">Create Account</button>
          <p>Already have an account? <a href="login.php">Login here</a>.</p>
      </form>
  </div>
</body>
</html>
