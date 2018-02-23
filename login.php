<?php
// podesavanje veze
include_once 'databaseConnection.php';

// start sesije
session_start();

// ukoliko je korisnik logovan posalji ga na pocetnu stranu
if(isset($_SESSION['login_user'])){
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // podaci o korisniku se primaju iz login forme
    $korisnicko_ime=mysqli_real_escape_string($conn,$_POST['korisnicko_ime']);
    $lozinka=mysqli_real_escape_string($conn,$_POST['lozinka']);

    // ispitaj da li u tabeli pretplatnik postoji korisnik sa ispravnom lozinkom
    $sql="SELECT PRETPLATNIK_ID, ULOGA FROM korisnik WHERE KORISNICKO_IME='$korisnicko_ime' and LOZINKA='$lozinka'";
    $result = $conn->query($sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    $count=mysqli_num_rows($result);

    // ukoliko pronadjes korisnika sa pravilno unetom lozinkom, pokreni sesiju i idi na pocetnu stranu
    if($count==1) {
        $_SESSION['login_user']=$korisnicko_ime;
        $_SESSION['user_role']=$row['ULOGA'];
        $_SESSION['user_id']=$row['PRETPLATNIK_ID'];

        header("location: index.php");
    }
    else {
        $error="Korisnicko ime i lozinka nisu ispravni.";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Prijava</title>
    <meta charset="utf-8">

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
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="registracija.php">Registracija</a></li>
                <li><a href="login.php">Prijava</a></li>
            </ul>

        </div>
    </div>
</nav>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Prijava</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="" name="loginform">
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
                              <button type="submit" class="btn btn-default">Prijava</button>
                              <a class="btn btn-danger" href="index.php" role="button">Odustani</a>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
                <div class="well well-lg">
                    Za privilegije administratora ulogujte se sa korisničkim imenom <strong>admin</strong> i lozinkom <strong>admin</strong>
                </div>
                <div class="well well-lg">
                    Za privilegije pretplatnika ulogujte se sa korisničkim imenom <strong>test</strong> i lozinkom <strong>test</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
