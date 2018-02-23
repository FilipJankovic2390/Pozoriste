<?php
include_once 'databaseConnection.php';

session_start();

// ukoliko korisnik nije logovan posalji ga na stranu za logovanje
if(!isset($_SESSION['login_user'])){
    header("Location: login.php");
}

// Proveri da li prijavljeni korisnik ima ulogu administratora. Ukoliko nema vrati ga na pocetnu stranu.
if(!$_SESSION['user_role']==1) {
	header("Location: index.php");
}

// treba vratiti karte koje su visak nakon ponistavanja rezervacije
// procitaj sve rezervacije sa zadatim ID-jem
$sql = "SELECT * FROM zahtev WHERE REZERVACIJA_ID=$_GET[id]";
$result = $conn->query($sql);

// u svakoj od rezervacija dodaj broj kupljenih karata na broj slobodnih mesta za datu predstavu
while($row = $result->fetch_assoc()) {
  $predstava_id = $row['PREDSTAVA_ID'];
  $zahtev_mesta = $row['ZAHTEV_MESTA'];

  $sql = "UPDATE predstava SET PREDSTAVA_BROJ_MESTA = PREDSTAVA_BROJ_MESTA + '$zahtev_mesta' WHERE PREDSTAVA_ID = '$predstava_id'";
  $conn->query($sql);
}

// kad vratis sve karte obrisi rezervaciju
$sql = "UPDATE rezervacije SET VALIDNOST = 0 WHERE REZERVACIJA_ID=$_GET[id]";

if ($conn->query($sql) === TRUE) {
    echo "Rezervacija je uspesno otkazana!";
} else {
    echo "Doslo je greske!" . $conn->error;
}

$conn->close();

header('Refresh: 3; URL=rezervacije.php');
?>
