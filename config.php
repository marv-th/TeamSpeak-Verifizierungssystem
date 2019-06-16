<?php 
#TeamSpeak Verifizierungssystem - #Game OS | Game-OS.de Servernetzwerk
#Coded by Marvin Thönneßen

/*

Hier kannst du die Einstellungen des Systems festlegen.

*/

$config = array(
    //TeamSpeak Server Query Username, mit welchen Account sich die Query verbinden soll. (Verwende kein serveradmin, da dies ein Sicherheitrisiko ist.)
    "query_username" => "",

    //TeamSpeak Server Query Passwort, mit welchen Passwort sich die Query unter dem angegebenen Account verbinden soll.
    "query_password" => "",

    //TeamSpeak Server Query Port, auf welchem die Query verbinden soll. (Standart: 10011)
    "query_port" => "10011",

    //TeamSpeak Server Port, auf welchem die Query verbinden soll. (Standart: 9987)
    "port" => "9987",

    //TeamSpeak Server Adresse, auf welchem die Query verbinden soll
    "adress" => "ts.game-os.de",

    //Botnamen der Query
    "name" => "GameOS-Verifybot",

    //Verifizierungsgruppe, welcher der Client bei der Verifizierung bekommen sollte.
    "group" => "34",

    //Verifizierungsnachricht, welcher der Client nach erfolgreicher verifizierung bekommen soll.
    "verify_message" => "Hey. Wir haben dich erfolgreich verifiziert. Wenn du Anregungen haben solltest, dann wende dich an den Support.",

    //Dauer der Verifizierung in sekunden, damit der Client ggf. Animation auf der Verifizierungsseite erleben kann. (0 für direkt(aus))
    "verify_sleep" => 3,

    //Der Abruf der Seite ist nur bei einer SSL/HTTPS Verbindung zulässig. [on,off]
    "ssl_check" => "off",

    //Client erhält während des Prozesses eine Nachricht. (Leer für nein)
    "process_msg" => ""
);

//Ab hier ignorieren!!

$needed_options = "query_username,query_password,query_port,port,adress,group";
foreach (explode(",",$needed_options) as $key => $value) {
    if ($config["$value"]==NULL) {
        echo "<b>Fehler</b> es ist kein Wert für $value festgelegt<br>";
        $lost_option = 1;
    }
}
if ($lost_option==1) {
    echo "<br>Bitte behebe diese Fehler, um das Verifizierungssystem nutzen zu können.";
    exit;
}