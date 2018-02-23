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

$predstava_id=$_GET['id'];

if(isset($_POST['izmeni-predstavu'])) {

    $komad_id = $_POST['komad_id'];
    $predstava_broj_mesta = $_POST['predstava_broj_mesta'];
    $predstava_cena = $_POST['predstava_cena'];
    $predstava_datum = $_POST['predstava_datum'];

    $sql = "UPDATE predstava SET KOMAD_ID='$komad_id', PREDSTAVA_BROJ_MESTA='$predstava_broj_mesta', PREDSTAVA_CENA='$predstava_cena', PREDSTAVA_DATUM='$predstava_datum' WHERE PREDSTAVA_ID='$predstava_id'";
    $result = $conn->query($sql);
    $conn->close();
      header("Location: predstave.php"); 
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Izmeni predstavu</title>
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
		  			<div class="panel-heading">Izmeni predstavu</div>
            <div class="panel-body">
  					<form class="form-horizontal" method="post" action="">

<?php
              echo "<div class='form-group'>";
              echo "<label for='komad_id' class='col-sm-2 control-label'>Naziv komada</label>";
              echo "<div class='col-sm-6'>";
              echo "<select class='form-control' name='komad_id'>";

              // pronadji prethodno izabrani komad
              $sql = "SELECT KOMAD_ID FROM predstava WHERE PREDSTAVA_ID=$_GET[id]";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {
                // posto postoji samo jedan rezultat dodeli tu vrednost promenljivoj $komad_id
                $komad_id = $row['KOMAD_ID'];
              };
              
              // povuci iz tabele i prikazi sve nazive pozorisnih komada i selektuj prethodno izabrani komad na osnovu promenljive $komad_id
              $sql = "SELECT * FROM pozorisni_komad ORDER BY KOMAD_NAZIV";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {
                echo "<option value='".$row['KOMAD_ID']."' ".(($row['KOMAD_ID']==$komad_id)?"selected":"").">".$row['KOMAD_NAZIV']."</option>";
              };
              echo "</select>";
        			echo "</div>";
        			echo "</div>";


              $sql = "SELECT * FROM predstava INNER JOIN pozorisni_komad ON predstava.KOMAD_ID=pozorisni_komad.KOMAD_ID WHERE predstava.PREDSTAVA_ID=$_GET[id]";

              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {

              echo "<div class='form-group'>";
              echo "<label for='predstava_broj_mesta' class='col-sm-2 control-label'>Broj mesta</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='predstava_broj_mesta' value='".$row['PREDSTAVA_BROJ_MESTA']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='predstava_cena' class='col-sm-2 control-label'>Cena</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='predstava_cena' value='".$row['PREDSTAVA_CENA']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='predstava_datum' class='col-sm-2 control-label'>Datum</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='datetime' class='form-control' name='predstava_datum' value='".$row['PREDSTAVA_DATUM']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
						  echo "<div class='col-sm-offset-2 col-sm-10'>";
						  echo "<button type='submit' class='btn btn-danger' name='izmeni-predstavu'>Sačuvaj</button> ";
              echo "<a class='btn btn-default' href='predstave.php' role='button'>Odustani</a>";
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
