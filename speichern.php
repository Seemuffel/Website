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
    if (isset($_POST["username"]) && isset($_POST["essen"])) {
        // Das ausgewählte Essen erhalten
        $essen = $_POST["essen"];

        // Benutzername des angemeldeten Benutzers erhalten
        $username = $_POST["username"];
        echo "Benutzername: " . $username;

        // Prepared Statement erstellen
        $stmt = $conn->prepare("UPDATE benutzer SET essen = ? WHERE username = ?");
        $stmt->bind_param("ss", $essen, $username);

        if ($stmt->execute()) {
            echo "Speichern erfolgreich!";
        } else {
            echo "Fehler beim Speichern: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Es wurde kein Essen ausgewählt oder Benutzername fehlt.";
    }
}

$conn->close();
?>
