<?php
/*session_start();

if ( ! isset($_SESSION['login_state']) || $_SESSION['login_state'] !== TRUE) {
    header('Location: crop_login.php');
    exit;
}
   
if (isset($_GET['logout']) || $_GET['logout'] === 'true') {
    $_SESSION = array();
    session_destroy();
    header('Location: crop_login.php');
    exit;
}*/

$images = glob('img/*.*');


if (empty($_GET['image'])) $_GET['image'] = $images[0];
$image = $_GET['image'];
if ( ! file_exists($image)) die("Bild wurde im Filesystem nicht gefunden");

list($width, $height, $type) = getimagesize($image);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST['image-data']))
{
    list($mime, $base64_string) = explode(';', $_POST['image-data']);
    list($encoding, $base64_payload) = explode(',', $base64_string);
    file_put_contents($image, base64_decode($base64_payload));
}

?>

<!DOCTYPE html>
<html>
<head>

<title></title>

<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://hub.22pages.com/cdn/css/bootstrap.431.min.css" rel="stylesheet">
	<script src="https://hub.22pages.com/cdn/js/jquery-3.3.1.min.js"></script>
<script src="jquery.cropit.js"></script>
<link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet" />  

<style>
  body {
    margin-top: 50px;
    margin-left: 100px;
    font-family: Hind;
  }
	
/* === Crop Image ===*/	
.cropit-preview {
    background-color: #ccc;
    background-size: cover;
/*    border: 1px solid #ccc;*/
    border-radius: 3px;
    width: <?php echo $width; ?>px;
    height: <?php echo $height; ?>px;
  }

.cropit-preview-image-container {
    cursor: move;
box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
  }
.cropit-preview-background {
    opacity: .1;
    cursor: auto;
  }
.error-image {
		display: none;
		color: red;
}  

/* Gray out zoom slider when the image cannot be zoomed */
	.cropit-image-zoom-input[disabled] {
  opacity: .2;
}  
/* Show move cursor when image has been loaded */
	.cropit-preview.cropit-image-loaded .cropit-preview-image-container {
  cursor: move;
}	
input[type="range"] {
    width: 350px;
}	
</style>

</head>

<body>
    
<!--<a href="crop.php?logout=true" role="button" class="btn btn-outline-primary mb-5">&#10094;&ensp;Beenden und zurück zum Login</a>-->

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<h1 class="py-3">Bilder-Manager</h1>
		</div>
<div class="col-12 ">
					<div>
					<?php if ( ! empty($_GET['image'])) { ?>
					</div>    
					<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST">
					
						<div class="image-editor">
							<div class="cropit-preview"></div>
							<div class="image-size-label py-2">Bild zoomen</div>
							<input type="range" class="cropit-image-zoom-input custom-range pb-5">
					
<div class="border p-4">
								<p>1. Bitte wählen Sie unten das Bild aus, das Sie gern ersetzen möchten.</p>
								<p>2. Laden Sie dann ein neues Bild hoch. Das Bild muss die folgenden Mindestmaße haben:
									<strong><?php echo $width; ?> x <?php echo $height; ?> px</strong></p>
								<div class="alert alert-danger error-image" role="alert">
									<p>Das Bild ist leider zu klein. Erforderliche Mindestmaße: <strong>
										<?php echo $width; ?> x
										<?php echo $height; ?> px</strong></p>
								</div>
													
								<label class="btn btn-outline-primary my-2">
													    Neues Bild auswählen <input type="file" class="cropit-image-input" hidden>
								</label>
								<input type="hidden" name="image-data" class="hidden-image-data">
								<p class="pt-3">3. Wählen Sie den gewünschten Zoomfaktor und den Bildausschnitt durch verschieben mit der Maus.</p>
								<p>4. Speichern Sie dann das neue Bild.<br><br>Bitte beachten Sie, dass dadurch das alte Bild <strong>gelöscht</strong> wird. Um das alte Bild herunterzuladen <a href="<?php echo $image; ?>" download="<?php echo $image; ?>">hier</a> klicken.</p>
								<button class="btn btn-primary" type="submit">Neues Bild Speichern</button>
							</div>
						</div>
					</form>
					

		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
<div class="col-12">
				<h3 class="py-5">Liste der vorhandenen Bilder</h3>
			</div>
		
		<?php } ?>

		<?php foreach ($images as $file) { 
		list($xwidth, $xheight) = getimagesize($file);
		?>

		<!-- Schleife mit vorhandenen Bildern und Links -->

<div class="col border m-3">
			<img width="100" src="<?php echo $file; ?>?<?php echo filemtime($file); ?>" class="shadow m-3">
			<a href="crop.php?image=<?php echo $file; ?>" role="button" class="btn btn-sm btn-outline-primary ml-3">Auswählen</a>
			
			<br /><br /><small>Bildname:&emsp;<?php echo basename($file); ?></small>
			<br /><small>Größe:&emsp;<?php echo $xwidth; ?> x
				<?php echo $xheight; ?> px</small>
		</div>

		<?php } ?>
	</div>
</div>
    
<script>
$(function() {
 
    $('.image-editor').cropit({      
        imageState: {
            src: '<?php echo $image; ?>?<?php echo filemtime($image); ?>',
        },
        onImageError: function() {
            $(".error-image").show();
        },
        onFileChange: function() {
            $(".error-image").hide();
        },
			allowDragNDrop: false,
			
    });

    $('form').submit(function() {
        var imageData = $('.image-editor').cropit('export',{
					type: 'image/jpeg', 
					quality: 1, 
					originalSize: false
				});
        $('.hidden-image-data').val(imageData);
    });
});

</script>

</body>
</html>
