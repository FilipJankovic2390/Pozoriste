<?php
include_once 'databaseConnection.php';

session_start();

// ukoliko korisnik nije logovan posalji ga na stranu za logovanje
if(!isset($_SESSION['login_user'])){
    header("Location: login.php");
}

if(isset($_POST['dodaj-zahtev'])) {

    $predstava_id = $_POST['pretplatnik_ime'];
    $pretplatnik_adresa = $_POST['pretplatnik_adresa'];
    $pretplatnik_mesto = $_POST['pretplatnik_mesto'];
    $pretplatnik_telefon = $_POST['pretplatnik_telefon'];
    $vrsta_kartice = $_POST['vrsta_kartice'];
    $broj_kartice = $_POST['broj_kartice'];
    $datum_isticanja = $_POST['datum_isticanja'];
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    $sql = "INSERT INTO korisnik (KORISNICKO_IME, LOZINKA) VALUES ('$korisnicko_ime', '$lozinka')";
    $sql = "INSERT INTO zahtev (PRETPLATNIK_IME, PRETPLATNIK_ADRESA, PRETPLATNIK_MESTO, PRETPLATNIK_TELEFON, VRSTA_KARTICE, BROJ_KARTICE, DATUM_ISTICANJA) VALUES ('$pretplatnik_ime', '$pretplatnik_adresa', '$pretplatnik_mesto', '$pretplatnik_telefon', '$vrsta_kartice', '$broj_kartice', '$datum_isticanja')";
    $result = $conn->query($sql);
    $conn->close();
    header("Location: pretplatnici.php");
}


$sql = "DELETE FROM pretplatnik WHERE PRETPLATNIK_ID=$_GET[id]";

if ($conn->query($sql) === TRUE) {
    echo "Zahtev je uspesno realizovan!";
} else {
    echo "Doslo je greske!" . $conn->error;
}

$conn->close();

header('Refresh: 3; URL=index.php');
?>
