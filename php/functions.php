<?php

if(isset($_POST['new-Form-Submit-Name']))
{
  $id=nextChampionshipID();
  $sportag="Fifa";
  $bajnoksagNeve;
  $bajnoksagLetszam;
  $rajatszasLeszE;
  $rajatszasLetszam;
  $csoportokSzama;
  $csapatnevekListaja=array();
  $jatekosokListaja=array();


  //ellenőrzöm hogy üresek e.
  if(empty($_POST['ChampName']) || empty($_POST['new-form-players-number-name']) || empty($_POST['group-select-name']))
  {
    echo "<script type='text/javascript'>alert('Nincs minden kötelező elem kitöltve!')</script>";
    return 0;
  }
  else
  {
    $bajnoksagNeve=$_POST["ChampName"];
    $bajnoksagLetszam=$_POST["new-form-players-number-name"];
    $rajatszasLeszE=$_POST["playoff-radio-name"];
    $rajatszasLetszam=$_POST["fifa-playoff-number-name"];
    $csoportokSzama=$_POST["group-select-name"];
  }
  $csoportLista=csoportSorsolas($bajnoksagLetszam, $csoportokSzama);
  //csapatok kinyerése, listába tevése.
  for($i=0;$i<$bajnoksagLetszam;$i++)
  {
    $jatekos="name-textbar-name$i";
    $csapat="team-textbar-name$i";

    if(empty($_POST[$jatekos]) || empty($_POST[$csapat]))
    {
      echo "<script type='text/javascript'>alert('Nincs kitöltve minden játékos adata!')</script>";
      return 0;
    }

    $csapatnevekListaja[]=$_POST[$csapat];
    $jatekosokListaja[]=$_POST[$jatekos];
  }

  // Create connection
$conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
// Check connection
if (!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}

//Bajnokság hozzáadása.2
if(is_numeric($rajatszasLetszam))
{
  $insertBajnoksag = "INSERT INTO bajnoksagok (id,bajnoksag_neve, letszam, csoportok_szama, rajatszas, rajatszas_letszam, sportag)
  VALUES ($id, '$bajnoksagNeve', $bajnoksagLetszam, $csoportokSzama, $rajatszasLeszE, $rajatszasLetszam, 'Fifa')";
}
else {
  $insertBajnoksag = "INSERT INTO bajnoksagok (id, bajnoksag_neve, letszam, csoportok_szama, rajatszas, rajatszas_letszam, sportag)
  VALUES ($id, '$bajnoksagNeve', $bajnoksagLetszam, $csoportokSzama, $rajatszasLeszE, 0, 'Fifa')";
}

if (!mysqli_query($conn, $insertBajnoksag))
{
   echo "Error: " . $insertBajnoksag . "<br>" . mysqli_error($conn);
   return 0;
}

for($i=0;$i<count($csapatnevekListaja);$i++)
{
  $insertCsapatok = "INSERT INTO csapatok (bajnoksag_id, csapat_neve, jatekos_neve, helyezes, win, draw, lose, rugott_gol, kapott_gol, goal_dif, points, csoport)
  VALUES ($id, '$csapatnevekListaja[$i]', '$jatekosokListaja[$i]', $i+1, 0, 0, 0, 0, 0, 0, 0, $csoportLista[$i])";

  if (!mysqli_query($conn, $insertCsapatok))
  {
    echo "Error: " . $insertCsapatok . "<br>" . mysqli_error($conn);
    return 0;
  }
}

FifaSorsolas($csapatnevekListaja);

mysqli_close($conn);

}
//Eddig tart NEW.php hozzadós formja.


function nextChampionshipID()
{
  // Create connection
$conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT max(id) as max_id FROM bajnoksagok";
$result = mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);

mysqli_close($conn);

return $row["max_id"]+1;
}


function csoportSorsolas($csapatokSzama, $csoportokSzama)//szétosztja a csapatokat csoportokba.
{
    $csoportLetszam;//Kiszámoljuk hogy mekkora létszáma lehet egy csoportnak
    if($csapatokSzama%$csoportokSzama==0)
    {
        $csoportLetszam=$csapatokSzama/$csoportokSzama;
    }
    else
    {
        $csoportLetszam=($csapatokSzama/$csoportokSzama)+1;
    }

    $csoportCounter=array(0,0,0,0);//Ez vigyáz arra hogy tul sok csapat legyen egy csoportba
    $csoportLista;//Ebbe a listába írjuk bele a csoportokat sorba. 0..$csapatokszáma és ahanyadik indexű csapat kapja azt a számot olyan csoportba lesz

    for($i=0;$i<$csapatokSzama;$i++)//csoportosztás
    {
      $x=rand(1,$csoportokSzama);//random szám hogy hanyas csoport
      if($csoportCounter[$x-1]>=$csoportLetszam)//Itt vizsgálja hogy ne legyen több csapat a csoportba.
      {
        $i--;//azért így mert ha olyan szám jön ki trandombol ami már tul mutat a megfelelőn akkor ujra történik a randomizálás.
        continue;
      }

      $csoportLista[$i]=$x; // Listába írás.
      $csoportCounter[$x-1]++;//számlálás
    }

    return $csoportLista; // kész Listát visszaadjuk így lehet majd vele dolgozni.

}

