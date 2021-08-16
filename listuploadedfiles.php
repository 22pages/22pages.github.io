<?php 
session_start();
?>
<!doctype html>
<html>
<head>
</head>
<body>

<?php 

if ($_SESSION["authorization"] == 'OK') {
	
include '../settings.php';

$numberoffiles = 0; 
$dir = '../../'.$uploaddir.'/';
if ($handle = opendir($dir)) {
while (($file = readdir($handle)) !== false){
if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
$numberoffiles++;
}
}

if ($numberoffiles > 0){
 
function newest($a, $b) { return filemtime($b) - filemtime($a); } 
$dir = glob('../../'.$uploaddir.'/*');

uasort($dir, "newest");

foreach($dir as $file) 
{ 
print '
<div class="cms_uploaded_file">
<img src="'.$uploaddir.basename($file).'" width="50px"> </img>
<a href="'.$uploaddir.basename($file).'" target="_blank">'.basename($file).'</a>
<div class="cms_uploaded_file_menu">
<a style="text-align: right;" href="crop.php?image=uploads/'.basename($file).'"> <i class="fa fa-pencil-square-o"></i></a>
<div class="delete"><i class="fa fa-trash-o"></i></div>
<div class="cancel"><i class="fa fa-times"></i></div>
<div class="ok"><i class="fa fa-check"></i></div>
</div>
</div>';
}
}


}

?>

</body>
</html>