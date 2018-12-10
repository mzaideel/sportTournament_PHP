<?php
if(isset($_POST["elid"]))
{
  $id=$_POST['elid'];
  if(is_numeric($id))
  {

    $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
    // Check connection
    if (!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    $selectStatsUnit="SELECT * from stats where id=$id";
    $result = mysqli_query($conn, $selectStatsUnit);

    if (mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_array($result);
        echo "<form action='' method='POST'><table><tr><td>".$row["jatekos"]."</td>
        <td> <input type='number' name='statValue' min=0 max=999 value=".$row["stat"]."></td></tr></table>
        </br><input type='hidden' name='rowID' value=".$row["id"]."><input class='update-submit-btn' type='submit' name='statUpdateSend'></form>";
    }
    else
    {
        echo "Nincs ilyen a statisztikák között!";
        mysqli_close($conn);
        return 0;
    }

    mysqli_close($conn);
  }
  else{
    if($id=="addGollovo")
    {
      echo "<form action='' method='POST'><table><tr><td>Játékos neve:</td><td><input type='text' name='jatekosNeve'></td></tr>
      <tr><td>Gólok száma:</td><td> <input type='number' name='statValue' min=0 max=999></td></tr></table>
      </br><input type='hidden' name='rowID' value='gol'><input class='update-submit-btn' type='submit' name='statNewGollovo'></form>";
    }
    else if($id=="addYellow")
    {
      echo "<form action='' method='POST'><table><tr><td>Játékos neve:</td><td><input type='text' name='jatekosNeve'></td></tr>
      <tr><td>Sárgalapok száma:</td><td> <input type='number' name='statValue' min=0 max=999></td></tr></table>
      </br><input type='hidden' name='rowID' value='yellow'><input class='update-submit-btn' type='submit' name='statNewYellow'></form>";
    }
    else if($id=="addRed")
    {
      echo "<form action='' method='POST'><table><tr><td>Játékos neve:</td><td><input type='text' name='jatekosNeve'></td></tr>
      <tr><td>Piroslapok száma:</td><td> <input type='number' name='statValue' min=0 max=999></td></tr></table>
      </br><input type='hidden' name='rowID' value='red'><input class='update-submit-btn' type='submit' name='statNewRed'></form>";
    }
    else echo "kapd be!";
  }
}

if(isset($_POST["statUpdateSend"]))
{
  if(empty($_POST['rowID']))
  {
    echo "<script type='text/javascript'>alert('Az egyik mező üres!');</script>";
    return 0;
  }

  $id=$_POST["rowID"];
  $stat=$_POST["statValue"];

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }

  $updateStat="UPDATE stats set stat='$stat' where id=$id";

  if (!mysqli_query($conn, $updateStat))
  {
      echo "<script type='text/javascript'>alert('Hiba csúszott a gépezetbe!');</script>";
      mysqli_close($conn);
      return 0;
  }

  mysqli_close($conn);

}

if(isset($_POST["statNewGollovo"]))
{
  $jatekos=$_POST["jatekosNeve"];
  $stat=$_POST["statValue"];
  $type=$_POST["rowID"];
  $bajnoksagID=$_COOKIE["leagueID"];

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }

  $addNewGollovo = "INSERT INTO stats (bajnoksag_id, stat_type, jatekos, stat)
  VALUES ($bajnoksagID,'$type', '$jatekos', '$stat')";

  if (!mysqli_query($conn, $addNewGollovo))
  {
    echo "Error: " . $addNewGollovo . "<br>" . mysqli_error($conn);
    mysqli_close($conn);
    return 0;
  }
  mysqli_close($conn);
}

if(isset($_POST["statNewYellow"]))
{
  $jatekos=$_POST["jatekosNeve"];
  $stat=$_POST["statValue"];
  $type=$_POST["rowID"];
  $bajnoksagID=$_COOKIE["leagueID"];

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }

  $addNewYellow = "INSERT INTO stats (bajnoksag_id, stat_type, jatekos, stat)
  VALUES ($bajnoksagID,'$type', '$jatekos', '$stat')";

  if (!mysqli_query($conn, $addNewYellow))
  {
    echo "Error: " . $addNewYellow . "<br>" . mysqli_error($conn);
    mysqli_close($conn);
    return 0;
  }
  mysqli_close($conn);
}

if(isset($_POST["statNewRed"]))
{
  $jatekos=$_POST["jatekosNeve"];
  $stat=$_POST["statValue"];
  $type=$_POST["rowID"];
  $bajnoksagID=$_COOKIE["leagueID"];

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }

  $addNewRed = "INSERT INTO stats (bajnoksag_id, stat_type, jatekos, stat)
  VALUES ($bajnoksagID,'$type', '$jatekos', '$stat')";

  if (!mysqli_query($conn, $addNewRed))
  {
    echo "Error: " . $addNewRed . "<br>" . mysqli_error($conn);
    mysqli_close($conn);
    return 0;
  }
  mysqli_close($conn);
}
?>
