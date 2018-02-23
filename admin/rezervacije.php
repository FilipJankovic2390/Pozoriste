<?php
include_once '../databaseConnection.php';

session_start();

// ukoliko korisnik nije logovan posalji ga na stranu za logovanje
if(!isset($_SESSION['login_user'])){
    header("Location: ../login.php");
}

// Proveri da li prijavljeni korisnik ima ulogu administratora. Ukoliko nema vrati ga na pocetnu stranu.
if(!$_SESSION['user_role']==1) {
	header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Rezervacije</title>
    <meta charset="utf-8">

    <!-- ucitavanje Bootstrap CSS stilova -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>
<div class="container">
<nav class="navbar navbar-default">
<div class="navbar-header">
	<a class="navbar-brand" href="../index.php">Pozorište</a>
</div>
<div class="collapse navbar-collapse">
	<ul class="nav navbar-nav">
		<li><a href="pretplatnici.php">Pretplatnici</a></li>
		<li><a href="predstave.php">Predstave</a></li>
		<li><a href="komadi.php">Pozorišni komadi</a></li>
		<li><a href="producenti.php">Producenti</a></li>
		<li class="active"><a href="rezervacije.php">Rezervacije</a></li>
		<li><a href="izvestaji.php">Izveštaji</a></li>
	</ul>
	<ul class="nav navbar-nav navbar-right">
		<li class="navbar-text">Dobrodošli, <?php echo $_SESSION['login_user'] ?></li>
		<li><a href="../logout.php">Odjava</a></li>
	</ul>
</div>
</nav>
<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-danger">
	  			<div class="panel-heading">Rezervacije</div>
				<?php

					// spajamo tabele rezervacije i pretplatnik da bi smo prikazali PRETPLATNIK_IME na osnovu PRETPLATNIK-ID
					$sql = "SELECT rezervacije.REZERVACIJA_ID, rezervacije.REZERVACIJA_DATUM, rezervacije.UKUPAN_IZNOS, rezervacije.VALIDNOST, pretplatnik.PRETPLATNIK_ID, pretplatnik.PRETPLATNIK_IME FROM rezervacije INNER JOIN pretplatnik ON rezervacije.PRETPLATNIK_ID = pretplatnik.PRETPLATNIK_ID WHERE rezervacije.VALIDNOST = 1 ORDER BY rezervacije.REZERVACIJA_DATUM DESC";

			        $result = $conn->query($sql);
              $count=mysqli_num_rows($result);

              // ukoliko korisnik nema zahteva za rezervacijama prikazi mu prikladan tekst
              if($count==0) {
                echo "<table class='table'><tr><td>Nema rezervacija.</td></tr></table>";
              }
              else {
  			        echo "<table class='table table-striped table-bordered'><thead><tr><th>ID</th><th>Pretplatnik</th><th>Datum rezervacije</th><th>Vreme rezervacije</th><th>Iznos rezervacije</th><th></th></tr></thead><tbody>";

  			        while($row = $result->fetch_assoc())
  			        {
  			            echo "<tr><td>".$row['REZERVACIJA_ID']."</td>";
  			            echo "<td>".$row['PRETPLATNIK_IME']."</td>";

  	                  // prikazi datum i vreme u posebnim kolonama tabele
  	                  $timestamp = strtotime($row['REZERVACIJA_DATUM']);
  	                  echo "<td>".date('d.m.Y', $timestamp)."</td>"; // prikazi datum u formatu 13.01.2017
  	                  echo "<td>".date('H:i', $timestamp)."</td>"; // prikazi vreme u formatu 17:30

  	                  echo "<td>".$row['UKUPAN_IZNOS']."</td>";
                      echo "<td width='30'><a href='obrisi_rezervaciju.php?id=".$row['REZERVACIJA_ID']."'><span class='glyphicon glyphicon-remove'></span></a></td>";
  			        }
  			        echo "</tr></tbody></table>";
            }
			    ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>
