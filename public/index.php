<?php
	session_start();

	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}
/* NINCS MÉG LOGOUT, DE KÉSŐBB JOLJÖHET

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}
*/
?>
<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet">
  <link rel="stylesheet" href="..\css\style.css">
	<link rel="stylesheet" href="..\css\index.css">
  <meta charset="UTF-8">
	<?php include("..\php\MySqlFunctions.php"); ?>
	<?php include('..\php\functions.php'); ?>
</head>

<body>
<div class="content_Index">
	<div class="menu_div">
		<ul>
			<a href="#"><li>Kezdőoldal</li></a>
			<a href="new.php"><li>Új verseny</li></a>
			<a href=""><li>Folyamatban lévők</li></a>
		</ul>
	</div>
	<div id="leagueContainer">
		<?php
			indexPage();
		?>
	</div>
</div>
</body>

</html>

<script>

function leagueLink(id)
{
  setCookie("leagueID",id, 1)
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
</script>
