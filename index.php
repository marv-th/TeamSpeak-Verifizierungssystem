<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=chrome">
    <title>Verifizierungssystem von #Game OS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="libraries/msg_animation.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
<form class="border border-light p-5">
    <?php 

    include("libraries/ts3admin.class.php");
    include("config.php");
    ini_set("display_errors", 0);
    $server_ssl = strtolower($_SERVER["HTTPS"]);
    if ($config["ssl_check"]=="on" AND $server_ssl!="on") {
        echo "Diese Seite kann nicht geladen werden, da dies keine Sichere verbindung ist. Kontaktiere den Support";
    }
    $ts3 = new ts3admin($config["adress"], $config["query_port"]);
    $connect = $ts3->connect();
    if($connect['success']) {
        $ts3logincheck = $ts3->login($config["query_username"], $config["query_password"]);
        if ($ts3logincheck['success'] OR ($config["query_username"] and $config["query_password"])==NULL) {
            $portchecker = $ts3->selectServer($config["port"]);
            if ($config["name"]!=null) {$config["name"]="TeamSpeak-Verifizierungssystem";}
            $ts3->setName($config["name"]);
            if($portchecker['success']) {
            $clientList = $ts3->clientList("-uid -away -voice -times -groups -info -icon -country -ip -badges]");
                $client_count = 0;
                foreach ($clientList as $key => $chrow) {
                    foreach ($chrow as $row) {
                        if ($_SERVER["REMOTE_ADDR"] == $row["connection_client_ip"]) {
                            $client_count = $client_count+1;
                            $client_only_name = $row["client_nickname"];
                            $client_only_clid = $row["clid"];
                        }
                    }
                }
                if ($client_count==0) {
                    echo '<p class="h6 mb-4 text-center">Wir konnten deinen Client leider nicht finden. Wende dich am besten an den Support</p>';
                } else {
                    echo '<p class="h4 mb-4 text-center" id="main_header">Wähle deinen Client aus</p>
                    <div id="main_section">
                        <p class="h6 mb-4 text-center">Wir konnten ein paar Clients finden, welche Du sein könntest</p>';
                        foreach ($clientList as $key => $chrow) {
                            foreach ($chrow as $row) {
                                if ($_SERVER["REMOTE_ADDR"] == $row["connection_client_ip"]) {
                                    $client_count = $client_count+1;
                                    $clientAvatar = $ts3->clientAvatar($row["client_unique_identifier"]);
                                    $client_description = $ts3->clientInfo($row["clid"])["data"]["client_description"];
                                    //echo '<option value="'.$row["clid"].'">'.$row["client_nickname"].' ('.$row["client_unique_identifier"].')</option>';
                                    echo '
                                        <p>
                                        <div class="card text-center">
                                            <div class="card-body">';
                                            if ($clientAvatar["data"]!=null) {echo '<img src="data:image/png;base64,'.$clientAvatar["data"].'" />';} echo '
                                            <h5 class="card-title">'.$row["client_nickname"].'</h5>
                                            <p class="card-text">'.$row["client_platform"].' ('.$row["client_version"].')</p>
                                            <p class="card-text">'.$client_description.'</p>
                                            <button onClick="option_me_yes('.$row["clid"].');" type="button" class="btn btn-primary">Das bin ich</button>
                                            </div>
                                        </div>
                                        </p>';
                                }
                            }
                        }
                    echo '</div>';
                }
            } else {
            }
        } else {
        }
    } else{
    } 
    ?>

</form>
<footer class="page-footer font-small ">
  <div class="footer-copyright text-center py-3">Entwickelt von:
    <a href="https://mdbootstrap.com/education/bootstrap/"> #Game OS | Game-OS.de Servernetzwerk</a>
  </div>
  <small><center>Entdecke
    <a href="https://gosbot.de"> GOSBot</a> - Kostenloser TeamSpeak Musikbot
  </center></small>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
function option_me_no() {
    console.log("User hat nein ausgewählt");
    document.getElementById("main_section").innerHTML = "Du hast Nein ausgewählt. Wende dich an deinen Support";
}
function option_me_yes(value_clid) {
    console.log("User hat ja ausgewählt");
    document.getElementById("main_section").innerHTML = `
    <center>
    <div class="spinner-grow" role="status">
    <span class="sr-only">Loading...</span>
    </div>
    <br>
    Warte einen Moment! Wir verifizieren dich gerade</center>
    `;
    client_verify(value_clid);
}
function client_verify(clid) {
    console.log("Funktion an clientid "+clid+" ausgelöst.");
    $.ajax({
        type: 'GET',
        url: 'libraries/client_verify.php?clid='+clid,
        success: function (data) {
            console.log(data);
                datasplit = data.split(":::");
                if (datasplit[2]==1) {
                    alert = `
                    
                    <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                        <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                        <span class="swal2-success-line-tip"></span>
                        <span class="swal2-success-line-long"></span>
                        <div class="swal2-success-ring"></div> 
                        <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                        <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                    </div>
                    <p class="h4 mb-4 text-center">`+datasplit[1]+`</p>
                    `;
                    //window.setTimeout('location.href="' + redirectsplit[1] + '"', 1000);
                } else {
                    alert = `
                    <div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>
                    <p class="h4 mb-4 text-center">`+datasplit[1]+`</p>
                    `;
                }
                document.getElementById("main_header").innerHTML = datasplit[0];
                document.getElementById("main_section").innerHTML = alert;
        },
        error: function (data) {
        },
    });
}

function client_select() {
	console.log(document.getElementById("client_select").value);
}

</script>
</html>