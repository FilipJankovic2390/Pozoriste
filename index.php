<?php
include_once 'databaseConnection.php';

// start sesije
session_start();

// izvrsi sledeci kod ako se strana ucitala nakon klika za dodavanje novog zahteva
if(isset($_POST['dodaj-zahtev'])) {

    // Proveri da li je ulogovani pretplatnik dostigao max broj od 15 neproknjizenih zahteva
    $sql = "SELECT * FROM zahtev WHERE PRETPLATNIK_ID=$_SESSION[user_id] AND ZAHTEV_PROKNJIZEN = 0";
    $result = $conn->query($sql);
    $count=mysqli_num_rows($result);

    if($count>=15) {
      echo "<script>";
      echo "alert('Prekoracili ste maksimalan broj od 15 zahteva! Obrisite neki od zahteva ili pristupite placanju.')";
      echo "</script>";

      header('Refresh: 0; URL=index.php');
    }
    else {

      $predstava_id = intval($_POST['predstava_id']);
      $broj_mesta = intval($_POST['broj_mesta']);
      $zahtev_mesta = intval($_POST['zahtev_mesta']);
      $pretplatnik_id = intval($_POST['pretplatnik_id']);
      $razlika_mesta = $broj_mesta-$zahtev_mesta;

      if(($broj_mesta >= $zahtev_mesta) && ($zahtev_mesta >0)){

        // snimi zahtev
        $sql = "INSERT INTO zahtev (PREDSTAVA_ID, ZAHTEV_MESTA, PRETPLATNIK_ID) VALUES ('$predstava_id', '$zahtev_mesta', '$pretplatnik_id')";
        $conn->query($sql);

        // umanji broj slobodnih mesta za zahtevani broj mesta u tabeli predstave
        $sql = "UPDATE predstava SET PREDSTAVA_BROJ_MESTA=$razlika_mesta WHERE PREDSTAVA_ID=$predstava_id";
        $conn->query($sql);

        $conn->close();

        header("Location: index.php");
      }
      else {
        echo "<script>";
        echo "alert('Nema dovoljno raspolozivih mesta ili ste uneli negativnu vrednost. Pokusajte ponovo!')";
        echo "</script>";

        header('Refresh: 0; URL=index.php');
      }
  }

}

