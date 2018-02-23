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
	<title>Izvestaji</title>
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
		<li><a href="rezervacije.php">Rezervacije</a></li>
		<li class="active"><a href="izvestaji.php">Izveštaji</a></li>
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
  			<div class="panel-heading">Lista zahteva</div>
  			<table class="table table-striped table-bordered">
  				<tbody>
  					<tr><td>Pronaći pretplatnika kome će prvom isteći kreditna kartica</td><td><a class="btn btn-success" href="izvestaj-1.php" role="button">Prikaži</a></td></tr>
  					<tr><td>Pronaći na spisku rezervacija onu koja ima najveći iznos za naplatu kao i podatke o vlasniku i kreditnoj kartici kojom će vlasnik te rezervacije izmiriti obaveze</td><td><a class="btn btn-success disabled" href="izvestaj-2.php" role="button">Prikaži</a></td></tr>
  					<tr><td>Pronaći podatke o producentu koji ima najviše različitih predstava za koje više nema slobodnih mesta</td><td><a class="btn btn-success" href="izvestaj-3.php" role="button">Prikaži</a></td></tr>
  					<tr><td>Sortirati u opadajucem redosledu pozorišne predstave u zavisnosti od cene karata</td><td><a class="btn btn-success" href="izvestaj-4.php" role="button">Prikaži</a></td></tr>
  					<tr><td>Napraviti pogled koji za svakog pretplatnika prikazuje koliko je ukupno novca potrošio na sve rezervacije koje je napravio, kao i broj kreditne kartice kojom izmiruje obaveze</td><td><a class="btn btn-success" href="izvestaj-5.php" role="button">Prikaži</a></td></tr>
  				</tbody>
  			</table>
		</div>		
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-danger">
  			<div class="panel-heading">Rezervacija koja ima najveći iznos za naplatu kao i podaci o vlasniku i kreditnoj kartici kojom će vlasnik te rezervacije izmiriti obaveze</div>

			<?php 
		        // spajamo tabele rezervacije i pretplatnik da bi smo prikazali PRETPLATNIK_IME na osnovu PRETPLATNIK-ID
				$sql = "SELECT * FROM rezervacije INNER JOIN pretplatnik ON rezervacije.PRETPLATNIK_ID = pretplatnik.PRETPLATNIK_ID LIMIT 1";

		        $result = $conn->query($sql);
		        echo "<table class='table table-striped table-bordered'><thead><tr><th>ID rezervacije</th><th>Datum rezervacije</th><th>Vreme rezervacije</th><th>Iznos rezervacije</th><th>Pretplatnik</th><th>Kreditna kartica</th><th>Broj kreditne kartice</th><th>Datum isticanja</th></tr></thead><tbody>";
		        
		        while($row = $result->fetch_assoc())
		        {
		          echo "<tr><td>".$row['REZERVACIJA_ID']."</td>";

                  // prikazi datum i vreme u posebnim kolonama tabele
                  $timestamp = strtotime($row['REZERVACIJA_DATUM']);
                  echo "<td>".date('d.m.Y', $timestamp)."</td>"; // prikazi datum u formatu 13.01.2017
                  echo "<td>".date('H:i', $timestamp)."</td>"; // prikazi vreme u formatu 17:30

                  echo "<td>".$row['UKUPAN_IZNOS']."</td>";
                  echo "<td>".$row['PRETPLATNIK_IME']."</td>";
                  echo "<td>".$row['VRSTA_KARTICE']."</td>";
                  echo "<td>".$row['BROJ_KARTICE']."</td>";
                  echo "<td>".$row['DATUM_ISTICANJA']."</td>";
		        }

		        echo "</tr></tbody></table>";
		    ?>

			</div>
		</div>
	</div>
</div>
</div>
</body>
</html>