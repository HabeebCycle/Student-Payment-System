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
		echo "<script>Classroom has been closed</script>";
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
		if($row['prt_type']==1){$foto="../images/moderator-24.png"; $name="$name";}
			else if($res['gender']==2){$foto="../images/female-24.png";}
			else {$foto="../images/male-24.png";}
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


<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?> | Erudite</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="include/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="include/dashboard_stylesheet.css" />

<link rel="stylesheet" type="text/css" href="../include/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="../include/dashboard_stylesheet.css" />

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
<body>
<input type="hidden" value="<?php echo $roomkey; ?>" id="roomkey">
<input type="hidden" value="<?php echo $partid; ?>" id="partid">


<center>
<div class="container" id="contentPane">
<!--Container Opens-->
<header id="topHeader">
<div class="floatl padr10">
  <h1><a href="<?php echo $roomkey;?>"></a></h1>
</div>
<div class="floatl padl20 mart10"><b>Classroom: <?php echo $title; ?></b></div>
<div class="floatr header_right_links" align="	">
<b class="lh18"><?php echo "Welcome $name ";?></b><br>
<a href="#" type="button" onClick="confirmExitClass();"><b><?php echo isset($_SESSION['is_user'])?"End Class":"Leave Class"; ?></b></a></div>
</header>


<!--End of top part-->

<div class="pad5">
<center>
	<div align="center" class="">
    	<div class="floatl" id="lesson_block">
    	   <?php //include("whiteboard_viewer.php");?>
		   <div id="lesson_chat" class="pad5">
		   <!-- blah blah blah -->
           </div>
    	</div>
		<!--
        <div class="floatr" id="whiteboard_container">
           <?php include("whiteboard_viewer.php");?>
        </div> -->
    </div>
	</center><br clear="all"><br clear="all">
    <div class="">
    	<div id="participants_list" class="floatl panel_white_round_"><b class="colorBlue">Participants</b>
        <div class="participants_scrollPane" id="participants_scrollPane">



 <!--              <li><a href="#"><b><img src="../../images/male-24.png" alt="Male" width="16" height="16" border="0"></b><img src="../../images/webcam-icon-small.gif" alt="Webcam" width="9" height="10" border="0" class="floatr" title="Webcam">George</a></li>
-->



          </div>
        </div>
        <div class="floatl" id="discussion_block">
        		<div id="discussion_chat" class="pad5">
                     <!--   <ul>
                        	<li><span class="msgTime">10:47pm</span><b>Jane:</b> how do you do?  </li>
                            <li><span class="msgTime">10:47pm</span><b>Jane:</b> hey hi wassup? hey hi wassup? hey hi wassup? hey hi wassup? hey hi wassup? hey hi wassup?  how do you do?  </li>
                            <li><span class="msgTime">10:47pm</span><b>Jane:</b> how do you do?  </li>				<li><div id="discussion_notifier">John left the room </div></li>
                            <li><span class="msgTime">10:47pm</span><b>Jane:</b> how do you do?  </li>
                        </ul>-->
                </div>



                <div id="discussion_chat_textbox">
                <form action="" method="get" onSubmit="send(); return false;" >

          <input type="text" id="chat_text_input" value="Type your message here..." maxlength="255"onClick="javascript: this.value=='Type your message here...'?this.value='':this.value=this.value;"><input id="chat_text_send_btn" type="button" value="" onClick="send();">
          </form>
          </div>
        </div>
        <div class="floatl" id="tools_panel_right">
        		<div class="marl10" id="start_time" title="<?php echo $title; ?>">
				<!-- blah blah blah -->
				</div>&nbsp;&nbsp;&nbsp;Facilitator: <?php echo $mod_name; ?>
                <div class="panel_white_round_ marl10 mart5" title="Tools"><img src="../images/tools_icon.gif" width="14" height="14" alt="Tools"> <b>Tools:</b> <a href="#" class="tool_btn_w26" title="Live Notepad" onClick="popbox_show('LiveNotepad');"><img src="../images/notepad_icon.gif" alt="Notepad" width="16" height="16" border="0"></a>
                <a href="#" class="tool_btn_w26" title="File Explorer"  onClick="toggle_showHide('file_explorer');"><img src="../images/files_icon.gif" alt="File Explorer" width="16" height="16" border="0"></a><?php if(isset($_SESSION['is_user'])){ ?>
                <a href="#" class="tool_btn_w26" title="Share a document"  onClick="popbox_show('fileUploader');"><img src="../images/upload_icon.png" alt="Share a document" width="16" height="16" border="0"></a><?php } ?>
				<a href="#" class="tool_btn_w26" title="View Agenda" onClick="popbox_show('LessonAgenda');"><img src="../images/icon-info.gif" alt="Agenda" width="16" height="16" border="0"></a>

                </div><br/>
				<?php if(isset($_SESSION['is_user'])){ ?>
				<div id="chat_select">
					<input type="radio" name="nchat" value="1" onClick="select_item(this.value);" checked>Post to Lesson Board<br>
					<input type="radio" name="nchat" value="2" onClick="select_item(this.value);">Post to Chat Discussion
				</div>
				<input type="hidden" id="select_input" value="1">
				<?php }else{ ?>
				<input type="hidden" id="select_input" value="2">
				<?php } ?>
        </div>
    </div>
    <br clear="all">
