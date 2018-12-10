<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet">
  <link rel="stylesheet" href="..\css\style.css">
  <meta charset="UTF-8">
  <?php include("..\php\MySqlFunctions.php"); ?>
</head>

<body>
  <div class="Content_Login">

  <form class="login_form"  method="post">
    <p class="login_title_p">Baki Sports</p>
    <p>Username</br>
      <input type="text" name="username">
    </p>
    <p>Password</br>
      <input type="password" name="password"><br/>
      <input type="submit" name="login_form_submit" class="login_btn" value="Login">
    </p>

  </form>

</div>
</body>

</html>
