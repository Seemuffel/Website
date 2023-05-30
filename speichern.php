<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "deine_datenbank";

// Verbindung zur Datenbank herstellen
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}
echo "Verbindung zur Datenbank erfolgreich hergestellt.";

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

        // Ausgabe des vorbereiteten Statements zur Diagnose
        var_dump($stmt);

        // Fehler beim Vorbereiten des Statements abfangen
        if ($stmt === false) {
            echo "Fehler beim Vorbereiten des Statements: " . $conn->error;
        } else {
            if ($stmt->execute()) {
                echo "Speichern erfolgreich!";
                
                // Alle Benutzerdaten abrufen
                $selectAllStmt = $conn->prepare("SELECT * FROM benutzer");
                $selectAllStmt->execute();
                $result = $selectAllStmt->get_result();

                // Überprüfen, ob ein Ergebnis vorhanden ist
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "ID: " . $row['id'];
                        echo "Benutzername: " . $row['username'];
                        echo "Passwort: " . $row['password'];
                        echo "Essen: " . $row['essen'];
                        echo "<br>";
                    }
                } else {
                    echo "Keine Daten gefunden.";
                }

                $selectAllStmt->close();
            } else {
                echo "Fehler beim Speichern: " . $stmt->error;
            }
        }
    } else {
        echo "Es wurde kein Essen ausgewählt oder Benutzername fehlt.";
    }
}

$conn->close();
?>