</div>



<!--Container ends-->
</div>


<div class="pop_lightbox" id="popBox_participant_detail" align="left">
      <a href="#" class="small_close_btn floatr" onclick="popbox_close('popBox_participant_detail');">x</a>
      <iframe name="" id="fraViewParticipantDetail" align="middle" src="../view_participant_detail.php" style="width:100%; border:0px; font-family:tahoma; text-align:center; overflow:auto;" frameborder="0" allowtransparency="true"></iframe>


</div>


<div class="pop_lightbox" id="LiveNotepad"  style="min-width:100px; width:600px;" align="left" >
  <form action="" name="form_notepad" method="get" onSubmit="saveNote(); return false;">
    <a href="#" class="small_close_btn floatr" onclick="popbox_close('LiveNotepad');" title="Minimize">x</a><img src="../images/spacer.gif" alt="" width="5" height="1" class="floatr"><input type="button" value="" style="background:url(../images/save_icon.gif);border:0px; cursor:pointer; width:14px; height:14px;" title="Save/Download" class="floatr" onClick="saveNote();" >
    <div class="lighbox_title" id="Notepad_title" onMouseDown="dragStart(event, 'LiveNotepad');" style="cursor:move"><img src="../images/notepad_icon.gif" alt="" width="16" height="16"> Notepad</div>
	<input type="hidden" name="prt_id" id="p_idr" value="<?php echo $_SESSION['part_id']; ?>" />
    <textarea name="notepad_text" id="notepad_text"  style="width:99%; height:200px; max-width:594px; max-height:500px;"><?php echo $noteText; ?></textarea>
  </form>
</div>

<div class="pop_lightbox" id="LessonAgenda"  style="min-width:100px; width:600px;" align="left" >
    <a href="#" class="small_close_btn floatr" onclick="popbox_close('LessonAgenda');" title="Minimize">x</a><img src="../images/spacer.gif" alt="" width="5" height="1" class="floatr">
    <textarea style="width:99%; height:200px; max-width:594px; max-height:500px;" disabled><?php echo $agenda; ?></textarea>
</div>

<div class="pop_lightbox" id="fileUploader" align="left" >

     <a href="#" class="small_close_btn floatr" onclick="popbox_close('fileUploader');">x</a><img src="../images/spacer.gif" alt="" width="5" height="1" class="floatr">
    <div class="lighbox_title"><img src="../images/upload_icon.png" alt="" width="16" height="16"> File Uploader</div>

 <iframe id="upload_target" name="upload_target" align="middle" src="../file-uploader.php" style="width:400px;height:250px;border:0px; font-family:tahoma; overflow:auto;"  frameborder="0" allowtransparency="true"></iframe>

</div>


<div class="pop_lightbox" id="meetingOver" align="left" >
<!---->
<img src="../images/spacer.gif" alt="" width="5" height="1" class="floatr">
    <div class="lighbox_title">Classroom is  closed...</div>
    The facilitator has closed the classroom.<br>
    Please use the top-right button to Leave.<br>
<br>
    <input type="button" class="def_btn" value="Close" onclick="popbox_close('meetingOver');">


</div>


