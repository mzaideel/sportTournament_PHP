<?php

if(isset($_POST['login_form_submit']))
{
  Login();
}


function Login()
{
  session_start();
  // variable declaration
  $username = "";
  $email    = "";
  $errors = array();
  $_SESSION['success'] = "";

  // connect to database
  $db = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');

  // LOGIN USER
  if (isset($_POST['login_form_submit']))
  {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username))
    {
      array_push($errors, "Username is required");
      echo "<script>alert('Hiányzik a felhasználónév!')</script>";
      return false;
    }
    if (empty($password))
    {
      array_push($errors, "Password is required");
      echo "<script>alert('Hiányzik a jelszó!')</script>";
      return false;
    }

    if (count($errors) == 0)
     {
      //Itt kéri le a formba beírt bejelentkezési adatokat, és vizsgálja hogy helyes-e.
      $password = md5($password);
      $query = "SELECT * FROM login WHERE uname='$username' AND pwd='$password'";
      $results = mysqli_query($db, $query);

      if (mysqli_num_rows($results) == 1)
      {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
      }else
      {
        array_push($errors, "Wrong username/password combination");
        echo "<script>alert('valami félresiklott!')</script>";
      }
    }
  }
}

?>
