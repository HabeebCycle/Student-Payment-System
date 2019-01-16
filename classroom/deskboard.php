<?php
require_once ('../../config/config.php');
if(!isset($_SESSION['islog']) and !$_SESSION['islog']){
	header('Location: ../../');
}
ob_start();

include("phpfunc.php");
$roomkey="";
$partid="";
$title="";
$mod_name="";
$agenda="";
$mod_id="";
$name="";
$foto="";
$startDT="";
$email="";
include("deskboard-func.php");


/////////////////////////////////////////////////////function get room details
if(isset($_GET['roomkey']) )//&& $_SESSION['roomkey']==$_GET['roomkey'])
{
	@$roomkey=$_GET['roomkey'];
	@$partid=$_SESSION['part_id'];

	$q="SELECT * FROM lessons WHERE id LIKE $roomkey and duration=0";
	$result=mysqli_query($connection, $q) or die("Query failed. Error resolving roomkey. -".mysqli_error());
	$row=mysqli_fetch_array($result);
	if(mysqli_num_rows($result)==1)
	{
		$roomurl="class/".$roomkey;
		$title=$row['title'];
		$agenda=$row['agenda'];
		$mod_id=$row['teacher'];
		$startDT=time()-$row['start'];
		$mod_name=mysqli_fetch_array(mysqli_query($connection,"select name from admin where id=$mod_id"))['name'];
	}
	else
	{
		echo "<script>alert('Classroom has been closed');</script>";
		if (isset($_SESSION['participant_logged_in'])) {
			unset($_SESSION['participant_logged_in']);
			unset($_SESSION['part_id']);
			unset($_SESSION['roomkey']);
			unset($_SESSION['is_user']);
			unset($_SESSION['isAdmin']);
			unset($_SESSION['room_is_open']);
		}
		if(isset($_SESSION['is_user'])){
			header ("location: ../../teach/poruse.php");
			exit;
		}else{
			header ("location: ../../student/poruse.php");
			exit;
		}
	}

////////////////////////////////// function get participant details
	if(isset($_SESSION['is_user'])){
		$ins = mysqli_query($connection, "update lessons set start=".time()." where start=0 and id=$roomkey");
	}
	$q="SELECT * FROM participant_details WHERE participant_id LIKE '$partid'";
	$result=mysqli_query($connection,$q) or die("Query failed. Error resolving roomkey. -".mysqli_error());
	$row=mysqli_fetch_array($result);
	if(mysqli_num_rows($result)==1)
	{
		$typ = $row['prt_type']==1?"admin":"student"; //1 - moderator; 2 - student
		$res = mysqli_fetch_array(mysqli_query($connection, "select * from $typ where id=".$row['user_id']));
		$name=$row['prt_name'];
		$ifmod=$row['user_id'];
		$enterDT=$row['prt_enter_dt'];
		if($row['prt_type']==1){$foto="../../images/moderator-24.png"; $name="$name";}
			else if($res['gender']==2){$foto="../../images/female-24.png";}
			else {$foto="../../images/male-24.png";}
		$email=trim($res['email']);
		$noteText = $row['prt_noteText'];
		//$geoLoc=explode(',',$row['prt_geo_location']);
		//$lat=trim($geoLoc[0]);
		//$long=trim($geoLoc[1]);
	}
	else
	{
		echo "<br>Error fetching participant details<br>";echo $partid;
		exit;
	}
////////////////////////////////////

}
else
{
	echo "You are not logged in this room. Please enter valid room key you want to enter.";
	exit;
	//header("location: index.php");
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title; ?> | Erudite</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="../../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../../build/css/custom.min.css" rel="stylesheet">
	<style type="text/css"><!--
		#file_sharer {text-align:center; background:#f8f8f8; text-align:left; max-width:300px;  -moz-border-radius-topleft: 10px; -webkit-border-top-left-radius: 10px; border-top-left-radius:10px;}
		#file_sharer .title,#file_sharer .title a {display:block; background:#3D63D9; color:#fff; padding:3px 5px; -moz-border-radius-topleft: 10px; -webkit-border-top-left-radius: 10px; border-top-left-radius:10px;}
		#file_sharer:hover .title,#file_sharer:hover .title a {background:#; color:#;}
		#file_sharer #shared_files_block {border-left:1px solid #ccc; background:#FFF;	max-height:200px; overflow:auto;}

		#file_sharer #file_explorer table td {
			padding-left:5px;
			border:0px;
			/*border-bottom:1px solid #eee; */

		}
		#file_sharer #file_explorer table tr:hover td {
			background:#E9F5FC;
		}
		#contentPane {
			border:1px solid #ccc;
			border-width:0px 1px 1px 1px;
			background:#fff;
			-moz-box-shadow: 0px 0px 5px #666;
			-webkit-box-shadow: 0px 0px 5px #666;
			box-shadow: 0px 0px 5px #666;
		}
		.pad5 {padding:5px;}
		.pad10 {padding:10px;}
		.padl5 {padding-left:5px;}
		.padl20 {padding-left:2px;}
		.padt3 {padding-top:3px;}
		.padt5 {padding-top:5px;}
		.padb10 {padding-bottom:10px;}
		.padr10 {padding-right:10px;}
		.mar5 {margin:5px;}
		.mar10 {margin:10px;}
		.marl10 {margin-left:10px;}
		.mart5 {margin-top:5px;}
		.mart10 {margin-top:10px;}
		.mart10 {margin-top:10px;}
		.marb10 {margin-bottom:10px;}
		.mart-40 {margin-top:-40px;}
		.colorBlue {color:#3d63d9;}
		.colorRed {color:#ff0000;}
		.colorGray {color:#999;}
		.bgyellow {background:#FFFEF2;}
		.font11 {font-size:11px;}
		.font12 {font-size:12px;}
		.display_none {display:none;}

		.lh18 {line-height:16px;}
		.floatl {float:left;}
		.floatr {float:right;}
		.w100p {width:100%;}
		.w800 {width:800px;}
		.w200 {width:200px;}
		.w232 {width:232px; max-width:250px;}
		.w385 {width:385px; max-width:385px;}
		.w98p {width:98%;}
		.w986 {width:986px;}
		.w95p {width:95%;}
		.h100p {height:100%;}

		.header_right_links {
			text-align:right;
			line-height:16px;
			margin-top:0px;
			position:relative;
		}
		#lesson_chat {
			height:170px;
			text-align:justify;
			padding-right:2px;
			margin-bottom:0px;
			padding-bottom:0px;
			overflow:auto;
			white-space: -moz-pre-wrap
			font-size:12px;
			line-height:11px;
			font-family:calibri, times new roman;
			z-index:100;
			padding-top:0px;
			margin-top:0px;
		}
		.les_block{
			height:180px;
			overflow:hidden;
			background:#2ef;
			border:2px solid #222;
			margin-top:5px;
			margin:0px; padding:0px;
			-moz-border-radius:50px; -webkit-border-radius:50px; border-radius:10px;
			-moz-box-shadow:3px 2px 15px #333; -webkit-box-shadow:2px 2px 15px #333; box-shadow:2px 2px 15px #333;
		}
		.les_blocky{
			height:190px;
			overflow:hidden;
			background:#2ef;
			border:2px solid #222;
			margin-top:5px;
			margin-bottom:5px;
			margin:0px; padding:0px;
			-moz-border-radius:50px; -webkit-border-radius:50px; border-radius:10px;
			-moz-box-shadow:3px 2px 15px #333; -webkit-box-shadow:2px 2px 15px #333; box-shadow:2px 2px 15px #333;
		}
		.les_blockz{
			height:80px;
			overflow:hidden;
			background:#2ef;
			border:2px solid #222;
			margin-top:5px;
			margin-bottom:5px;
			margin:0px; padding:0px;
			-moz-border-radius:50px; -webkit-border-radius:50px; border-radius:10px;
			-moz-box-shadow:3px 2px 15px #333; -webkit-box-shadow:2px 2px 15px #333; box-shadow:2px 2px 15px #333;
		}
		.les_blocka{
			height:190px;
			overflow:hidden;
			background:#2ef;
			border:2px solid #222;
			margin-top:5px;
			margin-bottom:5px;
			margin:0px; padding:0px;
			-moz-border-radius:50px; -webkit-border-radius:50px; border-radius:10px;
			-moz-box-shadow:3px 2px 15px #333; -webkit-box-shadow:2px 2px 15px #333; box-shadow:2px 2px 15px #333;
		}
		.panel_white_round_ {
			padding:10px;
			background:#fff;
			border:1px solid #ccc;
		}
		.panel_white_round_:hover {
			border:1px solid #ccc;
			background:#fff;
			border:1px solid #ddd;
			-moz-box-shadow:2px 2px 5px #ccc; -webkit-box-shadow:2px 2px 5px #ccc;
			-moz-border-radius:10px; -webkit-border-radius:10px; border-radius:10px;
		}
		.participants_scrollPane {
			overflow: auto;
			height:200px;
		}

		#discussion_block {
			border:1px solid #ccc;
			background:#fff;
			padding:0px;
			height:214px;
			-moz-box-shadow:1px 2px 10px #ccc; -webkit-box-shadow:1px 2px 10px #ccc; box-shadow:1px 2px 10px #ccc;
		}
		#discussion_chat {
			height:170px;
			text-align:left;
			margin-bottom:0px;
			padding-bottom:0px;
			overflow:auto;
			line-height:18px;
			z-index:100;
			padding-top:0px;
			margin-top:0px;
		}
		#discussion_chat ul {display:inline; list-style:none; list-style-type:none; margin:0px;}
		#discussion_chat ul li {padding:2px 0px; vertical-align:bottom; position:relative; display:block; margin:0px; white-space:nowrap;}
		#discussion_chat ul li .msgTime {text-align:right; color:#fff; right:0px; background:#333; padding:0px 5px; display:none; position:absolute; top:0px;  -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;}
		#discussion_chat ul li:hover {background: #f8f8f8;}
		#discussion_chat ul li:hover .msgTime {display:block;}

		#lesson_chat ul {display:inline; list-style:none; list-style-type:none; margin:0px;}
		#lesson_chat ul li {padding:2px 0px; vertical-align:bottom; position:relative; display:block; margin:0px; white-space:nowrap;}
		#lesson_chat ul li .msgTime {text-align:right; color:#fff; right:0px; background:#333; padding:0px 5px; display:none; position:absolute; top:0px;  -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;}
		#lesson_chat ul li:hover {background: #f8f8f8;}
		#lesson_chat ul li:hover .msgTime {display:block;}

		#discussion_notifier {
			color:#999;
			font-style:italic;
			text-align:right;
			display:block;
			float:right;
			margin:0px 4px 0px 4px;
			padding:2px 10px;
			background:#f8f8f8;
			z-index:1;
			width:auto;
			line-height:15px;
			max-width:445px;
			border-top:1px solid #fff;
			border-bottom:1px solid #fff;
			font-family:tahoma;
		}
		#discussion_notifier:hover {

			background:#F0EEFD;
			color:#333;
			border-top:1px solid #DFDAFA;
			border-bottom:1px solid #DFDAFA

		}
		#discussion_chat_textbox {
			height:36px;
			margin-left:0px;
			padding:2px;
			border-top:1px #ccc solid;
		}
		#chat_text_input {
			height:34px;
			border:0px;
			color:#0687c8;
			font-size:14px;
			line-height:24px;
			float:left;
			padding:2px;
			overflow:auto;
		}
		#chat_select {

			height:25px;
			border:1px;
			padding:2px;
			float:left;
			margin-top:2px;
			position:absolute;
		}
		#chat_text_send_btn {
			background:url(../images/buttons/dashboard_discussion_chat_send_.gif) top left no-repeat #00f;
			width:48px;
			height:35px;
			border:0px;
			float:right;
			margin-top:1px;
		}
		#chat_text_send_btn:hover {
			outline:thin;
			background:url(../images/buttons/dashboard_discussion_chat_send_.gif) left 50% no-repeat;
			border:0px;
		}
		#chat_text_send_btn:focus,#chat_text_send_btn:active {
			background:url(../images/buttons/dashboard_discussion_chat_send_.gif) left bottom no-repeat;
			border:0px;
		}
		#participant_chat {
			max-height:380px;
			overflow: auto;
			line-height:18px;
		}
		#chat_input_box {
			width:100%;
			height:28px;
			border:1px #ccc solid;
			margin-top:8px;
			padding:2px;
			-moz-box-shadow:0px 0px 10px #e3f2fd; -webkit-box-shadow:0px 0px 10px #e3f2fd;
		}
		#chat_input_box:hover,#chat_input_box:active {
			-moz-box-shadow:0px 0px 8px #a3d8f6; -webkit-box-shadow:0px 0px 8px #a3d8f6;
			border:1px #ddd solid;
		}
		#chat_input_box_input {

			height:23px;
			/*position:absolute;*/
			z-index:1;
			float:left;
			border:0px solid;
			vertical-align:middle;
			color:#3d63d9;
			*line-height:24px;
		}
		.chat_box_send_btn {
			width:27px;
			height:29px;
			border:0px;
			float:right;
			background:url(../images/buttons/dashboard_discussion_chat_send_.gif) left top no-repeat;
			-moz-user-select: none; -khtml-user-select: none; user-select: none;
			outline:none;
		}
		.chat_box_send_btn:active {
			outline:none;
			border:0px;
			background:url(../images/buttons/dashboard_discussion_chat_send_focus.gif) left top no-repeat;
		}
	--></style>

	<script src="../scripts/common.js"></script>
	<script type="text/javascript" src="../scripts/drag.js"></script>
	<script src="../scripts/swfobject_modified.js" type="text/javascript"></script>

	<script type="text/javascript"><!--
	function onABCommComplete() {
	  // OPTIONAL: do something here after the new data has been populated in your text area
	}


	//--></script>
	<!--[if lt IE 9]>
	<script src="../scripts/html5.js"></script>
	<![endif]-->
  </head>

  <body style="background:url(../images/dashboard_background.gif) repeat-x;font-family:calibri,tahoma,arial,verdana;
	font-size:12px;
	color:#666;
	margin:0px;
	cursor:default;">

	<input type="hidden" value="<?php echo $roomkey; ?>" id="roomkey">
	<input type="hidden" value="<?php echo $partid; ?>" id="partid">

	<div class="container">
	<div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0" id="contentPane">

	<header id="topHeader">
		<div class="floatl mart10"><?php echo $title; ?></div>
		<div class="floatr header_right_links" align="	">
		<?php echo "Welcome $name ";?> &nbsp;
		<a href="#" class="btn btn-xs btn-warning" onClick="confirmExitClass();"><b><?php echo isset($_SESSION['is_user'])?"End Class":"Leave Class"; ?></b></a></div>
	</header><br/><br/>

	<div class="pad5">
