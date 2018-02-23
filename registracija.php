<?php
include_once 'databaseConnection.php';

session_start();

if(isset($_POST['dodaj-pretplatnika'])) {

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
    
    $sql = "INSERT INTO korisnik (KORISNICKO_IME, LOZINKA, PRETPLATNIK_ID) VALUES ('$korisnicko_ime', '$lozinka', ' $conn->insert_id')";
    echo $sql;
    
    $result = $conn->query($sql);
    $conn->close();
    header("Location: login.php");
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Registracija</title>
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
                    <div class="panel-heading">Registracija</div>
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
                              <button type="submit" class="btn btn-default" name="dodaj-pretplatnika">Sačuvaj</button> 
                              <a class="btn btn-danger" href="index.php" role="button">Odustani</a>
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