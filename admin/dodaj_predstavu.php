<?php
include_once '../databaseConnection.php';

session_start();

// ukoliko korisnik nije logovan posalji ga na stranu za logovanje
if(!isset($_SESSION['login_user'])){
    header("Location: ../login.php");
}

// Da bi onemogucio obicne pretplatnike da dodaju predstave, proveri da li prijavljeni korisnik ima ulogu administratora. Ukoliko nije administrator, vrati ga na pocetnu stranu.
if(!$_SESSION['user_role']==1) {
	header("Location: ../index.php");
}

if(isset($_POST['dodaj-predstavu'])) {

    $komad_id = $_POST['komad_id'];
    $predstava_broj_mesta = $_POST['predstava_broj_mesta'];
    $predstava_cena = $_POST['predstava_cena'];
    $predstava_datum = $_POST['predstava_datum'];

    // Proveri da li se navedeni pozorisni komad vec ne prikazuje istog dana
    $sql = "SELECT * FROM predstava WHERE DATE('$predstava_datum') = DATE(PREDSTAVA_DATUM) AND $komad_id = KOMAD_ID";
    $result = $conn->query($sql);
    $count = mysqli_num_rows($result);

	if ($count > 0) {
	  // ukoliko postoji pozorisni komad istog datuma prikazi poruku o gresci
	  echo "<script>alert('Izabrani pozorišni komad se vec igra naznačenog datuma. Izmenite datum i pokušajte ponovo!')</script>";
	}
	else {
		// ukoliko se trazeni komad ne igra istog datuma snimi predstavu u tabelu
		$sql = "INSERT INTO predstava (KOMAD_ID, PREDSTAVA_BROJ_MESTA, PREDSTAVA_CENA, PREDSTAVA_DATUM) VALUES ('$komad_id', '$predstava_broj_mesta', '$predstava_cena', '$predstava_datum')";
		$result = $conn->query($sql);
		$conn->close();
    	header("Location: predstave.php");
	}   

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Dodaj predstavu</title>
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
		  			<div class="panel-heading">Dodaj predstavu</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="">
						  <div class="form-group">
						    <label for="komad_id" class="col-sm-2 control-label">Naziv komada</label>
						    <div class="col-sm-6">
							  <select class="form-control" name="komad_id" required>
							  	  <option value="">--- Odaberite pozorisni komad ---</option>

				                    <?php
				                      // povuci iz tabele sve nazive pozorisnih komada i prikazi ih u dropdownu
				                      $sql = "SELECT * FROM pozorisni_komad ORDER BY KOMAD_NAZIV";
				                      $result = $conn->query($sql);
				                      while($row = $result->fetch_assoc()) {
				                        echo "<option value='".$row['KOMAD_ID']."'>".$row['KOMAD_NAZIV']."</option>";
				                      }
				                    ?>

							  </select>
							 </div>
							</div>
							<div class="form-group">
						    <label for="predstava_broj_mesta" class="col-sm-2 control-label">Broj mesta</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="predstava_broj_mesta" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="predstava_cena" class="col-sm-2 control-label">Cena</label>
						    <div class="col-sm-6">
						      <input type="number" class="form-control" name="predstava_cena" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="predstava_datum" class="col-sm-2 control-label">Datum</label>
						    <div class="col-sm-6">
						      <input type="datetime-local" class="form-control" name="predstava_datum" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-danger" name="dodaj-predstavu">Sačuvaj</button>
						      <a class="btn btn-default" href="pretplatnici.php" role="button">Odustani</a>
						    </div>
						  </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