// izvrsi upis rezervacije u tabelu ako se strana ucitala nakon klika na dugme za placanje
if(isset($_POST['dodaj-rezervaciju'])) {

    // snimi rezervaciju u bazu
    $pretplatnik_id = $_SESSION['user_id'];
    $dt = new DateTime();
    $rezervacija_datum = $dt->format('Y-m-d H:i:s');
    $ukupan_iznos = intval($_POST['ukupan_iznos']);

    $sql = "INSERT INTO rezervacije (PRETPLATNIK_ID, REZERVACIJA_DATUM, UKUPAN_IZNOS) VALUES ('$pretplatnik_id', '$rezervacija_datum', '$ukupan_iznos')";
    $conn->query($sql);

    // procitaj REZERVACIJA_ID poslednje rezervacije
    $sql="SELECT * FROM rezervacije ORDER BY REZERVACIJA_ID DESC LIMIT 1";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      $rezervacija_id = $row['REZERVACIJA_ID'];
    }

    // proknjizi sve zahteve za prethodnu rezervaciju i dodaj im vrednost REZERVACIJA_ID
    $sql = "UPDATE zahtev SET ZAHTEV_PROKNJIZEN=1,REZERVACIJA_ID=$rezervacija_id WHERE PRETPLATNIK_ID=$_SESSION[user_id] AND REZERVACIJA_ID IS NULL";
    $conn->query($sql);
    $conn->close();
    header("Location: rezervacije.php");

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pozorište</title>
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
                <li class="active"><a href="index.php">Predstave</a></li>
                <?php if(isset($_SESSION['user_id'])){
                  echo "<li><a href='rezervacije.php'>Moje rezervacije</a></li>";
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
                <div class="panel-heading">Predstave</div>
                <div class="panel-body">
                <?php

                    // ne prikazuj predstave koje su se vec odigrale, vec samo one predstojece i tako onemoguci pretplatnika da rezervise predstavu posle datuma predstave
                    $sql = "SELECT predstava.PREDSTAVA_ID, pozorisni_komad.KOMAD_NAZIV, predstava.PREDSTAVA_BROJ_MESTA, predstava.PREDSTAVA_CENA, predstava.PREDSTAVA_DATUM FROM predstava INNER JOIN pozorisni_komad ON predstava.KOMAD_ID=pozorisni_komad.KOMAD_ID WHERE predstava.PREDSTAVA_DATUM > CURDATE() ORDER BY predstava.PREDSTAVA_DATUM";

                    $result = $conn->query($sql);

                    $count=mysqli_num_rows($result);

                    // ukoliko nema predstava prikazi prikladan tekst
                    if($count==0) {
                      echo "Nema zakazanih predstava.";
                    }
                    else {
                      echo "<table class='table table-striped table-bordered'><thead><tr><th>Naziv predstave</th><th>Slobodnih mesta</th><th>Cena karte</th><th>Datum</th><th>Vreme</th>";

                      // ukoliko je korisnik prijavljen, prikazi mu naslove polja za rezervaciju predstave
                      if(isset($_SESSION['user_id'])){
                        echo "<th class='text-right'>Broj karata</th><th></th>";
                      }

                      echo "</tr></thead><tbody>";
                      while($row = $result->fetch_assoc())
                      {
                          echo "<form action='' method='POST'><tr><td>".
                          $row['KOMAD_NAZIV']."</td><td>".
                          $row['PREDSTAVA_BROJ_MESTA']."</td><td>".
                          $row['PREDSTAVA_CENA']."</td>";

                          $timestamp = strtotime($row['PREDSTAVA_DATUM']);
                          echo "<td>".date('d.m.Y', $timestamp)."</td>";
                          echo "<td>".date('H:i', $timestamp)."</td>";

                          // ukoliko je korisnik prijavljen, prikazi mu polja za rezervaciju predstave
                          if(isset($_SESSION['user_id'])){
                            echo "<td><input type='hidden' name='pretplatnik_id' value='".$_SESSION['user_id']."' />".
                            "<input type='hidden' name='predstava_id' value='".$row['PREDSTAVA_ID']."' />".
                            "<input type='hidden' name='broj_mesta' value='".$row['PREDSTAVA_BROJ_MESTA']."' />".
                            "<input type='text' class='form-control pull-right' style='text-align:right; width:80px' name='zahtev_mesta' value='' /></td><td class='text-right'><button type='submit' class='btn btn-danger' name='dodaj-zahtev'>Rezerviši</button></td></tr></form>";
                          }
                      }
                      echo "</tbody></table>";
                    }
                ?>
                </div>
            </div>
        </div>

        <?php

            // ukoliko je korisnik prijavljen, prikazi blok sa njegovim zahtevima za rezervacije
            if(isset($_SESSION['user_id'])){
              echo "<div class='col-xs-12'>";
              echo "<div class='panel panel-danger'>";
              echo "<div class='panel-heading'>Moji zahtevi</div>";
              echo "<div class='panel-body'>";

                      $sql = "SELECT predstava.PREDSTAVA_ID,
                                     predstava.PREDSTAVA_CENA,
                                     predstava.PREDSTAVA_DATUM,
                                     predstava.PREDSTAVA_BROJ_MESTA,
                                     pozorisni_komad.KOMAD_NAZIV,
                                     zahtev.ZAHTEV_ID,
                                     zahtev.PREDSTAVA_ID,
                                     zahtev.ZAHTEV_MESTA,
                                     zahtev.ZAHTEV_PROKNJIZEN
                                FROM zahtev
                               INNER JOIN predstava ON zahtev.PREDSTAVA_ID = predstava.PREDSTAVA_ID
                               INNER JOIN pozorisni_komad ON predstava.KOMAD_ID = pozorisni_komad.KOMAD_ID
                               WHERE PRETPLATNIK_ID='".$_SESSION['user_id']."' AND zahtev.ZAHTEV_PROKNJIZEN = 0";

                      $result = $conn->query($sql);
                      $count=mysqli_num_rows($result);

                      // ukoliko korisnik nema zahteva za rezervacijama prikazi mu prikladan tekst
                      if($count==0) {
                        echo "Nema zahteva za rezervacijama.";
                      }
                      else {
                        $ukupan_iznos = 0;

                        echo "<table class='table table-striped table-bordered'><thead><tr><th>Naziv predstave</th><th>Datum</th><th>Vreme</th><th>Broj karata</th><th class='text-right'>Cena karte</th><th class='text-right'>Ukupna cena</th><th></th></tr></thead><tbody>";
                        while($row = $result->fetch_assoc())
                        {
                            echo "<tr><td>".$row['KOMAD_NAZIV']."</td>";

                            // prikazi datum i vreme u posebnim kolonama tabele
                            $timestamp = strtotime($row['PREDSTAVA_DATUM']);
                            echo "<td>".date('d.m.Y', $timestamp)."</td>"; // prikazi datum u formatu 13.01.2017
                            echo "<td>".date('H:i', $timestamp)."</td>"; // prikazi vreme u formatu 17:30

                            // izracunaj broj slobodnih mesta u slucaju brisanja zahteva
                            $broj_mesta = intval($row['ZAHTEV_MESTA']) + intval($row['PREDSTAVA_BROJ_MESTA']);

                            echo "<td>".$row['ZAHTEV_MESTA']."</td>";
                            echo "<td class='text-right'>".$row['PREDSTAVA_CENA']."</td>";
                            echo "<td class='text-right'>".($row['ZAHTEV_MESTA']*$row['PREDSTAVA_CENA'])."</td>";
                            echo "<td class='text-right'><a class='btn btn-danger' href='obrisi_zahtev.php?id=".$row['ZAHTEV_ID']."&mesta=".$broj_mesta."&predstava_id=".$row['PREDSTAVA_ID']."'>Poništi</a></td>";

                            // dodaj cenu zahteva na ukupnu cenu
                            $ukupan_iznos = $ukupan_iznos + ($row['ZAHTEV_MESTA']*$row['PREDSTAVA_CENA']);
                        }

                        echo "</tr><tr><td colspan='5'></td><td class='text-right'><strong>".$ukupan_iznos."</strong></td>";

                        // Ukoliko je kartica pretplatnika istekla, prikazi mu upozorenje i sakrij dugme za placanje
                        $sql = "SELECT DATUM_ISTICANJA FROM pretplatnik WHERE PRETPLATNIK_ID=$_SESSION[user_id]";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){

                        if($row['DATUM_ISTICANJA'] < date("Y-m-d")) {
                          echo "<td></td></tr></tbody></table><div class='well well-lg'>Kreditna kartica vam je istekla. Plaćanje je onemogućeno.<br>Izmenite podatke o kartici i pokušajte ponovo!</div>";
                        }
                        else {
                        echo "<td class='text-right'><form action='' method='post'><input type='hidden' name='ukupan_iznos' value='".$ukupan_iznos."' /><button type='submit' class='btn btn-success' name='dodaj-rezervaciju'>Plaćanje</button></form></td></tr></tbody></table>";
                        }
                      }
                  echo "</div>";
                echo "</div>";
              echo "</div>";
            }
          }
        ?>
    </div>
</div>
</body>
</html>
