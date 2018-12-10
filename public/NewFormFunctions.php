<?php

//$select=$_POST['selected'];
$eid=$_POST['elid'];
$eval=$_POST['evalue'];

/*for($i=0; $i<$eval; $i++)
echo '<p id="name-p">'.$i.'.játékos neve: <input type="text" id="name-textbar'.$i.'"> csapata: <input type="text" id="team-textbar'.$i.'"> </p>';*/

switch ($eid) {
  case 'new-form-players-number':

    echo "<table>";

      for($i=0; $i<$eval; $i++)
      {
        echo '<tr id="name-tr">';
        echo '<td>'. ($i+1) . '. játékos neve:<td>';
        echo '<td><input type="text" name="name-textbar-name'.$i.'" id="name-textbar'.$i.'"><td>';
        echo '<td>csapata:<td>';
        echo '<input type="text" name="team-textbar-name'.$i.'" id="team-textbar'.$i.'">';
        echo '</tr>';
      }
      echo '</table>';
    break;

  case 'ide-jön-a-következő-esemény-az-ajaxtól':

    break;

  default:
    // code...
    break;
}


?>
