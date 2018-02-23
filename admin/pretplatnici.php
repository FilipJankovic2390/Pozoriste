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
	<title>Pretplatnici</title>
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
				<li class="active"><a href="pretplatnici.php">Pretplatnici</a></li>
				<li><a href="predstave.php">Predstave</a></li>
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
		  			<div class="panel-heading">Pretplatnici</div>
					<?php
				        $sql = "SELECT * FROM korisnik INNER JOIN pretplatnik ON korisnik.PRETPLATNIK_ID = pretplatnik.PRETPLATNIK_ID ORDER BY korisnik.ULOGA DESC";
				        $result = $conn->query($sql);
				        echo "<table class='table table-striped table-bordered'><thead><tr><th>IME i PREZIME</th><th>ADRESA</th><th>MESTO</th><th>TELEFON</th><th>KARTICA</th><th>BROJ KARTICE</th><th>DATUM ISTICANJA</th><th>KORISNICKO IME</th><th>ULOGA</th><th></th><th></th></tr></thead><tbody>";
				        while($row = $result->fetch_assoc())
				        {
				            echo "<tr><td>".$row['PRETPLATNIK_IME']."</td><td>".
				            $row['PRETPLATNIK_ADRESA']."</td><td>".
				            $row['PRETPLATNIK_MESTO']."</td><td>".
				            $row['PRETPLATNIK_TELEFON']."</td><td>".
				            $row['VRSTA_KARTICE']."</td><td>".
				            $row['BROJ_KARTICE']."</td><td>".
				            $row['DATUM_ISTICANJA']."</td><td>".
				            $row['KORISNICKO_IME']."</td><td>";

                    if($row['ULOGA']==1){
                      echo "administrator";
                    }
                    else {
                      echo "pretplatnik";
                    };

                    echo "</td><td width='30'>
				            <a href='izmeni_pretplatnika.php?id=".$row['PRETPLATNIK_ID']."'><span class='glyphicon glyphicon-pencil'></span></a></td><td width='30'>
				            <a href='obrisi_pretplatnika.php?id=".$row['PRETPLATNIK_ID']."'><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
				        }
				        echo "</tbody></table>";
				    ?>
			</div>
			<a class="btn btn-danger" href="dodaj_pretplatnika.php" role="button">Dodaj pretplatnika</a>
		</div>
	</div>
</body>
</html>
