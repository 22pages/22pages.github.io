<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST['password']))
{
    $_SESSION['login_state'] = password_verify($_POST['password'], '$2y$10$jQpSuHFz0fVsplnfpDN99OewCIH3YENGNO2wXaVoNslmKWdIh9hyS');
}

if (isset($_SESSION['login_state']) && $_SESSION['login_state'] === TRUE) {
    header('Location: crop.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<style>
  body {
		
	  display: -ms-flexbox !important;
  display: flex !important;	
  -ms-flex-align: center !important;
  align-items: center !important;
  -ms-flex-pack: center !important;
  justify-content: center !important;

  }
</style>

</head>

<body>

<div>
    	<h1>Bilder-Manager</h1>
    	
    	<?php if (isset($_SESSION['login_state']) && $_SESSION['login_state'] === FALSE) { ?>
    	    <div class="alert alert-danger" role="alert">
    	        Das Passwort ist leider falsch. Bitte versuchen Sie es erneut.
    	    </div>
    	<?php } ?>
    	
    	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST">
    	
    	<div class="form-group pt-2">
    	    <label for="pwd">Passwort:</label>
    	    <input type="password" class="form-control" name="password" id="pwd" required>
    	</div>
    	<div style="text-align: center;">
    	    <br/><input class="btn btn-success" type="submit" value="Login" >
    	</div>
    	</form>
    </div>
</body>
</html>