<center>
	<div align="center" class="" id="resx_les">
    	<div class="les_block col-md-12 col-sm-12 col-xs-12">
		   <div id="lesson_chat" class="pad5">
		   <!-- blah blah blah -->
           </div>
    	</div>
    </div>
	<div align="center" class="" id="resy_les">
    	<div class="les_blocky col-md-4 col-sm-4 col-xs-12">
		   <div id="lesson_video" class="pad5">
		    <video width="320" height="180" controls>
			  <source src="../res/media/video<?php echo $roomkey; ?>.mp4" type="video/mp4">
			Your browser does not support the video tag.
			</video>
		   </div>
    	</div>
    </div>
	<div align="center" class="" id="resz_les">
    	<div class="les_blockz col-md-4 col-sm-4 col-xs-12">
		   <div id="lesson_audio" class="pad5">
			<p style="text-align:center;"><b>AUDIO BOARD</b></p>
		     <audio controls>
			  <source src="../res/media/audio<?php echo $roomkey; ?>.mp3" type="audio/mpeg">
			  Your browser does not support the audio element.
			 </audio>
		   </div>
    	</div>
    </div>
	<div align="center" class="" id="resa_les">
    	<div class="les_blocka col-md-4 col-sm-4 col-xs-12">
		   <div id="lesson_image" class="pad5">
		     <img  src="../res/media/image<?php echo $roomkey; ?>.jpg" alt="No Image Upload" style="width:280px;height:180px">
		   </div>
    	</div>
    </div>
	</center><br clear="all"><br clear="all">
	<div class="">
		<div id="discussion_block" class="col-md-12 col-sm-12 col-xs-12">
			<div id="discussion_chat" class="pad5">
			</div>

			<div id="discussion_chat_textbox" class="col-md-12 col-sm-12 col-xs-12">
				<form action="" method="get" onSubmit="send(); return false;" >
					<input type="text" id="chat_text_input" class="col-md-10 col-sm-10 col-xs-10" value="Type your message here..." maxlength="255" onClick="javascript: this.value=='Type your message here...'?this.value='':this.value=this.value;"/><input id="chat_text_send_btn" class="col-md-2 col-sm-2 col-xs-2" type="button" value="" onClick="send();"/>
				</form>
			</div>
        </div>


	</div>
	<br clear="all">
		<?php if(isset($_SESSION['is_user'])){ ?>
		<div id="chat_select" class="col-md-12 col-sm-12 col-xs-12">
			<input type="radio" name="nchat" value="1" onClick="select_item(this.value);" checked>&nbsp; Post to Lesson Board &nbsp;&nbsp;
			<input type="radio" name="nchat" value="2" onClick="select_item(this.value);">&nbsp; Post to Discussion &nbsp;&nbsp;
			<button class="btn btn-primary btn-sm fa fa-upload" data-toggle="modal" data-target=".mediapost"> Post Media</button>
			<!--<input type="checkbox" id="didatic" name="didatic" value="didatic" onclick="toggle_dispBox(this);"> &nbsp; Switch Display -->
		</div><br/>
		<input type="hidden" id="select_input" value="1">
		<?php }else{ ?>
		<input type="hidden" id="select_input" value="2">
		<?php } ?>
		<br clear="all">
		<div id="display_select" class="col-md-12 col-sm-12 col-xs-12">
			<input type="radio" name="disp" value="1" onClick="select_disp(this.value);" checked>&nbsp; Lesson Board &nbsp;&nbsp;
			<input type="radio" name="disp" value="2" onClick="select_disp(this.value);">&nbsp;Video &nbsp;&nbsp;
			<input type="radio" name="disp" value="3" onClick="select_disp(this.value);">&nbsp;Audio &nbsp;&nbsp;
			<input type="radio" name="disp" value="4" onClick="select_disp(this.value);">&nbsp;Image &nbsp;&nbsp;
		</div>
	<br clear="all"><br clear="all">
	<div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
	<center>
	<button class="btn btn-primary btn-sm fa fa-users" data-toggle="modal" data-target=".participants"> Participants</button>
	<button class="btn btn-warning btn-sm fa fa-info-circle" data-toggle="modal" data-target=".less_det"> Details</button>
	<button class="btn btn-success btn-sm fa fa-cogs" data-toggle="modal" data-target=".chat_tools"> Tools</button>
	<button class="btn btn-info btn-sm fa fa-cloud-download" data-toggle="modal" data-target=".less_res"> Resources</button>
	</center>

	<div class="modal fade mediapost" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-users"></b> Post Media Resources</h4>
				</div>

				<div class="modal-body">
					<iframe id="upload_target" name="upload_target" align="middle" src="../media-upload.php" style="width:95%;height:250px;border:0px; font-family:tahoma; overflow:auto;"  frameborder="0" allowtransparency="true"></iframe>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade participants" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-users"></b> Participants</h4>
				</div>

				<div class="modal-body">
					<h5></h5>
					<div class="participants_scrollPane panel_white_round_" id="participants_scrollPane">
						lllllll
					</div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade less_det" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-info-circle"></b> Lesson Details</h4>
				</div>

				<div class="modal-body">
					<h5></h5>
					<div class="panel_white_round_" id="start_time" title="<?php echo $title; ?>">
					<!-- blah blah blah -->
					</div><br/><b>Facilitator:</b><br/> <?php echo $mod_name; ?>
					<br/><b>AGENDA</b><br/><textarea style="width:99%; height:150px;" disabled><?php echo $agenda; ?></textarea>
					</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade chat_tools" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-cogs"></b> Lesson Tools</h4>
				</div>

				<div class="modal-body">
					<h5></h5>
					<div class="panel_white_round_ " title="Tools"><img src="../images/tools_icon.gif" width="14" height="14" alt="Tools"> <b>Tools:</b><br/><br/> <a href="#" class="tool_btn_w26" title="Live Notepad" data-toggle="modal" data-target=".notetext"><img src="../images/notepad_icon.gif" alt="Notepad" width="16" height="16" border="0"><b> Notepad</b></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#" class="tool_btn_w26" title="File Explorer" data-toggle="modal" data-target=".less_res"><img src="../images/files_icon.gif" alt="File Explorer" width="16" height="16" border="0"> <b>File/Resources Explorer</b></a><br/><br/>
				<?php if(isset($_SESSION['is_user'])){ ?>
                <a href="#" class="tool_btn_w26" title="Share a document" data-toggle="modal" data-target=".fileupload"><img src="../images/upload_icon.png" alt="Share a document" width="16" height="16" border="0"> <b>Share Resources</b></a><br/><?php } ?>

                </div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade less_res" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-cloud-download"></b> Resources/File Explorer</h4>
				</div>

				<div class="modal-body">
					<h5></h5>
					<div class="" id="file_sharer" align="right">

					<div id="" class="panel_white_round_">
						<div id="shared_files_block"></div>

						<div style="background:#FFC; border-left:1px solid #ccc; border-top:1px solid #ccc;"><?php if(isset($_SESSION['is_user'])){ ?><a href="#" title="Share a document" data-toggle="modal" data-target=".fileupload"><img src="../images/upload_icon.png" alt="Share a document" width="14" height="14" border="0" > Share a document</a><?php } ?> <img src="../images/spacer.gif" width="20" height="5">  <a href="#"title="Refresh list" onClick="showSharedFiles();"> <img src="../images/refresh-icon.png" alt="Refresh list" width="14" height="14" border="0" > Refresh uploaded files</a></div>
					</div>
					</div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade notetext" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-file"></b> Your Notepad</h4>
				</div>

				<div class="modal-body">
				<form action="" name="form_notepad" method="get" onSubmit="saveNote(); return false;">
					<input type="hidden" name="prt_id" id="p_idr" value="<?php echo $_SESSION['part_id']; ?>" />
					<textarea name="notepad_text" id="notepad_text"  style="width:99%; height:150px;"><?php echo $noteText; ?></textarea>
				</form>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary" onClick="saveNote();">Save</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade fileupload" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xs" style="color:#000;font-family:calibri;font-size:12px;">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <h4 class="modal-title" id="myModalLabel2"><b class="fa fa-cloud-upload"></b> File Uploader</h4>
				</div>

				<div class="modal-body">
					<iframe id="upload_target" name="upload_target" align="middle" src="../file-uploader.php" style="width:95%;height:250px;border:0px; font-family:tahoma; overflow:auto;"  frameborder="0" allowtransparency="true"></iframe>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	</div>
	<br clear="all">
	</div>

	</div>
	</div>

	<script type="text/javascript">

	function confirmExitClass(){
		if(confirm("Are you sure to exit the classroom ?")){
			location.href="../endmeet.php?pid=<?php echo $partid; ?>&rk=<?php echo $roomkey; ?><?php echo isset($_SESSION['is_user'])?"&mid=".$_SESSION['is_user']:"";?>";
		}
	}
	/*
	if(document.getElementById("didatic").checked){
		document.getElementById("resx_les").style.display="none";
		document.getElementById("resy_les").style.display="block";
	}else{
		document.getElementById("resx_les").style.display="block";
		document.getElementById("resy_les").style.display="none";
	}
	*/
	function toggle_dispBox(chkBox){
		dispBox1=document.getElementById("resx_les");
		dispBox2=document.getElementById("resy_les");
		if(!chkBox.checked){
			dispBox1.style.display="block";
			dispBox2.style.display="none";
		}
		else {
			dispBox1.style.display="none";
			dispBox2.style.display="block";
		}
	}

	function select_item(val){
		document.getElementById("select_input").value = val;
	}

	select_disp(1);
	function select_disp(val){
		if(val==1){
			document.getElementById("resx_les").style.display="block";
			document.getElementById("resy_les").style.display="none";
			document.getElementById("resz_les").style.display="none";
			document.getElementById("resa_les").style.display="none";
		}else if(val==2){
			document.getElementById("resx_les").style.display="none";
			document.getElementById("resy_les").style.display="block";
			document.getElementById("resz_les").style.display="none";
			document.getElementById("resa_les").style.display="none";
		}else if(val==3){
			document.getElementById("resx_les").style.display="none";
			document.getElementById("resy_les").style.display="none";
			document.getElementById("resz_les").style.display="block";
			document.getElementById("resa_les").style.display="none";
		}else{
			document.getElementById("resx_les").style.display="none";
			document.getElementById("resy_les").style.display="none";
			document.getElementById("resz_les").style.display="none";
			document.getElementById("resa_les").style.display="block";
		}
	}

	//This function will display the users
	function showParticipants(){
		//Send an XMLHttpRequest to the 'show-message.php' file

		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../show-participant-list.php",false);
			xmlhttp.send(null);

		}
		else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.open("GET","../show-participant-list.php",false);
			xmlhttp.send();
		}

		//Replace the content of the messages with the response from the 'show-messages.php' file
		document.getElementById('participants_scrollPane').innerHTML = xmlhttp.responseText

		//Repeat the function each 5 seconds
		setTimeout('showParticipants()',5000);

	}
	//Start the show participants() function
	showParticipants();

	function showChatMessages(){
		//Send an XMLHttpRequest to the 'show-chat-message.php' file
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../show-chat-messages.php",false);
			xmlhttp.send(null);

		}
		else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.open("GET","../show-chat-messages.php",false);
			xmlhttp.send();
		}

		//Replace the content of the messages with the response from the 'show-messages.php' file
		document.getElementById('discussion_chat').innerHTML = xmlhttp.responseText

		//Repeat the function each 2 seconds
		setTimeout('showChatMessages()',2000);

		var objDiv = document.getElementById("discussion_chat");
		objDiv.scrollTop = objDiv.scrollHeight;

	}
	//Start the showmessages() function
	showChatMessages();

	function showLessonMessages(){
		//Send an XMLHttpRequest to the 'show-lesson-message.php' file
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../show-lesson-messages.php",false);
			xmlhttp.send(null);

		}
		else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.open("GET","../show-lesson-messages.php",false);
			xmlhttp.send();
		}

		//Replace the content of the messages with the response from the 'show-messages.php' file
		document.getElementById('lesson_chat').innerHTML = xmlhttp.responseText

		//Repeat the function each 2 seconds
		setTimeout('showLessonMessages()',2000);

		var objDiv = document.getElementById("lesson_chat");
		objDiv.scrollTop = objDiv.scrollHeight;

	}
	//Start the showmessages() function
	showLessonMessages();

	function showStartTime(){
		//Send an XMLHttpRequest to the 'show-message.php' file
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../start_time.php",false);
			xmlhttp.send(null);

		}
		else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.open("GET","../start_time.php",false);
			xmlhttp.send();
		}

		//Replace the content of the messages with the response from the 'show-messages.php' file
		document.getElementById('start_time').innerHTML = xmlhttp.responseText

		//Repeat the function each 60 seconds
		setTimeout('showStartTime()',60000);

	}
	//Start the showmessages() function
	showStartTime();

	//This function will submit the message
	function send() {

		var message=document.getElementById("chat_text_input").value;
		var mtype = document.getElementById("select_input").value;
		if(message == 'Type your message here...'){
			alert('Type your message');
		}else{
			//Send an XMLHttpRequest to the 'send.php' file with all the required informations
			var sendto = "../sendchatmessage.php?message="+message+"&mtype="+mtype;
			if(window.XMLHttpRequest){
				xmlhttp = new XMLHttpRequest();
				xmlhttp.open("GET",sendto,false);
				xmlhttp.send(null);

				document.getElementById("chat_text_input").focus();
			}
			else{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.open("GET",sendto,false);
				xmlhttp.send();

				document.getElementById("chat_text_input").focus();
			}
			var error = '';
			//If an error occurs the 'send.php' file send`s the number of the error and based on that number a message is displayed
			error=String(xmlhttp.responseText);
			if(error == ''){
				//
				//showmessages();
				document.getElementById("chat_text_input").value="";
			}
			else{
				alert(error);
			}
		}

	}

	function saveNote(){
		var messager=document.getElementById("notepad_text").value;
		var mtyper = document.getElementById("p_idr").value;

		//Send an XMLHttpRequest to the 'send.php' file with all the required informations
		var sendto = "../view_livepad_content.php?notepad_text="+messager+"&prt_id="+mtyper;
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET",sendto,false);
			xmlhttp.send(null);

			document.getElementById("notepad_text").focus();
		}
		else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.open("GET",sendto,false);
			xmlhttp.send();

			document.getElementById("notepad_text").focus();
		}
		//If an error occurs the 'send.php' file send`s the number of the error and based on that number a message is displayed
		alert(String(xmlhttp.responseText));
	}


	//For showing shared files
	//This function will display the users
	function showSharedFiles(){
		//Send an XMLHttpRequest to the 'show-message.php' file

		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../view-user-files.php",false);
			xmlhttp.send(null);

		}
		else{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.open("GET","../view-user-files.php",false);
			xmlhttp.send();
		}

		//Replace the content of the messages with the response from the 'show-messages.php' file
		document.getElementById('shared_files_block').innerHTML = xmlhttp.responseText

		//Repeat the function each 10 seconds
		setTimeout('showSharedFiles()',10000);

	}
	//Start the show participants() function
	showSharedFiles();

	</script>



    <!-- Custom Theme Scripts -->
   <script src="../../build/js/custom.min.js"></script>
   <!-- jQuery -->
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../../vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="../../vendors/skycons/skycons.js"></script>

  </body>
  </html>
