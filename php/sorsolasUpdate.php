<?php

if(isset($_POST["elid"]))
{
  $id=$_POST['elid'];

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  // Check connection
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }
  $selectSorsolasUnit="SELECT * from sorsolas where id=$id";
  $result = mysqli_query($conn, $selectSorsolasUnit);

  if (mysqli_num_rows($result) > 0)
  {
      $row = mysqli_fetch_array($result);
      echo "<form action='' method='POST'><table><tr><td>".$row["home_team"]."</td>
      <td> <input type='number' name='home' min=0 max=99> - <input type='number' name='away' min=0 max=99> </td><td>".$row["away_team"]."</td></tr></table>
      </br><input type='hidden' name='rowID' value=".$row["id"]."><input class='update-submit-btn' type='submit' name='updateSend'></form>";
  }
  else
  {
      echo "Nincs ilyen a sorsolások között!";
      mysqli_close($conn);
      return 0;
  }

  mysqli_close($conn);
}

if(isset($_POST["updateSend"]))
{
  if(empty($_POST['rowID']))
  {
    echo "<script type='text/javascript'>alert('Az egyik mező üres!');</script>";
    return 0;
  }

  $id=$_POST['rowID'];
  $home=$_POST['home'];
  $away=$_POST['away'];
  $homeGoalDif=$home-$away;
  $awayGoalDif=$away-$home;

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }

  $selectCsapatok="SELECT home_team, away_team, bajnoksag_id from sorsolas where id=$id";
  $result = mysqli_query($conn, $selectCsapatok);
  $csapatok = mysqli_fetch_array($result);
  $homeTeam=$csapatok['home_team'];
  $awayTeam=$csapatok['away_team'];
  $bajnoksagID=$csapatok['bajnoksag_id'];

  if($home > $away)
  {
    $updateSorsolas="UPDATE sorsolas set home_goal=$home, away_goal=$away, lejatszott=true where id=$id";

    mysqli_autocommit($conn,FALSE);
    if (mysqli_query($conn, $updateSorsolas))
    {
        mysqli_commit($conn);
        csapatokUpdate($homeTeam, $awayTeam, $bajnoksagID);
    }
    else
    {
        echo "<script type='text/javascript'>alert('Hiba csúszott a gépezetbe!');</script>";
    }

    mysqli_close($conn);
  }
  else if($home < $away)
  {
    $updateSorsolas="UPDATE sorsolas set home_goal=$home, away_goal=$away, lejatszott=true where id=$id";

    mysqli_autocommit($conn,FALSE);
    if (mysqli_query($conn, $updateSorsolas))
    {
        mysqli_commit($conn);
        csapatokUpdate($homeTeam, $awayTeam, $bajnoksagID);
    }
    else
    {
        echo "<script type='text/javascript'>alert('Hiba csúszott a gépezetbe!');</script>";
    }

    mysqli_close($conn);
  }
  else
  {
    $updateSorsolas="UPDATE sorsolas set home_goal=$home, away_goal=$away, lejatszott=true where id=$id";

  mysqli_autocommit($conn,FALSE);
  if (mysqli_query($conn, $updateSorsolas) && mysqli_query($conn, $updateHome) && mysqli_query($conn, $updateAway))
  {
      mysqli_commit($conn);
      csapatokUpdate($homeTeam, $awayTeam, $bajnoksagID);
  }
  else
  {
      echo "<script type='text/javascript'>alert('Hiba csúszott a gépezetbe!');</script>";
  }

  mysqli_close($conn);
  }

}

function csapatokUpdate($home, $away, $bajnoksagID)
{
  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  // Check connection
  if (!$conn)
  {
      die("Connection failed: " . mysqli_connect_error());
  }

  $selectHomeSorsolas1="SELECT * from sorsolas where bajnoksag_id=$bajnoksagID AND home_team='$home' AND lejatszott=true";
  $result = mysqli_query($conn, $selectHomeSorsolas1);

  $rugott=0;
  $kapott=0;
  $w=0;
  $d=0;
  $l=0;
  $pts=0;

  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result))
      {
        $rugott=$rugott+$row["home_goal"];
        $kapott=$kapott+$row["away_goal"];

        if($row["home_goal"]>$row["away_goal"])
        {
          $w++;
          $pts+=3;
        }
        else if($row["home_goal"]<$row["away_goal"])
        {
          $l++;
        }
        else
        {
          $d++;
          $pts+=1;
        }
      }
  }


  $selectHomeSorsolas2="SELECT * from sorsolas where bajnoksag_id=$bajnoksagID AND away_team='$home' AND lejatszott=true";
  $result = mysqli_query($conn, $selectHomeSorsolas2);

  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result))
      {
        $rugott=$rugott+$row["away_goal"];
        $kapott=$kapott+$row["home_goal"];

        if($row["away_goal"]<$row["home_goal"])
        {
          $l++;

        }
        else if($row["away_goal"]>$row["home_goal"])
        {
          $w++;
          $pts+=3;
        }
        else
        {
          $d++;
          $pts+=1;
        }
      }
  }

  $golkul=$rugott-$kapott;
  $updateHome="UPDATE csapatok set win=$w, draw=$d, lose=$l, rugott_gol=$rugott, kapott_gol=$kapott, goal_dif=$golkul, points=$pts
   where bajnoksag_id=$bajnoksagID and csapat_neve='$home'";

  if (!mysqli_query($conn, $updateHome))
  {
      echo "<script type='text/javascript'>alert('elején van hiba!');</script>";
      mysqli_close($conn);
      return 0;
  }

  //Vendég kezdete
  $selectAwaySorsolas1="SELECT * from sorsolas where bajnoksag_id=$bajnoksagID AND home_team='$away' AND lejatszott=true";
  $result = mysqli_query($conn, $selectAwaySorsolas1);

  $rugott=0;
  $kapott=0;
  $w=0;
  $d=0;
  $l=0;
  $pts=0;

  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result))
      {
        $rugott=$rugott+$row["home_goal"];
        $kapott=$kapott+$row["away_goal"];

        if($row["home_goal"]>$row["away_goal"])
        {
          $w++;
          $pts+=3;
        }
        else if($row["home_goal"]<$row["away_goal"])
        {
          $l++;
        }
        else
        {
          $d++;
          $pts+=1;
        }
      }
  }

  $selectAwaySorsolas2="SELECT * from sorsolas where bajnoksag_id=$bajnoksagID AND away_team='$away' AND lejatszott=true";
  $result = mysqli_query($conn, $selectAwaySorsolas2);

  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result))
      {
        $rugott=$rugott+$row["away_goal"];
        $kapott=$kapott+$row["home_goal"];

        if($row["away_goal"]<$row["home_goal"])
        {
          $l++;

        }
        else if($row["away_goal"]>$row["home_goal"])
        {
          $w++;
          $pts+=3;
        }
        else
        {
          $d++;
          $pts+=1;
        }
      }
  }

  $golkul=$rugott-$kapott;
  $updateAway="UPDATE csapatok set win=$w, draw=$d, lose=$l, rugott_gol=$rugott, kapott_gol=$kapott, goal_dif=$golkul, points=$pts
   where bajnoksag_id=$bajnoksagID and csapat_neve='$away'";

  if (!mysqli_query($conn, $updateAway))
  {
      echo "<script type='text/javascript'>alert('Végén van hiba!');</script>";
      mysqli_close($conn);
      return 0;
  }

mysqli_close($conn);

}

?>
