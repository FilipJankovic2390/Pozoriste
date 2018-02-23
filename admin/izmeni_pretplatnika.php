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

$pretplatnik_id=$_GET['id'];

if(isset($_POST['izmeni-pretplatnika'])) {

    $pretplatnik_ime = $_POST['pretplatnik_ime'];
    $pretplatnik_adresa = $_POST['pretplatnik_adresa'];
    $pretplatnik_mesto = $_POST['pretplatnik_mesto'];
    $pretplatnik_telefon = $_POST['pretplatnik_telefon'];
    $vrsta_kartice = $_POST['vrsta_kartice'];
    $broj_kartice = $_POST['broj_kartice'];
    $datum_isticanja = $_POST['datum_isticanja'];
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    $sql = "UPDATE pretplatnik SET PRETPLATNIK_IME='$pretplatnik_ime', PRETPLATNIK_ADRESA='$pretplatnik_adresa', PRETPLATNIK_MESTO='$pretplatnik_mesto', PRETPLATNIK_TELEFON='$pretplatnik_telefon', VRSTA_KARTICE='$vrsta_kartice', BROJ_KARTICE='$broj_kartice', DATUM_ISTICANJA='$datum_isticanja' WHERE PRETPLATNIK_ID='$pretplatnik_id'";
    $conn->query($sql);

    $sql = "UPDATE korisnik SET KORISNICKO_IME='$korisnicko_ime', LOZINKA='$lozinka' WHERE PRETPLATNIK_ID='$pretplatnik_id'";
    $conn->query($sql);
    
    $conn->close();
    header("Location: pretplatnici.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Izmeni pretplatnika</title>
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
		  			<div class="panel-heading">Izmeni pretplatnika</div>
            <div class="panel-body">
  						<form class="form-horizontal" method="post" action="">

<?php
            $sql = "SELECT * FROM korisnik INNER JOIN pretplatnik ON korisnik.PRETPLATNIK_ID = pretplatnik.PRETPLATNIK_ID WHERE pretplatnik.PRETPLATNIK_ID=$_GET[id]";
        		$result = $conn->query($sql);
        		while($row = $result->fetch_assoc()) {

        			echo "<div class='form-group'>";
              echo "<label for='pretplatnik_ime' class='col-sm-2 control-label'>Prezime i ime</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='pretplatnik_ime' value='".$row['PRETPLATNIK_IME']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='pretplatnik_adresa' class='col-sm-2 control-label'>Adresa</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='pretplatnik_adresa' value='".$row['PRETPLATNIK_ADRESA']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='pretplatnik_mesto' class='col-sm-2 control-label'>Mesto</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='pretplatnik_mesto' value='".$row['PRETPLATNIK_MESTO']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='pretplatnik_telefon' class='col-sm-2 control-label'>Telefon</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='pretplatnik_telefon' value='".$row['PRETPLATNIK_TELEFON']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='vrsta_kartice' class='col-sm-2 control-label'>Vrsta kartice</label>";
              echo "<div class='col-sm-6'>";
              echo "<select class='form-control' name='vrsta_kartice'>";
              echo "<option value='' ".(($row['VRSTA_KARTICE']=='')?"selected":"")."></option>";
              echo "<option value='VISA' ".(($row['VRSTA_KARTICE']=='VISA')?"selected":"").">VISA</option>";
              echo "<option value='MASTER CARD' ".(($row['VRSTA_KARTICE']=='MASTER CARD')?"selected":"").">MASTER CARD</option>";
              echo "<option value='AMERICAN EXPRESS' ".(($row['VRSTA_KARTICE']=='AMERICAN EXPRESS')?"selected":"").">AMERICAN EXPRESS</option>";
              echo "<option value='DINERS' ".(($row['VRSTA_KARTICE']=='DINERS')?"selected":"").">DINERS</option>";
              echo "</select>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='broj_kartice' class='col-sm-2 control-label'>Broj kartice</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='broj_kartice' value='".$row['BROJ_KARTICE']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='datum_isticanja' class='col-sm-2 control-label'>Datum isticanja</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='date' class='form-control' name='datum_isticanja' value='".$row['DATUM_ISTICANJA']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='korisnicko_ime' class='col-sm-2 control-label'>Korisnicko ime</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='korisnicko_ime' value='".$row['KORISNICKO_IME']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='lozinka' class='col-sm-2 control-label'>Lozinka</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='lozinka' value='".$row['LOZINKA']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
						  echo "<div class='col-sm-offset-2 col-sm-10'>";
						  echo "<button type='submit' class='btn btn-danger' name='izmeni-pretplatnika'>Sačuvaj</button> ";
              echo "<a class='btn btn-default' href='pretplatnici.php' role='button'>Odustani</a>";
						  echo "</div>";
						  echo "</div>";
        		}
?>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
