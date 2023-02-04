<?php
# Password generating system
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
$password = substr( str_shuffle( $chars ), 0, 8 );

# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$hostname_err = $url_err = $key_err = "";
$hostname = $url = $key = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  # Validate hostname
  if (empty(trim($_POST["hostname"]))) {
    $hostname_err = "Please enter a hostname.";
  } else {
    $hostname = trim($_POST["hostname"]);
    if (!ctype_alnum(str_replace(array(), "", $hostname))) {
      $hostname_err = "Hostname can not use some of those characters";
    }
  }

  # Validate url 
  if (empty(trim($_POST["url"]))) {
    $url_err = "Please enter an url address";
  } else {
    $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    if (!filter_var($url, FILTER_SANITIZE_URL )) {
      $email_err = "Please enter a valid url address.";
    }
  }

  # Validate password
  if (empty(trim(sha1($password)))) {
    $key_err = "Please enter a key.";
  } else {
    $key = trim(sha1($password));
  }

  # Check input errors before inserting data into database
  if (empty($username_err) && empty($email_err) && empty($key_err)) {
    # Prepare an insert statement
    $sql = "INSERT INTO badprtbv_blocker(hostname, url, normal_key) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind varibales to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_hostname, $param_url, $param_key);

      # Set parameters
      $param_hostname = $hostname;
      $param_url = $url;
      $param_key = $key;

      # Execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
           # echo "<p>" . "yo" . "</p>";  Incase I need to check if its working.
         http_response_code(202); # If there is no error, 202 response code
        exit;
      } else {
        http_response_code(400); # If there is an error, 400 response code
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}