<!--file sharer-->
<div class="" id="file_sharer" align="right">
<span class="colorBlue title"><b><a href="#" onClick="toggle_showHide('file_explorer');"> <img class="floatl" src="../images/files_icon.gif" width="16" height="16" border="0">&nbsp; File Explorer</a></b></span>

<div id="file_explorer" class="">
<div id="shared_files_block"></div>

<div class="pad5" style="background:#FFC; border-left:1px solid #ccc; border-top:1px solid #ccc;"><?php if(isset($_SESSION['is_user'])){ ?><a href="#" title="Share a document" onClick="popbox_show('fileUploader');"><img src="../images/upload_icon.png" alt="Share a document" width="14" height="14" border="0" > Share a document</a><?php } ?> <img src="../images/spacer.gif" width="20" height="5">  <a href="#"title="Refresh list" onClick="showSharedFiles();"> <img src="../images/refresh-icon.png" alt="Refresh list" width="14" height="14" border="0" > Refresh uploaded files</a></div>
</div>
</div>
<!--file sharer ends-->


</center>


<script type="text/javascript">
function confirmExitClass(){
	if(confirm("Are you sure to exit the classroom ?")){
		location.href="../endmeet.php?pid=<?php echo $partid; ?>&rk=<?php echo $roomkey; ?><?php echo isset($_SESSION['is_user'])?"&mid=".$_SESSION['is_user']:"";?>";
	}

}
</script>

<script type="text/javascript">

popbox_close('LiveNotepad');
popbox_close('LessonAgenda');
popbox_close('popBox_participant_detail');
popbox_close('fileUploader');
popbox_close('meetingOver');


function select_item(val){
	//alert(val);
	document.getElementById("select_input").value = val;
}

function toggle_msgEdit(chkBox,id){

	txtBox=document.getElementById(id);
	if(!chkBox.checked)
	{

		txtBox.style.display="none";
	}
	else {
		txtBox.style.display="block";
	}
}
</script>


<script type="text/javascript">
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

	//Repeat the function each 6 seconds
	setTimeout('showParticipants()',6000);

}
//Start the show participants() function
showParticipants();




//This function will display the users
function showChatMessages(){
	//Send an XMLHttpRequest to the 'show-message.php' file

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

	//Repeat the function each 3 seconds
	setTimeout('showChatMessages()',2000);

	var objDiv = document.getElementById("discussion_chat");
	objDiv.scrollTop = objDiv.scrollHeight;

}
//Start the showmessages() function
showChatMessages();

//This function will display the users
function showLessonMessages(){
	//Send an XMLHttpRequest to the 'show-message.php' file

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

	//Repeat the function each 3 seconds
	setTimeout('showLessonMessages()',2000);

	var objDiv = document.getElementById("lesson_chat");
	objDiv.scrollTop = objDiv.scrollHeight;

}
//Start the showmessages() function
showLessonMessages();


//This function will display the users
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

	var objDiv = document.getElementById("lesson_chat");
	objDiv.scrollTop = objDiv.scrollHeight;

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

	//Repeat the function each 5 seconds
	setTimeout('showSharedFiles()',5000);

}
//Start the show participants() function
showSharedFiles();

</script>

<!--<script type="text/javascript">
//chk if room is closed

//For showing shared files
//This function will display the users
function chkRoomClosed(){
	//Send an XMLHttpRequest to the 'show-message.php' file
var rk=document.getElementById('roomkey').value;
var pid=document.getElementById('partid').value;

	if(window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET","../chk_room_closed.php?rk="+rk+"&pid="+pid,false);
		xmlhttp.send(null);

	}
	else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		xmlhttp.open("GET","../chk_room_closed.php?rk="+rk+"&pid="+pid,false);
		xmlhttp.send();
	}
	//Repeat the function each 3 seconds
	setTimeout('chkRoomClosed()',5000);

}
//Start the show participants() function
chkRoomClosed();
</script>-->


<script type="text/javascript">
	function showParticipantDetail(pid) {
		var viewDetailPage="../view_participant_detail.php";
		document.getElementById('fraViewParticipantDetail').src=viewDetailPage+"?pid="+pid;
		popbox_show('popBox_participant_detail');

	}
</script>


</body>
</html>
