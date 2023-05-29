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
    // Überprüfen, ob die Array-Schlüssel definiert sind
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        // Benutzernamen und Passwort aus dem Formular erhalten
        $username = $_POST["username"];
        $password = $_POST["password"];

        // SQL-Befehl zum Überprüfen der Anmeldedaten
        $sql = "SELECT * FROM benutzer WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Anmeldung erfolgreich
            header("Location: essen.html?username=" . urlencode($username));
            exit;
        } else {
            echo "Falscher Benutzername oder falsches Passwort.";
        }
    } else {
        echo "Fehlende Benutzername oder Passwort in der Anfrage.";
    }
}

$conn->close();
?>
