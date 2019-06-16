<?php 

$get_clid=$_GET["clid"];
if ($get_clid==null) {
    echo "Fehler:::Es wurde kein Client angegeben:::0";
} else {
    include("../libraries/ts3admin.class.php");
    include("../config.php");
    ini_set("display_errors", 1);
    $ts3 = new ts3admin($config["adress"], $config["query_port"]);
    $connect = $ts3->connect();
    if($connect['success']) {
        $ts3logincheck = $ts3->login($config["query_username"], $config["query_password"]);
        if ($ts3logincheck['success'] OR ($config["query_username"] and $config["query_password"])==NULL) {
            $portchecker = $ts3->selectServer($config["port"]);
            if ($config["name"]!=null) {$config["name"]="TeamSpeak-Verifizierungssystem";}
            $ts3->setName($config["name"]);
            if($portchecker['success']) {
                if ($config["process_msg"]!=null) {
                    $sendMessage = $ts3->sendMessage(1,$get_clid,"Wir werden deinen Client verifizieren");
                }
                if ($config["verify_sleep"]!=0 AND is_int($config["verify_sleep"])) {sleep($config["verify_sleep"]);}
                $clientInfo = $ts3->clientInfo($get_clid);
                $client_dbid = $clientInfo["data"]["client_database_id"];
                $client_connection_client_ip = $clientInfo["data"]["connection_client_ip"];
                $client_servergroups = $clientInfo["data"]["client_servergroups"];
                if ($client_connection_client_ip!=$_SERVER["REMOTE_ADDR"]) {
                    echo "Halt!:::Du kannst dich nicht verifizieren, da wir nicht bestätigen können, ob du das wirklich bist. 👀:::0";
                } else {
                    if (in_array($config["group"], explode(",",$client_servergroups))) {
                        echo "Nene, das geht nicht nochmal...😅:::Du kannst dich doch nicht zweimal mit dem gleichen Client verifizieren 😝:::0";
                    } else {
                        $serverGroupAddClient = $ts3->serverGroupAddClient($config["group"], $client_dbid);
                        if ($serverGroupAddClient["success"]) {
                            if ($config["verify_message"]!=null) {
                                $ts3->sendMessage(1,$get_clid,$config["verify_message"].' Entdecke [url=https://gosbot.de]GOSBot[/url]! Kostenloser TeamSpeak Musikbot.');
                            }
                            echo "Super!🙌:::Du hast deinen Client erfolgreich verifiziert 😋:::1";
                        } else {
                            echo "TeamSpeak Fehler!😢:::Es ist ein TeamSpeak Fehler bei der Rängevergabe aufgetreten. Bitte wende dich an unser Team.:::0";
                        }
                    }
                }
            }
        }
    }
}