<?php

if ( isset($_GET["id"]) && preg_match("/^[a-zA-Z0-9]+$/", $_GET['id']) == 1 &&
     isset($_GET["token"]) && strlen($_GET["token"]) == 40 && preg_match("/^[a-z0-9]+$/", $_GET["token"]) == 1
   ) {

    $file = "db/" . $_GET['id'] . ".json";
    if (file_exists($file)) {
        if (filesize($file) == 0) {
        }
        else {
            $computer = json_decode(file_get_contents($file), true);
        }
    }
    else {
        die("ERROR: no such identity");
    }    
    
    
    $timestamp = strstr((time() / 30), '.', true);
    $hash = hash_hmac("sha1", $timestamp, $computer['secret']);
    
    if (isset($computer["lasttoken"]) && $_GET["token"] == $computer["lasttoken"]) {
        die("ERROR: token already used");
    }
    else if ($hash != $_GET["token"]) {
         die("ERROR: invalid token");
    }
    else {
        $computer["lastseen"] = time();
        $computer["lasttoken"] = $hash; 
        $computer["ip"] = $_SERVER['REMOTE_ADDR'];
        
        file_put_contents($file, json_encode($computer));
        die("OK");
    } 
}
else {
	die("ERROR");
}

?>
