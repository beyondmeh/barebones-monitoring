<?php
    $host   = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
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

	<?php if (! isset($_POST["password"]) || $_POST["password"] == "" || $_POST["password"] != $_POST["repeat_password"]) { ?>
		<div class="col-md-offset-4 col-md-4">
			<form class="form-horizontal" action='' method="POST">
				<fieldset>
					<div id="legend">
						<legend class="text-center">Provide the new password</legend>
					</div>

					<?php if ($_POST["password"] != $_POST["repeat_password"]) { ?>
						<div class="alert alert-danger">
							<strong>Error!</strong> Passwords did not match.
						</div>
					<?php } ?>
								

					<div class="form-group">
						<label c for="password">Password:</label>
						<div class="controls">
							<input type="password" id="password" name="password" placeholder="" class="form-control input-xlarge">
						</div>
					</div>

					<div class="form-group">
						<label c for="repeat_password">Repeat Password:</label>
						<div class="controls">
							<input type="password" id="repeat_password" name="repeat_password" placeholder="" class="form-control input-xlarge">
						</div>
					</div>
					
					<div class="form-group">
						<div class="controls">
							<button class="btn btn-success">Set Password</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	<?php } else { ?>

		<h3>Save to: <em>db/config.ini</em></h3>
<pre>
[login]
username = admin
password = <?php echo password_hash($_POST["password"], PASSWORD_DEFAULT); ?>
</pre>
		
	<?php } ?>

    </div> <!-- /container -->

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
