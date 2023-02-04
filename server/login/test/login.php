<?php
# Password generating system

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass_generated = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass_generated[] = $alphabet[$n];
    }
    return implode($pass_generated); //turn the array into a string
}

echo randomPassword();


# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$hostname_err = $url_err = $key_err = "";
$hostname = $url = $key = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["hostname"]))) {
    $hostname_err = "Please enter a hostname.";
    #    Debugging    echo "Please enter a hostname.";
  } else {
    $hostname = trim($_POST["hostname"]);
    
  }

  if (empty(trim($_POST["url"]))) {
    $url_err = "Please enter your url.";
    #    Debugging    echo "Please enter your url.";
  } else {
    $url = trim($_POST["url"]);
    
  }
  if (empty(trim($_POST["key"]))) {
    $key_err = "Please enter a key.";
    #    Debugging    echo "Please enter a key.";
  } else {
    $key = trim($_POST["key"]);
    
  }

  # Validate credentials 
  if (empty($hostname_err) && empty($url_err) && empty($key_err)) {
      
    # Prepare a select statement
    $sql = "SELECT hostname, url, normal_key FROM badprtbv_blocker WHERE normal_key = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind variables to the statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_key);
      

      # Set parameters
      $param_key = $key;

      # Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        # Store result
        mysqli_stmt_store_result($stmt);

        # Check if key exists, If yes then verify url and hostname
        if (mysqli_stmt_num_rows($stmt) == 1) {
          # Bind values in result to variables
          mysqli_stmt_bind_result($stmt, $hostname_sql, $url_sql, $key_sql);

          if (mysqli_stmt_fetch($stmt)) {
            # Check if hostname and url are correct
            if ($hostname_sql = $hostname && $url = $url_sql  && $key = $key_sql) {
                http_response_code(202); # If there is no error, 202 response code
                exit;
            } else {
              # If any are incorrect show an error message
              $hostname_err = "The hostname or url you entered is incorrect.";
              #    Debugging    echo "The hostname or url you entered is incorrect.";
              http_response_code(400); # If there is an error, 400 response code
            }
          }
        } else {
          # If key doesn't exists show an error message
          $key_err = "Invalid key.";
           #    Debugging    echo "Invalid key.";
          http_response_code(400); # If there is an error, 400 response code
        }
      } else {
        http_response_code(400); # If there is an error, 400 response code
        exit;
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}
?>