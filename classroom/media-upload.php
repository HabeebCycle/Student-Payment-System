<?php
$status="";
if(isset($_GET['st']) && isset($_GET['st'])!=''){
	$status=$_GET['st'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Erudite File Uploader</title>
<link rel="stylesheet" type="text/css" href="include/stylesheet.css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle">


<form action="upload_media.php" method="post"
enctype="multipart/form-data" target="upload_target" onsubmit="">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td colspan="2"><?php
    	if($status!=""){
			echo "<div class='notification_box'> ". $status ."</div>";
		}
		?></td>
  </tr>
  <tr>
    <td><label for="file"><strong> Select Media File to Display: </strong> </label>
</td>
    <td><input name="file" type="file" id="file" size="20" /><br />

	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="submit" value="Upload" class="def_btn"  /><br />
<b>Please note:</b> You can only post video, audio or image. Tell your students to switch to the media type you uploaded</td>
  </tr>
</table>




<iframe id="upload_target" name="upload_target" src="#" style="width:0px;height:0px;border:0px;"></iframe>
<center>
<div class="font11" align="center" style="bottom:0px;position:fixed; text-align:center; width:100%; background:#f8f8f8"><hr size="1" color="#CCCCCC" noshade="noshade" />Only files with below extensions allowed <b>(size &lt;5Mb)</b><br />
	 		<b>Video:</b> .mp4 &nbsp;
            <b>Images:</b> .jpg &nbsp;
            <b>Audio:</b> .mp3</div>
</center>
 <br /><br />

</form>


    </td>
  </tr>
</table>

</body>
</html>
