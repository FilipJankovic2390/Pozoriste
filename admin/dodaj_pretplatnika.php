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

// izvrsi sledeci blok koda ukoliko je strana ucitana nakon pritiska na dugme 'Sacuvaj pretplanika'
if(isset($_POST['dodaj-pretplatnika'])) {

    // procitaj podatke iz forme i dodaj novog pretplatnika u tabelu pretplatnik
    $pretplatnik_ime = $_POST['pretplatnik_ime'];
    $pretplatnik_adresa = $_POST['pretplatnik_adresa'];
    $pretplatnik_mesto = $_POST['pretplatnik_mesto'];
    $pretplatnik_telefon = $_POST['pretplatnik_telefon'];
    $vrsta_kartice = $_POST['vrsta_kartice'];
    $broj_kartice = $_POST['broj_kartice'];
    $datum_isticanja = $_POST['datum_isticanja'];
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    $sql = "INSERT INTO pretplatnik (PRETPLATNIK_IME, PRETPLATNIK_ADRESA, PRETPLATNIK_MESTO, PRETPLATNIK_TELEFON, VRSTA_KARTICE, BROJ_KARTICE, DATUM_ISTICANJA) VALUES ('$pretplatnik_ime', '$pretplatnik_adresa', '$pretplatnik_mesto', '$pretplatnik_telefon', '$vrsta_kartice', '$broj_kartice', '$datum_isticanja')";
    $result = $conn->query($sql);

    // procitaj PRETPLATNIK_ID upravo dodatog pretplatnika i dodaj ga i u tabelu korisnik
    $sql="SELECT * FROM pretplatnik ORDER BY PRETPLATNIK_ID DESC LIMIT 1";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $pretplatnik_id = $row['PRETPLATNIK_ID'];
    }
    $sql="INSERT INTO korisnik (PRETPLATNIK_ID, KORISNICKO_IME, LOZINKA) VALUES ('$pretplatnik_id', '$korisnicko_ime','$lozinka')";
    $result = $conn->query($sql);

    // zatvori konekciju i prosledi korisnika na stranu admin/pretplatnici.php
    $conn->close();
    header("Location: pretplatnici.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Dodaj pretplatnika</title>
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
		  			<div class="panel-heading">Dodaj pretplatnika</div>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="">
						  <div class="form-group">
						    <label for="pretplatnik_ime" class="col-sm-2 control-label">Prezime i ime</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="pretplatnik_ime" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="pretplatnik_adresa" class="col-sm-2 control-label">Adresa</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="pretplatnik_adresa" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="pretplatnik_mesto" class="col-sm-2 control-label">Mesto</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="pretplatnik_mesto" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="pretplatnik_telefon" class="col-sm-2 control-label">Telefon</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="pretplatnik_telefon" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="vrsta_kartice" class="col-sm-2 control-label">Vrsta kartice</label>
						    <div class="col-sm-6">
							  <select class="form-control" name="vrsta_kartice" required>
							  	  <option value=""></option>
								  <option value="VISA">VISA</option>
								  <option value="MASTER CARD">MASTER CARD</option>
								  <option value="AMERICAN EXPRESS">AMERICAN EXPRESS</option>
								  <option value="DINERS">DINERS</option>
							  </select>
							 </div>
							</div>
							<div class="form-group">
						    <label for="broj_kartice" class="col-sm-2 control-label">Broj kartice</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="broj_kartice" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="datum_isticanja" class="col-sm-2 control-label">Datum isticanja</label>
						    <div class="col-sm-6">
						      <input type="date" class="form-control" name="datum_isticanja" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="korisnicko_ime" class="col-sm-2 control-label">Korisnicko ime</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" name="korisnicko_ime" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="lozinka" class="col-sm-2 control-label">Lozinka</label>
						    <div class="col-sm-6">
						      <input type="password" class="form-control" name="lozinka" required>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-danger" name="dodaj-pretplatnika">Sačuvaj</button>
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
