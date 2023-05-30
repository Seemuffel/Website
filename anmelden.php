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

        // Prepared Statement erstellen
        $stmt = $conn->prepare("SELECT * FROM benutzer WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Anmeldung erfolgreich
            header("Location: essen.html?username=" . urlencode($username));
            exit;
        } else {
            echo "Falscher Benutzername oder falsches Passwort.";
        }

        $stmt->close();
    } else {
        echo "Fehlender Benutzername oder Passwort in der Anfrage.";
    }
}

$conn->close();
?>
