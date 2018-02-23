<?php

// --------------------------------------------------------------------------------------- //
// podaci na ovu stranu dolaze sa strane index.php iz forme za prikaz i brisanje zahteva   //
// ----------------------------------------------------------------------------------------//

// konektuj se na bazu
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

// povecaj broj slobodnih mesta za otkazani broj mesta
$broj_mesta = $_GET['mesta'];
$predstava_id = $_GET['predstava_id'];
$sql = "UPDATE predstava SET PREDSTAVA_BROJ_MESTA=$broj_mesta WHERE PREDSTAVA_ID=$predstava_id";
$result = $conn->query($sql);

// obrisi zahtev nakon sto si vratio zahtevani broj karata
$sql = "DELETE FROM zahtev WHERE ZAHTEV_ID=$_GET[id]";

if ($conn->query($sql) === TRUE) {
    echo "Zahtev je uspesno obrisan!";
} else {
    echo "Doslo je greske!" . $conn->error;
}

$conn->close();

header('Refresh: 2; URL=index.php');
?>