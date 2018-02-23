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

$komad_id=$_GET['id'];

if(isset($_POST['izmeni-komad'])) {

    $komad_naziv = $_POST['komad_naziv'];
    $producent_id = $_POST['producent_id'];
    $komad_trupa = $_POST['komad_trupa'];

    $sql = "UPDATE pozorisni_komad SET KOMAD_NAZIV='$komad_naziv', PRODUCENT_ID='$producent_id', KOMAD_TRUPA='$komad_trupa' WHERE KOMAD_ID='$komad_id'";
    $conn->query($sql);
    
    $conn->close();
    header("Location: komadi.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Izmeni pozorisni komad</title>
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
		  			<div class="panel-heading">Izmeni pozorisni komad</div>
            <div class="panel-body">
  						<form class="form-horizontal" method="post" action="">

<?php
            $sql = "SELECT * FROM pozorisni_komad INNER JOIN producent ON pozorisni_komad.PRODUCENT_ID = producent.PRODUCENT_ID WHERE pozorisni_komad.KOMAD_ID=$_GET[id]";
        		$result = $conn->query($sql);
        		while($row = $result->fetch_assoc()) {

        			echo "<div class='form-group'>";
              echo "<label for='komad_naziv' class='col-sm-2 control-label'>Naziv komada</label>";
              echo "<div class='col-sm-6'>";
        			echo "<input type='text' class='form-control' name='komad_naziv' value='".$row['KOMAD_NAZIV']."'>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
              echo "<label for='producent_id' class='col-sm-2 control-label'>Producent</label>";
              echo "<div class='col-sm-6'>";
              echo "<select class='form-control' name='producent_id'>";
            }

              // pronadji prethodno izabranog producenta
              $sql = "SELECT PRODUCENT_ID FROM pozorisni_komad WHERE KOMAD_ID=$_GET[id]";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {
                // posto postoji samo jedan rezultat dodeli tu vrednost promenljivoj $komad_id
                $producent_id = $row['PRODUCENT_ID'];
              };
              
              // povuci iz tabele i prikazi sve producente i selektuj prethodno izabranog producenta na osnovu promenljive $producent_id
              $sql = "SELECT * FROM producent ORDER BY PRODUCENT_IME";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {
                echo "<option value='".$row['PRODUCENT_ID']."' ".(($row['PRODUCENT_ID']==$producent_id)?"selected":"").">".$row['PRODUCENT_IME']."</option>";
              };
              echo "</select>";
              echo "</div>";
              echo "</div>";

              $sql = "SELECT * FROM pozorisni_komad INNER JOIN producent ON pozorisni_komad.PRODUCENT_ID = producent.PRODUCENT_ID WHERE pozorisni_komad.KOMAD_ID=$_GET[id]";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {

              echo "<div class='form-group'>";
              echo "<label for='komad_trupa' class='col-sm-2 control-label'>Trupa</label>";
              echo "<div class='col-sm-6'>";
        			echo "<textarea class='form-control' name='komad_trupa'>".$row['KOMAD_TRUPA']."</textarea>";
        			echo "</div>";
        			echo "</div>";

              echo "<div class='form-group'>";
						  echo "<div class='col-sm-offset-2 col-sm-10'>";
						  echo "<button type='submit' class='btn btn-danger' name='izmeni-komad'>Sačuvaj</button> ";
              echo "<a class='btn btn-default' href='komadi.php' role='button'>Odustani</a>";
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
