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
	<title>Pozorisni komadi</title>
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
		<li class="active"><a href="komadi.php">Pozorišni komadi</a></li>
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
  			<div class="panel-heading">Pozorisni komadi</div>
			<?php 
		        $sql = "SELECT * FROM pozorisni_komad INNER JOIN producent ON pozorisni_komad.PRODUCENT_ID = producent.PRODUCENT_ID ORDER BY pozorisni_komad.KOMAD_NAZIV";
		        $result = $conn->query($sql);
		        echo "<table class='table table-striped table-bordered'><thead><tr><th>NAZIV KOMADA</th><th>PRODUCENT</th><th>TRUPA</th><th></th><th></th></tr></thead><tbody>";
		        while($row = $result->fetch_assoc())
		        {
		            echo "<tr><td>".$row['KOMAD_NAZIV']."</td>";
		            echo "<td>".$row['PRODUCENT_IME']."</td>";
		            echo "<td>".$row['KOMAD_TRUPA']."</td>";

		            echo "<td width='30'><a href='izmeni_komad.php?id=".$row['KOMAD_ID']."'><span class='glyphicon glyphicon-pencil'></span></a></td><td width='30'>
				            <a href='obrisi_komad.php?id=".$row['KOMAD_ID']."'><span class='glyphicon glyphicon-remove'></span></a></td>";
		        }
		        echo "</tr></tbody></table>";         
		    ?>
			</div>
			<a class="btn btn-danger" href="dodaj_komad.php" role="button">Dodaj pozorišni komad</a>
		</div>
	</div>
</div>
</body>
</html>