<?php
// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "deine_datenbank";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Benutzernamen und Passwort aus dem Formular erhalten
    $username = $_POST["username"];
    $password = $_POST["password"];

    // SQL-Befehl zum Einfügen der Daten
    $sql = "INSERT INTO benutzer (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registrierung erfolgreich!";
    } else {
        echo "Fehler bei der Registrierung: " . $conn->error;
    }
}

$conn->close();
?>
