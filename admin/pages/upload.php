<?php
if(is_array($_FILES)) {
if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
$sourcePath = $_FILES['userImage']['tmp_name'];
$filename = $_POST['name'];
$width = $_POST['w'];
if (isset ($_POST['h'])){$height = $_POST['h'];}
$targetPath = "../../images/".$filename.".jpg";
if(move_uploaded_file($sourcePath,$targetPath)) {
?>
<img src="<?php echo $targetPath . "?=" . filemtime($targetPath); ?>" width="<?php echo $width;?>px" <?php if (isset($height)){echo "height=" . $height;}?>px" />
<?php
}
}
}
?>