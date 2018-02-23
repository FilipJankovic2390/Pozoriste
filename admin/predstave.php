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
	<title>Predstave</title>
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
			<li class="active"><a href="predstave.php">Predstave</a></li>
			<li><a href="komadi.php">Pozorišni komadi</a></li>
			<li><a href="producenti.php">Producenti</a></li>
			<li><a href="rezervacije.php">Rezervacije</a></li>
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
	  			<div class="panel-heading">Predstave</div>
				<?php

					$sql = "SELECT predstava.PREDSTAVA_ID, 
								   pozorisni_komad.KOMAD_NAZIV, 
								   predstava.PREDSTAVA_BROJ_MESTA, 
								   predstava.PREDSTAVA_CENA, 
								   predstava.PREDSTAVA_DATUM 
							FROM predstava INNER JOIN pozorisni_komad ON 
								   predstava.KOMAD_ID=pozorisni_komad.KOMAD_ID 
							ORDER BY predstava.PREDSTAVA_DATUM DESC";

			        $result = $conn->query($sql);
			        echo "<table class='table table-striped table-bordered'><thead><tr><th>NAZIV KOMADA</th><th>SLOBODNIH MESTA</th><th>CENA KARTE</th><th>DATUM</th><th>VREME</th><th></th><th></th></tr></thead><tbody>";
			        while($row = $result->fetch_assoc())
			        {
			            echo "<tr><td>".$row['KOMAD_NAZIV']."</td><td>".
			            $row['PREDSTAVA_BROJ_MESTA']."</td><td>".
			            $row['PREDSTAVA_CENA']."</td>";

						// prikazi datum i vreme u posebnim kolonama tabele
						$timestamp = strtotime($row['PREDSTAVA_DATUM']);
						echo "<td>".date('d.m.Y', $timestamp)."</td>"; // prikazi datum u formatu 13.01.2017
						echo "<td>".date('H:i', $timestamp)."</td>"; // prikazi vreme u formatu 17:30

						echo "<td width='30'><a href='izmeni_predstavu.php?id=".$row['PREDSTAVA_ID']."'><span class='glyphicon glyphicon-pencil'></span></a></td><td width='30'>
				            <a href='obrisi_predstavu.php?id=".$row['PREDSTAVA_ID']."'><span class='glyphicon glyphicon-remove'></span></a></td>";
			        }
			        echo "</tr></tbody></table>";
			    ?>
			</div>
      		<a class="btn btn-danger" href="dodaj_predstavu.php" role="button">Dodaj predstavu</a>
		</div>
	</div>
</div>
</body>
</html>
