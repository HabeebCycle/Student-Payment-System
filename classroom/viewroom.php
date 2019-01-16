<?php
include_once("header.php");
include("phpfunc.php");

$errorMessage="";
if(isset($_POST['roomkey'])){
$roomkey=$_POST['roomkey'];
if($roomkey==""){$errorMessage="Invalid Room key"; $roomkey="Invalid";}
$username = $_SESSION['uname'];
$q="SELECT * FROM lessons WHERE id='$roomkey'";
$result=mysqli_query($connection, $q);
if($result){
$row=mysqli_fetch_array($result);
$title=$row['title'];
$desc=$row['agenda'];
$startdt=date("d F, Y h:i a",$row['start']);
$enddt=date("d F, Y h:i a",($row['duration']+$row['start']));
$duration = $row['duration'];
$less = $row['flip_url'];
$chat = $row['chat_url'];
}

}
?>
<style type="text/css">
<!--
.lighbox_title {font-size:16px; font-weight:bold; padding-bottom:5px; border-bottom:1px solid #ccc; margin-bottom:10px;}
/* Classes for viewing static room details  */
.big_icon_buttons_static span {top:00px; left:120px; display:block; background:#fff; border:1px dotted #ccc; color:#999; width:auto; min-width:620px; max-width:620px; clear:left;}
.big_icon_buttons_static span b {color:#999;}
.big_icon_buttons_static span:hover b {color:#3d63d9;}

#viewSharedFiles {max-height:150px; overflow:auto;}

#showChatScript {padding-right:10px; overflow:auto; max-height:180px; margin-right:20px; border:1px dashed #ccc; padding:5px; background:#FFFEF9}
#showChatScript .msgTime {color:#333; right:0px; background:#fff; border:1px solid #ccc; padding:2px 5px; display:none; position:absolute; top:0px;  -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;}
#showChatScript ul {display:inline-block; margin:0px; padding:0px; width:100%;}
#showChatScript ul li {position:relative; list-style:none; display:block; padding:5px; }
#showChatScript ul li:hover .msgTime {display:block;}

#showNoteScript {padding-right:10px; overflow:auto; max-height:180px; margin-right:20px; border:1px dashed #ccc; padding:5px; background:#FFFEF9}
#showNoteScript .msgTime {color:#333; right:0px; background:#fff; border:1px solid #ccc; padding:2px 5px; display:none; position:absolute; top:0px;  -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;}
#showNoteScript ul {display:inline-block; margin:0px; padding:0px; width:100%;}
#showNoteScript ul li {position:relative; list-style:none; display:block; padding:5px; }
#showNoteScript ul li:hover .msgTime {display:block;}

#showChatScript1 {padding-left:10px; overflow:auto; max-height:180px; margin-right:20px; border:1px dashed #ccc; padding:5px; background:#FFFEF9}
#showChatScript1 .msgTime {color:#333; right:0px; background:#fff; border:1px solid #ccc; padding:2px 5px; display:none; position:absolute; top:0px;  -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;}
#showChatScript1 ul {display:inline-block; margin:0px; padding:0px; width:100%;}
#showChatScript1 ul li {position:relative; list-style:none; display:block; padding:5px; }
#showChatScript1 ul li:hover .msgTime {display:block;}

#viewParticipantList {max-height:180px; overflow:auto; margin-right:px;}
#viewParticipantList ul {display:inline; list-style:none;}
#viewParticipantList ul li {padding-right:10px;}
#viewParticipantList ul li a {display:block; padding:3px}

/* Classes for viewing static room details  */
.big_icon_buttons {background:url(images/home_icons_150x150_bg.png) left top  no-repeat; padding:21px 22px; max-height:105px; margin:0px; display:block; float:left; margin-right:10px; 	-moz-border-radius:10px; -webkit-border-radius:10px; margin-right:10px;  margin-left:10px; text-decoration:none; margin-bottom:10px; position:relative;  -moz-user-select: none; -khtml-user-select: none; user-select: none;}
.big_icon_buttons:hover {-moz-box-shadow: 2px 2px 5px #ccc; -webkit-box-shadow: 2px 2px 5px #aaa; box-shadow: 2px 2px 5px #aaa; text-decoration:none;}
.big_icon_buttons:focus,.big_icon_buttons:active {background:url(images/home_icons_150x150_bg.png) left bottom  no-repeat;}
.big_icon_buttons span {display:none; padding:10px 10px; font-size:18px; position:absolute; background:#000; color:#fff; min-width:83px; top:110px; left:0px; z-index:100;	-moz-border-radius:10px; -webkit-border-radius:10px; margin-right:10px;opacity:0.8; filter: alpha(opacity=80); -moz-opacity:0.8; }
.big_icon_buttons:hover span {display:block;}

-->
</style>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Class Room Details <small><?php echo $title; ?></small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

					<div class="container" align="left">


<br>
  <br>
  <div class="big_icon_buttons big_icon_buttons_static" title="Room Details"><img src="images/icon_quickroom.gif" width="60" height="60" border="0"><span title="<?php echo "$desc"; ?>"><br>
  <b><?php echo $title; ?></b>
</span></div><br><br clear="all">


<?php
echo "<div class='pad10'><b>Started:</b> $startdt,<br> <b>Ended:</b> $enddt,<br> <b>Duration:</b> ".getDuration($duration)." </div>";

?>
<hr size="1" color="#CCCCCC">

<div class="row">
<div class="col-sm-12 col-md-2">
<span class="lighbox_title">Participants</span><br/>
<div id="viewParticipantList"><?php

	$q="SELECT * FROM participant_details AS pd WHERE pd.room_key LIKE '$roomkey' ORDER BY  user_id DESC";
$result=mysqli_query($connection, $q)or die("Query failed. Error resolving roomkey. -".mysqli_error());

	echo "<ul>";
		while ($row=mysqli_fetch_array($result))
		{
			$typ = $row['prt_type']==1?"admin":"student"; //1 - moderator; 2 - student
			$res = mysqli_fetch_array(mysqli_query($connection, "select * from $typ where id=".$row['user_id']));
			$name=$row['prt_name'];
			if($row['prt_type']==1){$foto="images/moderator-24.png"; $name="$name";}
			else if($res['gender']==2){$foto="images/female-24.png";}
			else {$foto="images/male-24.png";}
			$prt_email=trim($res['email']);
			echo "<li title='$name &lt; $prt_email &gt;'><img src='$foto' alt='$name' width='16' height='16' border='0'> $name  <img src='images/icon-info.gif' alt='Info'  border='0' class='' title='Info'></li>";
		}
	echo "</ul>";
	 ?></div>
</div>
<div class="col-sm-12 col-md-5">
<span class="lighbox_title">Lesson Script</span><br/>
<div id="showChatScript1">
<?php

$q="SELECT
		cm.participant_id AS pid, pd.prt_name AS pname, cm.message AS pmessage, cm.timeStamp AS ptime, pd.prt_enter_dt AS pjointime, pd.prt_leave_dt AS pleavetime FROM participant_details AS pd, lesson_note AS cm WHERE cm.participant_id LIKE pd.participant_id AND cm.room_key LIKE '$roomkey' ORDER BY cm.timeStamp";

		$result=mysqli_query($connection, $q) or die(mysqli_error());

		echo "<ul>";
		while($row=mysqli_fetch_array($result)){
			$time=date("H:i",strtotime($row['ptime']));
			if($time < "12:00")
			{$time.="am";}
			else{$time.="pm";}
			$pname="<span style='color:#333'>".$row['pname']." : </span>";

			echo "<li><span class='msgTime'>".$time."</span><b>POST: </b> ".$row['pmessage']." </li>";

		}
		echo "</ul>";

?></div>
</div>
<div class="col-sm-12 col-md-5">
<span class="lighbox_title">Chat Script</span><br/>
<div id="showChatScript">
<?php

$q="SELECT
		cm.participant_id AS pid, pd.prt_name AS pname, pd.prt_type AS ptype, cm.message AS pmessage, cm.timeStamp AS ptime, pd.prt_enter_dt AS pjointime, pd.prt_leave_dt AS pleavetime FROM participant_details AS pd, chat_message AS cm WHERE cm.participant_id LIKE pd.participant_id AND cm.room_key LIKE '$roomkey' ORDER BY cm.timeStamp";

		$result=mysqli_query($connection, $q) or die(mysqli_error());

		echo "<ul>";
		while($row=mysqli_fetch_array($result)){
			$time=date("H:i",strtotime($row['ptime']));
			if($time < "12:00")
			{$time.="am";}
			else{$time.="pm";}
			$pname="<span style='color:#333'>".$row['pname']." : </span>";

			echo "<li><span class='msgTime'>".$time."</span><b>".($row['ptype']==1?"Facilitator":$pname)."</b> ".$row['pmessage']." </li>";

		}
		echo "</ul>";

?></div>
</div>
</div>
<hr size="1" color="#CCCCCC">
<div class="row">
<div class="col-sm-12 col-md-4">
<span class="lighbox_title">Shared Files</span>
<div id="viewSharedFiles">
      <?php

$q="SELECT sf.*,pd.participant_id AS pid, pd.prt_name AS pname FROM sharedfiles AS sf, participant_details AS pd WHERE roomkey LIKE '$roomkey' AND sf.participant_id = pd.participant_id ORDER BY uploadedOn DESC";
$result=mysqli_query($connection, $q) or die("Query failed. "+mysqli_error());;
$totalFiles=mysqli_num_rows($result);
if($totalFiles<1){echo "No files shared in this class.";}
//echo $totalFiles;
if($result) {
echo "<table cellpadding='5' cellspacing='0' border='0'>";
	while($row=mysqli_fetch_array($result)){
  	$downloadpath="users/documents/".$row['file_name'];
		echo "<tr>
			    <td width='5%'>".checkFileTypeImg($row['file_type'])."</td>
			    <td width='75%' title='Shared by ".$row['pname']."'>".$row['file_name']."</td>
			    <td width='20%'><a href='".$downloadpath."' title='View/Download File (".$row['file_size']." Kb)' target='_blank'>
				<img src='images/buttons/download_arrow_small.gif' alt='Download file' width='16' height='18' border='0' title='View/Download file (".$row['file_size']." Kb)'></a></td>
			</tr>";

	}
echo "</table>";


}
else{
	echo "No file shared";
}

?></div>
</div>
<div class="col-sm-12 col-md-4">
<span class="lighbox_title">Your Saved Note</span>
<div id="showNoteScript">
<?php

$q="SELECT a.prt_noteText as note, a.room_key, b.title from participant_details a, lessons b where a.prt_name='$username' and a.room_key=b.id";

		$result=mysqli_query($connection, $q) or die(mysqli_error());

		echo "<ul>";
		while($row=mysqli_fetch_array($result)){
			$pname="<span style='color:#333'><u>".$row['title']."</u></span>";
			echo "<li>$pname<br/><b>Note: </b> ".$row['note']."<br/> </li>";
		}
		echo "</ul>";

?></div>
</div>
<div class="col-sm-12 col-md-4">
<span class="lighbox_title">Other Files</span>
<div id="viewOtherFiles">
    <a href='res/lessons/<?php echo $less; ?>' target="_blank"><img src='images/buttons/download_arrow_small.gif' alt='Download file' width='8' height='9' border='0'>    Download Board Script</a><br><br>
    <a href="res/lessons/<?php echo $chat; ?>" target="_blank"><img src='images/buttons/download_arrow_small.gif' alt='Download file' width='8' height='9' border='0'>  Download Chat Script</a><br><br>
	<a href="#" class=""><img src='images/buttons/download_arrow_small.gif' alt='Download file' width='8' height='9' border='0'>   Download Video (if recorded)</a></div>
</div>
</div>

  <br>
<br>
<br>


</div>
				</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<?php include_once("footer.php"); ?>

  </body>
</html>
