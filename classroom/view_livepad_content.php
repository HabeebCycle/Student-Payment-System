<?php
require_once ('../../config/config.php');
if(isset($_GET['notepad_text'])){
$content=htmlspecialchars($_GET['notepad_text'], ENT_QUOTES);
$partid=$_GET['prt_id'];
if(strlen(trim($content))>0){
	$q="update participant_details set prt_noteText = '$content' where participant_id=$partid";
	$result=mysqli_query($connection, $q) or die(mysqli_error());
	echo "Note saved successfully";
}else{
	echo "Error occured! No text found";
}
//echo "<pre>".$content."</pre>";
}else{
	echo "Error occured! No text found";
}
?>