function FifaSorsolas($csapatoktomb) //csapatoktomb= csapat nevek, x=csapatok száma;
{
  $bajnoksagID=nextChampionshipID()-1;
  $n; //csapatok száma
  $count=0; //fordulók száma
  if(count($csapatoktomb) % 2 == 0) //Ha páros csapatszámot kap a fv akkor a csapatok száma az a $csapatoktomb count.
  {
    $n = count($csapatoktomb);
  }
  else //Ha páratlan csapatszámot kap a fv akkor a csapatok száma az a $csapatoktomb count +1 és az utolsó helyre beírunk egy "BYE"-t.
  {
    $n = count($csapatoktomb) + 1;
    $csapatoktomb[]="BYE";
  }

  $n2 = $n / 2; //csapatok fele
  $fordulokSzama = ($n - 1) * 2; //fordulokSzama száma, csapatok fele-1 *2;
  $szezonfele = $fordulokSzama / 2; //szezon fele, ahonnan a visszavágókat kell játszani

  //csapatok inicializálása.
  $csapatok = array();
  $csapatok2 = array();
  $fordulok = array();

  //csapatokat betettem egy matrixba, egy forduló alapján
  for ($i = 0; $i < $n2; $i++)
  {
      $csapatok[0][$i] = $csapatoktomb[$count];
      $count++;
  }

  for ($i = $n2 - 1; $i >= 0; $i--)
  {
      $csapatok[1][$i] = $csapatoktomb[$count];
      $count++;
  }

  //átmásolja a 2. matrixba
  for ($i = 0; $i < 2; $i++)
  {
      for ($j = 0; $j < $n2; $j++)
      {
          $csapatok2[$i][$j] = $csapatok[$i][$j];
      }
  }

  //sorsolás kezdete ahol, k=aktuális fordulószám | k<fordulokszáma
  for ($k = 0; $k < $fordulokSzama; $k++)
  {
      if ($k > 0) //0. azaz 1. fordulónál nemváltoztatunk a sorrenden mert az statikusan megvan adva.
      {
          for ($i = 0; $i < 1; $i++) //eltoljuk a csapatokat eggyel arrébb. (innen)
          {
              for ($j = 1; $j < $n2; $j++)
              {
                  if ($i == 0 && $j < $n2 - 1) // ha a matrix [0,j] pozijába vagyunk
                  {
                      $csapatok2[$i][$j + 1] = $csapatok[$i][$j]; //elcsusztatjuk a csapatokat a másik matrixba.
                  }
                  else if ($i == 0 && $j == $n2 - 1)
                  {
                      for ($z = $n2 - 1; $z >= 0; $z--)
                      {
                          if ($z == 0)
                          {
                              $csapatok2[0][1] = $csapatok[1][$z];
                          }
                          else
                          {
                              $csapatok2[1][$z - 1] = $csapatok[1][$z];
                          }
                      }
                      $csapatok2[1][$n2 - 1] = $csapatok[$i][$j];
                  }
              }
          }
        }
      // eddig eltolás

      //sorsolás tárolása egy 3 dimenziós tömbben. ahol a [k,0,0] érték a forduló száma, a [k,0,i] az egyik csapat, és a [k,i,0] a másik csapat.
      $fordulok[$k][0][0] = $k;

      if ($k < $szezonfele) //Szezon felénél fordul a hazai pálya ezért itt forgatjuk a csapatokat.
      {
          for ($i = 1; $i <= $n2; $i++)//Csapatok tárolása mátrixban
          {
              $fordulok[$k][0][$i] = $csapatok2[0][$i - 1];
              $fordulok[$k][$i][0] = $csapatok2[1][$i - 1];
          }
      }
      else
      {
          for ($i = 1; $i <= $n2; $i++)
          {
              $fordulok[$k][$i][0] = $csapatok2[0][$i - 1];
              $fordulok[$k][0][$i] = $csapatok2[1][$i - 1];
          }
      }

      //jelenlegi fordulo átmásolása a másik matrixba.
      for ($i = 0; $i < 2; $i++)
      {
          for ($j = 0; $j < $n2; $j++)
          {
              $csapatok[$i][$j] = $csapatok2[$i][$j];
          }
      }
}

$conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
// Check connection
if (!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}
for($i=0;$i<$fordulokSzama;$i++)
{
  for($k = 1; $k < $n / 2 + 1; $k++)
  {
    $ford=$i+1;
    $home=$fordulok[$i][0][$k];
    $away=$fordulok[$i][$k][0];
    $insertCsapatok = "INSERT INTO sorsolas (bajnoksag_id, fordulo, home_team, away_team, home_goal, away_goal, lejatszott)
    VALUES ($bajnoksagID, $ford, '$home', '$away', 0, 0, false)";

    if (!mysqli_query($conn, $insertCsapatok))
    {
      echo "Error: " . $insertCsapatok . "<br>" . mysqli_error($conn);
      return 0;
    }
  }

}
mysqli_close($conn);
}

