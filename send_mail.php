<?php
/**
 * Kontaktformular PHP-Skript - Finale Version
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- ZIEL-EMAIL ---
    $empfaenger = "ansgor.walff+pvv@outlook.de"; 
    // ------------------

    // 1. Daten bereinigen
    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // 2. Validierung
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Bei Fehlern zurück zum Formular
        header("Location: index.html?status=error#contact");
        exit;
    }

    // 3. E-Mail Inhalt
    $betreff = "Neue Kontaktanfrage von: $name";
    
    $email_content = "Neue Nachricht über das Kontaktformular:\n\n";
    $email_content .= "Name:     $name\n";
    $email_content .= "E-Mail:   $email\n\n";
    $email_content .= "Nachricht:\n$message\n";

    // 4. Header (Wichtig für Zustellbarkeit & direktes Antworten)
    // Wir senden 'technisch' von einer Adresse deiner Domain (oder noreply)
    // aber Reply-To sorgt dafür, dass deine Antwort an den Kunden geht.
    $headers = array(
        'From' => 'noreply@parkraum-versorgungstechnik.de', 
        'Reply-To' => $email,
        'X-Mailer' => 'PHP/' . phpversion(),
        'Content-Type' => 'text/plain; charset=utf-8'
    );

    // Header-Array in String umwandeln
    $header_string = "";
    foreach ($headers as $key => $value) {
        $header_string .= "$key: $value\r\n";
    }

    // 5. Versenden
    if (mail($empfaenger, $betreff, $email_content, $header_string)) {
        // Erfolg -> Weiterleitung zur Website
        header("Location: index.html?status=success#contact");
        exit;
    } else {
        // Serverfehler
        header("Location: index.html?status=servererror#contact");
        exit;
    }

} else {
    // Direkter Aufruf der PHP-Datei -> zurück zur Startseite
    header("Location: index.html");
    exit;
}
?>