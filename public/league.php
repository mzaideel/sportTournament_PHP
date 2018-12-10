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
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="..\css\style.css">
  <link rel="stylesheet" href="..\css\new_Champ.css">
	<link rel="stylesheet" href="..\css\league.css">
  <meta charset="UTF-8">
	<?php include("..\php\MySqlFunctions.php"); ?>
	<?php include('..\php\functions.php'); ?>
	<?php include('..\php\sorsolasUpdate.php'); ?>
	<?php include('..\php\statUpdate.php'); ?>
</head>

<body>
<div class="content_Index">
	<div class="menu_div">
		<ul>
			<a href="index.php"><li>Kezdőoldal</li></a>
			<a href="new.php"><li>Új verseny</li></a>
			<a href=""><li>Folyamatban lévők</li></a>
		</ul>
	</div>
  <center>
  		<div class="tab">
  	  <button class="tablinks" onclick="StatSelect(event, 'bajnoksag')">Bajnokság</button>
  	  <button class="tablinks" onclick="StatSelect(event, 'sorsolas')">Sorsolás</button>
			<button class="tablinks" onclick="StatSelect(event, 'stats')">Statisztika</button>
			<button class="tablinks" onclick="StatSelect(event, 'playoff')">Rájátszás</button>
  	</div>
  </center>

  <div id="bajnoksag" class="tabcontent">
		<center><div id="bajnoksagTable">
			<table>
		    <?php
		    if(!isset($_COOKIE["leagueID"])) {
		        alert("Cookie named '" . "leagueID" . "' is not set!");
		    } else {
		        bajnoksag($_COOKIE["leagueID"]);
		    }
		    ?>
			</table>
		</div></center>
	</div>

	<div id="sorsolas" class="tabcontent">
		<center><div id="sorsolasTable">
		  	<?php
		    if(!isset($_COOKIE["leagueID"])) {
		        alert("Cookie named '" . "leagueID" . "' is not set!");
		    } else {
		        FifaSorsolasSelect($_COOKIE["leagueID"]);
		    }
		    ?>
				</div>
	</div>

	<div id="stats" class="tabcontent">
		<center><div id="statsContainerDiv">
			<?php
			if(!isset($_COOKIE["leagueID"])) {
					alert("Cookie named '" . "leagueID" . "' is not set!");
			} else {
					FifaStats($_COOKIE["leagueID"]);
			}
			?>
		</div></center>
	</div>

	<div id="playoff" class="tabcontent">
	  <h3>Coming soon!</h3>
	</div>

<script>
function StatSelect()
{
  var e = document.getElementById("sports");
  var ertek = document.getElementById("sports").value;

  if(ertek=="bajnoksag")
  {
    document.getElementById("bajnoksag").style.visibility="visible";
  }
}

//Ezt másoltam a netről!!
function StatSelect(evt, sport) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(sport).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>

</div>

		<div id="overlay">
      <div id="popup">
        <div id="close">X</div>
        <h2>Változtatás</h2>
        <p id="update_p"></p>
      </div>
    </div>

		<script>
    //ha rákattintok a TR-re akkor kijön egy popup a tr id-jával.
      $(function()
      {
        //itt hivatkozunk a TR clickre.
        $('#SorTable tr').click(function()
        {
          //Ez a popup előtűnés és eltünés
          $('#overlay').fadeIn(300);
          $('#close').click(function()
          {
            $('#overlay').fadeOut(300);
          });
          //Ez pedig a TR ID-ját kiírja egy változóba és azzal dolgozik tovább.
          elementid=$(this).attr('id');

          //Ajax hogy kiírja a PHP-t
          $(function()
          {
            $.post('../php/sorsolasUpdate.php',
            { elid: elementid}, function(response){
                $('#update_p').html(response);
            });
          });
        });
      });

			//Ez a gollövőlistát változtatja.
			$(function()
      {
        //itt hivatkozunk a TR clickre.
        $('#statTable tr').click(function()
        {
          //Ez a popup előtűnés és eltünés
          $('#overlay').fadeIn(300);
          $('#close').click(function()
          {
            $('#overlay').fadeOut(300);
          });
          //Ez pedig a TR ID-ját kiírja egy változóba és azzal dolgozik tovább.
          elementid=$(this).attr('id');

          //Ajax hogy kiírja a PHP-t
          $(function()
          {
            $.post('../php/statUpdate.php',
            { elid: elementid}, function(response){
                $('#update_p').html(response);
            });
          });
        });
      });

    </script>
</body>

</html>
