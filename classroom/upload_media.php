<?php
ob_start();
require_once ('../../config/config.php');
include("phpfunc.php");

$output = "";
$filename="";
$filetype="";
$filesize="";
$roomkey=$_SESSION['roomkey'];
$partid=$_SESSION['part_id'];

$target_dir="res/media/";
$uploadDir="users/documents/";
$fileurl="classroom/".$uploadDir;

if (($_FILES["file"]["type"] == "video/mp4")
|| ($_FILES["file"]["type"] == "audio/mpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/jpeg")

&& ($_FILES["file"]["size"] < 5048576))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    $output= "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
		$filename=$_FILES["file"]["name"];
		$filetype=$_FILES["file"]["type"];
		$filesize=round(($_FILES["file"]["size"]/1024),2);
		$restype = "video";
		if($filetype=="image/jpeg" || $filetype=="image/jpeg"){
			$restype = "image";
		}elseif($filetype=="audio/mpeg"){
			$restype = "audio";
		}else{
			$restype = "video";
		}

    echo "File <b>" . $filename . "</b> ";
  //  echo "Type: " . $filetype . "<br />";
    echo " (" . $filesize . " Kb)";
  //  echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
	$target_file = basename($_FILES["file"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$fileNamer = $restype.$roomkey.'.'.$imageFileType;
	$uploadFilename = $target_dir.$fileNamer;
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFilename)) {
      move_uploaded_file($_FILES["file"]["tmp_name"],$uploadDir . $filename);
	  $fileurl.=$filename;
	  $q="INSERT INTO sharedfiles(file_name,file_size,file_type,file_url,roomkey,participant_id)
	  VALUES('$filename','$filesize','$filetype','$fileurl','$roomkey','$partid')";
	  $result=mysqli_query($connection, $q) or die("Query failed. ".+mysqli_error());
	  if($result){
	      $output= "File <b>" . $filename . "</b> uploaded successfully.";
	  }
	  else {
		  $output= "There was some problem in storing the file on the server. Please go back and try again.";
	  }
      }
    }
  }
else
  {
	$output= "Invalid file type or file exceeds size limit of 5Mb.";
  }
header("location: media-upload.php?st=".$output);

?>
