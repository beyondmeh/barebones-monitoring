<?php
    $host   = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "ping.php";
    $secret = bin2hex(openssl_random_pseudo_bytes(20));
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

	<?php if (! isset($_GET["hostname"]) || $_GET["hostname"] == "") { ?>
		<div class="col-md-offset-4 col-md-4">
			<form class="form-horizontal" action='' method="GET">
				<fieldset>
					<div id="legend">
						<legend class="text-center">You must first provide<br>the computer's hostname</legend>
					</div>
					
					<div class="form-group">
						<label c for="hostname">Hostname:</label>
						<div class="controls">
							<input type="text" id="hostname" name="hostname" placeholder="" class="form-control input-xlarge ">
						</div>
					</div>
					
					<div class="form-group">
						<div class="controls">
							<button class="btn btn-success">Set Name</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	<?php } else { ?>

<h3>Save on local machine: <em>client.sh</em></h3>
<pre>
#!/bin/bash

HOST="<?php echo $host; ?>"
SECRET="<?php echo $secret; ?>"

HOSTNAME=$(hostname)
TIMESTAMP=$(echo "$(date +%s) / 30" | bc | cut -f1 -d".")
TOKEN=$(echo -n $TIMESTAMP | openssl dgst -sha1 -hmac "$SECRET" | awk '{print $2}')

echo "HOST:    ${HOSTNAME}"
echo "MTIME:   ${TIMESTAMP}" 
echo "TOKEN:   ${TOKEN}"
echo "---------------------"
echo "CONNECT: $HOST"
echo -n "STATUS:  "
curl "${HOST}?id=${HOSTNAME}&token=${TOKEN}"
</pre>

<h3>Save on server: <em><?php echo realpath(dirname(__FILE__)); ?>/db/<?php echo $_GET['hostname']; ?>.json</em></h3>
<pre>
{"name":"<?php echo $_GET['hostname']; ?>","secret":"<?php echo $secret; ?>","lastseen":0,"interval":900}
</pre>
		
	<?php } ?>

    </div> <!-- /container -->

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
