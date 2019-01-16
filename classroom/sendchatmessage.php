<?php
ob_start();
require_once ('../../config/config.php');
include("phpfunc.php");



//echo $_SESSION['part_id']." ".$_SESSION['roomkey'];

if(isset($_SESSION["part_id"]) && isset($_SESSION["roomkey"]) && isset($_GET["message"]) && isset($_GET["mtype"])){

$partid=$_SESSION['part_id'];
$roomkey=$_SESSION['roomkey'];
$mtype=$_GET['mtype'];
$message=htmlspecialchars($_GET['message'], ENT_QUOTES);


	if(strlen(trim($message))>0) {
		if($mtype==2){
			if(strlen(trim($message))<=255){
				$q="INSERT INTO chat_message(participant_id,room_key,message) VALUES('".$partid."','".$roomkey."','".$message."')";
				$result=mysqli_query($connection, $q) or die(mysqli_error());
			}else{
				echo "Only 255 characters allowed. \n You entered ".strlen(trim($message))." extra";
			}
		}else{
			if(strlen(trim($message))<=5600){
				$q="INSERT INTO lesson_note(participant_id,room_key,message) VALUES('".$partid."','".$roomkey."','".$message."')";
				$result=mysqli_query($connection, $q) or die(mysqli_error());
			}else{
				echo "Only 5600 characters allowed. \n You entered ".strlen(trim($message))." extra";
			}
		}
	}
	else {
		echo "Empty Message is not allow.";

	}

}
else {
	echo "Some data was misssing";

}
?>
