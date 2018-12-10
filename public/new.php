<?php
	if(!isset($_SESSION))
	 {
			 session_start();
	 }

	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}
?>

<!DOCTYPE html>
<html>

<head>
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="..\css\style.css">
	<link rel="stylesheet" href="..\css\new_Champ.css">
  <meta charset="UTF-8">
  <?php include("..\php\MySqlFunctions.php"); ?>
	<?php include('..\php\functions.php'); ?>

	<script type="text/javascript" src="js\newForm.js"></script>

</head>

<body>
<div class="content_new">
  <div class="menu_div">
		<ul>
			<a href="index.php"><li>Kezdőoldal</li></a>
			<a href="new.php"><li>Új verseny</li></a>
			<a href="#"><li>Folyamatban lévők</li></a>
		</ul>
	</div>
<!-- innenstől -->
<center>
		<div class="tab">
	  <button class="tablinks" onclick="SportSelect(event, 'fifa')">FIFA</button>
	  <button class="tablinks" onclick="SportSelect(event, 'nba')">NBA</button>
	  <button class="tablinks" onclick="SportSelect(event, 'nfl')">NFL</button>
	</div>
</center>
	<!-- Tab content -->
	<div id="fifa" class="tabcontent">
		<div id="new-form-fifa">
			<form method="POST" action="">

				<h2>FIFA</h2>

				<p class="new-form-p"> Bajnokság neve:
					<input type="text" name="ChampName">
				</p>

				<p class="new-form-p">Hány fős bajnokságot szeretnél:
					<input type="number" id="new-form-players-number" name="new-form-players-number-name" min="1" max="32" onchange="Nevkiiras()">
				</p>

				<center>
					<div id="namesInput"> Versenyzők nevei:</div>
						<div id="toggledNamesInput">
						</div>
				</center>

				<p class="new-form-p">Rájátszás:
				<input type="radio" name="playoff-radio-name" value="true" id="playoffYes" onclick="FifaPlayoffVisibility()"> Igen
				<input type="radio" name="playoff-radio-name" value="false" id="playoffNo" onclick="FifaPlayoffVisibility()" checked> Nem
				</p>
				<p class="new-form-p" id="fifa-playoff-number-p">Rájátszásba jutó csapatok száma:
					<input type="number" name="fifa-playoff-number-name" id="fifa-playoff-number" min="0">
				</p>
				<p class="new-form-p">Csoportok száma:</br></br>
					<select name="group-select-name" id="new-form-select">
				    <option value="0">Válassz</option>
						<option value="1">1</option>
				    <option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
				  </select>
				</p>
				<input type="submit" id="new-Form-Submit" name="new-Form-Submit-Name">
			</form>
		</div>
	</div>

	<div id="nba" class="tabcontent">
	  <h3>Coming soon!</h3>
	</div>

	<div id="nfl" class="tabcontent">
	  <h3>Coming soon!</h3>
	</div>

<!-- idáig!! -->


	</div>
</body>

<!-- A nevek div lenyílása -->
<script>
	$(document).ready(function(){
		$("#namesInput").click(function(){
				$("#toggledNamesInput").slideToggle("slow");
		});
	});

/* Checkbox-ra írodott de kiváltottam. később még jo lehet
	function FifaPlayoffVisibility() {
    var checkBox = document.getElementById("playoff-selector");
    var text = document.getElementById("fifa-playoff-number-p");
    if (checkBox.checked == true){
        text.style.display = "block";
    } else {
       text.style.display = "none";
    }
}
*/

function FifaPlayoffVisibility() {
	var radio = document.getElementById("playoffYes");
	var text = document.getElementById("fifa-playoff-number-p");
	if (radio.checked ){
			text.style.display = "block";
	} else {
		 text.style.display = "none";
	}
}
</script>

</html>
