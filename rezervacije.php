<?php
include_once 'databaseConnection.php';

// start sesije
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Moje rezervacije</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Pozorište</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Predstave</a></li>
                <?php if(isset($_SESSION['user_id'])){
                  echo "<li class='active'><a href='rezervacije.php'>Moje rezervacije</a></li>";
                } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php
                // za administratore prikazi dodatne opcije u meniju
                if(isset($_SESSION['login_user'])){
                  if($_SESSION['user_role']==1) {
                    echo "<li><a href='admin/pretplatnici.php'>Administracija</a></li>";
                    echo "<li><a href='logout.php'>Odjava</a></li>";
                  }
                  else {
                    echo "<li><a href='logout.php'>Odjava</a></li>";
                  }
                }
                else {
                  echo "<li><a href='registracija.php'>Registracija</a></li>";
                  echo "<li><a href='login.php'>Prijava</a></li>";
                }
            ?>
            </ul>

        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
		<div class="col-xs-12">
			<div class="panel panel-danger">
	  			<div class="panel-heading">Moje rezervacije</div>
          <div class="panel-body">
  				<?php

  					$sql = "SELECT * FROM rezervacije WHERE PRETPLATNIK_ID = $_SESSION[user_id] AND VALIDNOST = 1 ORDER BY REZERVACIJA_DATUM DESC";

  			        $result = $conn->query($sql);
                $count=mysqli_num_rows($result);

                // ukoliko korisnik nema zahteva za rezervacijama prikazi mu prikladan tekst
                if($count==0) {
                  echo "Nema zahteva za rezervacijama.";
                }
                else {
    			        echo "<table class='table table-striped table-bordered'><thead><tr><th>ID</th><th>DATUM</th><th>VREME</th><th class='text-right'>UKUPAN IZNOS</th><th></th></tr></thead><tbody>";
    			        while($row = $result->fetch_assoc())
    			        {
    			            echo "<tr><td>".$row['REZERVACIJA_ID']."</td>";

    	                  // prikazi datum i vreme u posebnim kolonama tabele
    	                  $timestamp = strtotime($row['REZERVACIJA_DATUM']);
    	                  echo "<td>".date('d.m.Y', $timestamp)."</td>"; // prikazi datum u formatu 13.01.2017
    	                  echo "<td>".date('H:i', $timestamp)."</td>"; // prikazi vreme u formatu 17:30

    	                  echo "<td class='text-right'>".$row['UKUPAN_IZNOS']."</td>";
                        echo "<td class='text-right'><a href='obrisi_rezervaciju.php?id=".$row['REZERVACIJA_ID']."'><span class='glyphicon glyphicon-remove'></span></a></td>";
    			        }
    			        echo "</tr></tbody></table>";
                }
  			    ?>
          </div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
