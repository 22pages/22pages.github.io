<!-- Prüfen, ob der User bei COAST CMS autorisiert ist -->
<?php
session_start();

if ( ! isset($_SESSION["authorization"]) || $_SESSION["authorization"] != 'OK') {
    die('403 Forbidden');
}
if (empty($_GET['image'])) die("Parameter image fehlt") ;

$image = $_GET['image'];

if ( ! file_exists($image)) die("Bild wurde im Filesystem nicht gefunden");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ! empty($_POST['image-data']))
{
    list($mime, $base64_string) = explode(';', $_POST['image-data']);
    list($encoding, $base64_payload) = explode(',', $base64_string);
    file_put_contents($image, base64_decode($base64_payload));
}

list($width, $height, $type) = getimagesize($image);

?>

<!DOCTYPE html>
<html>
<head>

<title></title>

<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  
<script src="js/jquery.min.js"></script>
<script src="js/jquery.cropit.js"></script>
<link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet" />  

<style>
  body {
    margin-top: 50px;
    margin-left: 100px;
    font-family: Hind;
  }
.cropit-preview {
    background-color: #ccc;
    background-size: cover;
    border: 1px solid #ccc;
    border-radius: 3px;
    width: <?php echo $width; ?>px;
    height: <?php echo $height; ?>px;
  }

.cropit-preview-image-container {
    cursor: move;
  }
.cropit-preview-background {
    opacity: .1;
    cursor: auto;
  }
</style>

</head>

<body>
 <a href="cms.php?currentfile=index.html"><i class="fa fa-arrow-left" aria-hidden="true"></i> Beenden und zurück zur Homepage</a>
<br><br><br><br>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST">
<div class="image-editor">
    <div class="cropit-preview"></div>
    <br>
    <large>Bitte laden Sie ein neues Bild in den Editor<br>Mindestmaße: <?php echo $width; ?> x <?php echo $height; ?>px</large>
    <br>
    <input type="file" class="cropit-image-input">
    <br>
    <br>
    <div class="image-size-label">Zoomen</div>
    <input type="range" class="cropit-image-zoom-input">
    <input type="hidden" name="image-data" class="hidden-image-data">
    <br><br>
    <button type="submit">Speichern</button>
    <br><br>
</div>
</form>
  
<!-- Hinzugefügt -->
<p>Wenn Sie noch ein weiteres Bild ersetzen möchten, wählen Sie es mit "Bearbeiten" aus.</p>
<hr>

<?php foreach (glob('uploads/*.*') as $file) { ?>

<!-- Schleife mit vorhandenen Bildern und Links -->

<img width="80" src="<?php echo $file; ?>?random=<?php echo time(); ?>">
<br>
<small>Bildname: <?php echo basename($file); ?></small>
<br>
<a href="crop.php?image=<?php echo $file; ?>">Bearbeiten</a>
<hr>

<?php } ?>

<!-- Ende Hinzugefügt -->
  
<script>
$(function() {
 
    $('.image-editor').cropit({      
        imageState: {
            src: '<?php echo $image; ?>?random=<?php echo time(); ?>',
        },
    });
// Hier die Bildqualität einstellen
    $('form').submit(function() {
        var imageData = $('.image-editor').cropit('export', {type: 'image/jpeg', quality: .8, originalSize: false});
        $('.hidden-image-data').val(imageData);
    });
    
 
});

</script>

</body>
</html>
