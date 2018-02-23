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

$producent_id=$_GET['id'];

if(isset($_POST['izmeni-producenta'])) {

    $producent_ime = $_POST['producent_ime'];

    $sql = "UPDATE producent SET PRODUCENT_IME='$producent_ime' WHERE PRODUCENT_ID='$producent_id'";
    $conn->query($sql);
    
    $conn->close();
    header("Location: producenti.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Izmeni producenta</title>
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
				<li class="active"><a href="producenti.php">Producenti</a></li>
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
		  			<div class="panel-heading">Izmeni producenta</div>
            <div class="panel-body">
  						<form class="form-horizontal" method="post" action="">

<?php
             

              $sql = "SELECT * FROM producent WHERE PRODUCENT_ID=$_GET[id]";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()) {

              echo "<div class='form-group'>";
              echo "<label for='producent_ime' class='col-sm-2 control-label'>Ime i prezime</label>";
              echo "<div class='col-sm-6'>";
              echo "<input type='text' class='form-control' name='producent_ime' value='".$row['PRODUCENT_IME']."'>";
              echo "</div>";
              echo "</div>";

              echo "<div class='form-group'>";
						  echo "<div class='col-sm-offset-2 col-sm-10'>";
						  echo "<button type='submit' class='btn btn-danger' name='izmeni-producenta'>Sačuvaj</button> ";
              echo "<a class='btn btn-default' href='producenti.php' role='button'>Odustani</a>";
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
