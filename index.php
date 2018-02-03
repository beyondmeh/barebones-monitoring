<?php

session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $conf = parse_ini_file("db/config.ini");

    if ($_POST["username"] == $conf["username"] && password_verify($_POST["password"], $conf["password"])) {
        $_SESSION["login"] = true;
    }
    else {
        $_SESSION["error"] = "authentication error";
    }
}

if (isset($_GET["logout"]) || isset($_POST["logout"])) {
    $_SESSION["login"] = false;
    session_destroy();
}


function time_diff( $ptime ) {
    $estimate_time = abs(time() - $ptime);

    if( $estimate_time < 1 ) {
        return '1 second';
    }

    $condition = array( 
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str ) {
        $d = $estimate_time / $secs;

        if( $d >= 1 ) {
            $r = round( $d );
            return $r . ' ' . $str . ( $r > 1 ? 's' : '' );
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Network Computer Status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
  <div class="container">
      
      
<?php if (! $_SESSION["login"]) { ?>
    <div class="col-md-offset-4 col-md-4">
        <form class="form-horizontal" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
          <fieldset>
            <div id="legend">
              <legend class="">Login</legend>
            </div>

            <?php if (isset($_SESSION["error"])) { ?>
                <div class="alert alert-danger">
                    <strong>Error!</strong> <?php echo $_SESSION["error"]; ?>
                </div>
            <?php 
                }
                unset($_SESSION["error"]); 
            ?>

            <div class="form-group">
              <label c for="username">Username</label>
              <div class="controls">
                <input type="text" id="username" name="username" placeholder="" class="form-control input-xlarge ">
              </div>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <div class="controls">
                <input type="password" id="password" name="password" placeholder="" class="form-control input-xlarge">
              </div>
            </div>
            <div class="form-group">
              <div class="controls">
                <button class="btn btn-success">Login</button>
              </div>
            </div>
          </fieldset>
        </form>
    </div>
      
<?php } else { ?>

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Network Computer Status</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="new_system.php">Add Device Helper</a></li>
          <li><a href="new_password.php">Password Helper</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $PHP_SELF . "?logout"; ?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>


    <h1>Computers</h1>
    <table class="table table-striped table-hover">
        <thead>
            <th>Name</th>
            <th>IP</th>
            <th>Status</th>
            <th>Last Seen</th>
        </thead>
            
        <?php
            $files = scandir('db/');
            foreach($files as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                if ($ext == "json") {
                    $computer = json_decode(file_get_contents('db/' . $file), true);
               
                    echo "<tr>";
                    echo "<td>" . $computer["name"] . "</td>";
                    
                    if ($computer["lastseen"] == 0) {
                        echo "<td>&mdash;</td>";
                        echo "<td class=\"text-info\"><em>new computer</em></td>";
                        echo "<td>&mdash;</td>";
                    }
                    else {
                        
                        echo "<td>" . $computer["ip"] . "</td>";
                       
                        if (time() > $computer["lastseen"] + $computer["interval"] + 600) { // interval + 10 minutes
                            echo "<td><time class=\"text-danger\" datetime=\"" . date("Y-m-d\TH:i:s", $computer["lastseen"]) . "\" title=\"last seen " . time_diff($computer["lastseen"]) . " ago\">offline</time></td>";
                        }
                        else if (time() > $computer["lastseen"] + $computer["interval"]) {
                            echo "<td><time class=\"text-warning\" datetime=\"" . date("Y-m-d\TH:i:s", $computer["lastseen"]) . "\" title=\"last seen " . time_diff($computer["lastseen"]) . " ago\">overdue</time></td>";
                        }
                        else {
                             echo "<td><time class=\"text-success\" datetime=\"" . date("Y-m-d\TH:i:s", $computer["lastseen"]) . "\" title=\"next check in " . time_diff($computer["lastseen"] + $computer["interval"]) . "\">online</time></td>";
                        }
                
                        echo "<td><time datetime=\"" . date("Y-m-d\TH:i:s", $computer["lastseen"]) . "\">" . time_diff($computer["lastseen"]) . " ago</time></td>";
                    }
                    echo "</tr>";
                }
            }
        ?>
        </table>
      </div>
<?php } ?>
    </div> <!-- /container -->

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
