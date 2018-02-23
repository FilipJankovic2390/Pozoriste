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

if(isset($_POST['dodaj-komad'])) {

    $komad_naziv = $_POST['komad_naziv'];
    $producent_id = $_POST['producent_id'];
    $komad_trupa = $_POST['komad_trupa'];

    $sql = "INSERT INTO pozorisni_komad (KOMAD_NAZIV, PRODUCENT_ID, KOMAD_TRUPA) VALUES ('$komad_naziv', '$producent_id', '$komad_trupa')";
    $result = $conn->query($sql);
    $conn->close();
    header("Location: komadi.php");

}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Dodaj pozorisni komad</title>
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
		  			<div class="panel-heading">Dodaj pozorisni komad</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="">
							<div class="form-group">
							    <label for="komad_naziv" class="col-sm-2 control-label">Naziv komada</label>
							    <div class="col-sm-6">
							    	<input type="text" class="form-control" name="komad_naziv" required>
							    </div>
							</div>
						  <div class="form-group">
						    <label for="producent_id" class="col-sm-2 control-label">Producent</label>
						    <div class="col-sm-6">
							  <select class="form-control" name="producent_id" required>
							  	  <option value="">--- Odaberite producenta ---</option>

			                    <?php
			                      // povuci iz tabele sve nazive pozorisnih komada i prikazi ih u dropdownu
			                      $sql = "SELECT * FROM producent ORDER BY PRODUCENT_IME";
			                      $result = $conn->query($sql);
			                      while($row = $result->fetch_assoc()) {
			                        echo "<option value='".$row['PRODUCENT_ID']."'>".$row['PRODUCENT_IME']."</option>";
			                      }
			                    ?>

							  </select>
							 </div>
							</div>
						  <div class="form-group">
						    <label for="komad_trupa" class="col-sm-2 control-label">Trupa</label>
						    <div class="col-sm-6">
						      <textarea class="form-control" name="komad_trupa" required></textarea>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-danger" name="dodaj-komad">Sačuvaj</button>
						      <a class="btn btn-default" href="komadi.php" role="button">Odustani</a>
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