function indexPage()
{

  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  // Check connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  $selectBajnoksagok = "SELECT * FROM bajnoksagok";
  $result = mysqli_query($conn, $selectBajnoksagok);

  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {

          echo "<a href='league.php' onclick=leagueLink(".$row["id"].")><div id="."'leagueDiv'".">
          <p>".$row["bajnoksag_neve"]."</p><table>";
          indexMatches($row["id"]);
          echo "</table></div></a>";//Az üres helyre írni kel egy függvényt ami az adott ID-hoz tartozó meccseket kiírja oda tr-td modszerrel
      }
  } else {
      echo "0 Results!";
      mysqli_close($conn);
      return 0;
  }

  mysqli_close($conn);
}

function indexMatches($id)
{
  $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }
  $selectCsapatok = "SELECT csapat_neve, helyezes, points FROM csapatok where bajnoksag_id=$id order by points LIMIT 10";
  $result = mysqli_query($conn, $selectCsapatok);

  if (mysqli_num_rows($result) > 0) {

      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>".$row["helyezes"]."</td>
                  <td>".$row["csapat_neve"]."</td>
                  <td>".$row["points"]." pts</td>
                </tr>";
      }
  } else {
      mysqli_close($conn);
      return 0;
  }

  mysqli_close($conn);
}

  function bajnoksag($bajnoksagID)
  {

    $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
		// Check connection
		if (!$conn)
		{
		    die("Connection failed: " . mysqli_connect_error());
		}
    $selectBajnoksagNeve="SELECT bajnoksag_neve from bajnoksagok where id=$bajnoksagID";
    $result0 = mysqli_query($conn, $selectBajnoksagNeve);
    $bajnoksagNeve = mysqli_fetch_array($result0);
    echo "<h1>".$bajnoksagNeve["bajnoksag_neve"]."</h1>";
    echo "<thead><tr> <th>Helyezés</th> <th>Csapat</th> <th>W</th> <th>D</th> <th>L</th> <th>Rugott gól</th> <th>Kapott Gól</th> <th>Gól különbség</th><th>Pont</th></tr></thead>";

		$sql = "SELECT * from csapatok  where bajnoksag_id=$bajnoksagID order by points desc, goal_dif desc, rugott_gol desc, win desc, helyezes ";
		$result = mysqli_query($conn, $sql);

    $helyezes=0;
    while($row = mysqli_fetch_assoc($result))
    {
      $helyezes++;
      echo "<tr>
      <td>$helyezes</td>
      <td>".$row["csapat_neve"]."</td>
      <td>".$row["win"]."</td>
      <td>".$row["draw"]."</td>
      <td>".$row["lose"]."</td>
      <td>".$row["rugott_gol"]."</td>
      <td>".$row["kapott_gol"]."</td>
      <td>".$row["goal_dif"]."</td>
      <td>".$row["points"]."</td>
      </tr>";
    }
		mysqli_close($conn);

  }


  function FifaSorsolasSelect($bajnoksagID)
  {

    $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
		// Check connection
		if (!$conn)
		{
		    die("Connection failed: " . mysqli_connect_error());
		}
    $selectBajnoksagNeve="SELECT bajnoksag_neve from bajnoksagok where id=$bajnoksagID";
    $result0 = mysqli_query($conn, $selectBajnoksagNeve);
    $bajnoksagNeve = mysqli_fetch_array($result0);
    echo "<h1>".$bajnoksagNeve["bajnoksag_neve"]."</h1>";

		$sql = "SELECT * from sorsolas  where bajnoksag_id=$bajnoksagID order by fordulo ";
		$result = mysqli_query($conn, $sql);

    $forduloSzamlalo=1;

    echo "<table id='SorTable'>";
    echo "<thead><tr> <th>1. Forduló:</th> </tr></thead>";
    while($row = mysqli_fetch_assoc($result))
    {
      if($row["fordulo"]!=$forduloSzamlalo)
      {
        echo "</table><table id='SorTable'><thead><tr> <th>".$row["fordulo"].". Forduló:</th> </tr></thead>";
        $forduloSzamlalo++;
      }
      if($row["lejatszott"]==true)
      {
      echo "<tr id=".$row["id"]." >
      <td>".$row["home_team"]."
      ".$row["home_goal"]." -
      ".$row["away_goal"]."
      ".$row["away_team"]."</td>
      </tr>";
      }
      else
      {
        echo "<tr id=".$row["id"].">
        <td>".$row["home_team"]." -
        ".$row["away_team"]."</td>
        </tr>";
      }

    }

    echo "</table>";
		mysqli_close($conn);

  }

  function FifaStats($bajnoksagID)
  {
    $conn = mysqli_connect('den1.mysql3.gear.host', 'bakisportsdb', 'Fu1jGbd~t~Hp', 'bakisportsdb');
		// Check connection
		if (!$conn)
		{
		    die("Connection failed: " . mysqli_connect_error());
		}
    $selectBajnoksagNeve="SELECT bajnoksag_neve from bajnoksagok where id=$bajnoksagID";
    $result0 = mysqli_query($conn, $selectBajnoksagNeve);
    $bajnoksagNeve = mysqli_fetch_array($result0);
    echo "<h1>".$bajnoksagNeve["bajnoksag_neve"]."</h1>";

    $selectGoal="SELECT * from stats where bajnoksag_id=$bajnoksagID AND stat_type='gol' order by stat desc";
    $result1 = mysqli_query($conn, $selectGoal);

    echo "<table id='statTable'>
    <thead><th colspan='3'>Góllövőlista</th></thead>";

    $szamlalo=0;

    while($rowGoal = mysqli_fetch_assoc($result1))
    {
      $szamlalo++;
      echo "
      <tr id=".$rowGoal["id"].">
        <td>$szamlalo</td>
        <td>".$rowGoal["jatekos"]."</td>
        <td>".$rowGoal["stat"]."</td>
      </tr>";
    }
    echo "<tr id='addGollovo'><td colspan='3'><button>+</button></td></tr></table>";

    $selectYellow="SELECT * from stats where bajnoksag_id=$bajnoksagID AND stat_type='yellow' order by stat desc";
    $result2 = mysqli_query($conn, $selectYellow);

    echo "<table id='statTable'>
    <thead><th colspan='3'>Sárga lapok</th></thead>";

    $szamlalo=0;

    while($rowYellow = mysqli_fetch_assoc($result2))
    {
      $szamlalo++;
      echo "
      <tr id=".$rowYellow["id"].">
        <td>$szamlalo</td>
        <td>".$rowYellow["jatekos"]."</td>
        <td>".$rowYellow["stat"]."</td>
      </tr>";
    }
    echo "<tr id='addYellow'><td colspan='3'><button>+</button></td></tr></table>";


    $selectRed="SELECT * from stats where bajnoksag_id=$bajnoksagID AND stat_type='red' order by stat desc";
    $result3 = mysqli_query($conn, $selectRed);

    echo "<table id='statTable'>
    <thead><th colspan='3'>Piros lapok</th></thead>";

    $szamlalo=0;

    while($rowRed = mysqli_fetch_assoc($result3))
    {
      $szamlalo++;
      echo "
      <tr id=".$rowRed["id"].">
        <td>$szamlalo</td>
        <td>".$rowRed["jatekos"]."</td>
        <td>".$rowRed["stat"]."</td>
      </tr>";
    }
    echo "<tr id='addRed'><td colspan='3'><button>+</button></td></tr></table>";


    $selectGoalkeeper = "SELECT csapat_neve, kapott_gol from csapatok where bajnoksag_id=$bajnoksagID order by kapott_gol ";
    $result4 = mysqli_query($conn, $selectGoalkeeper);

    echo "<table id='goalkeeperTable'>
    <thead><th colspan='3'>Legjobb kapusok</th></thead>";

    $szamlalo=0;

    while($rowGoalkeeper = mysqli_fetch_assoc($result4))
    {
      $szamlalo++;
      echo "
      <tr>
        <td>$szamlalo</td>
        <td>".$rowGoalkeeper["csapat_neve"]."</td>
        <td>".$rowGoalkeeper["kapott_gol"]."</td>
      </tr>";
    }
    echo "</table>";

    mysqli_close($conn);
  }
?>
