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

$sql = "DELETE FROM predstava WHERE PREDSTAVA_ID=$_GET[id]";
$conn->query($sql);

if ($conn->query($sql) === TRUE) {
    echo "Predstava je uspesno obrisana!";
} else {
    echo "Doslo je greske!" . $conn->error;
}

$conn->close();

header('Refresh: 3; URL=predstave.php');
?>
