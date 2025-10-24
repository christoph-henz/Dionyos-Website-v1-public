<?php
include_once  'Mailer.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class ReservationAccept
{
    static function main():void
    {
        if (isset($_GET['id']) && isset($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
            $reservationId = $_GET['id'];
            $customerEmail = $_GET['email'];

            // Hier kannst du die Logik zur Bestätigung der Reservierung hinzufügen
            // Zum Beispiel: Aktualisiere den Status der Reservierung in der Datenbank

            // E-Mail-Bestätigung an den Kunden senden
            $to = $customerEmail; // E-Mail des Kunden
            $subject = "Bestätigung Ihrer Reservierung";
            $message = "Vielen Dank für Ihre Reservierung!\n\n";
            $message .= "Ihre Reservierung wurde erfolgreich bestätigt.\n";
            $message .= "Reservierungs-ID: $reservationId\n";

            // Funktion zum Senden der E-Mail (Stelle sicher, dass der Mailer korrekt konfiguriert ist)
            Mailer::isendMail($to, $subject, $message);

            // Weiterleitung auf die Hauptseite nach der E-Mail
            header("Location: https://www.dionysos-aburg.de/MainPage.php");
            exit();
        } else {
            echo "Ungültige Anfrage. Parameter fehlen oder sind ungültig.";
            exit();
        }
    }
}
ReservationAccept::main();

header("Location: https://www.dionysos-aburg.de/MainPage.php");
exit();