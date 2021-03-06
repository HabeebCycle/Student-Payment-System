<?php
ob_start();
require_once ('../../config/config.php');
include("phpfunc.php");

@$roomkey=$_SESSION['roomkey'];
@$partid=$_SESSION['part_id'];

$q="SELECT
		cm.participant_id AS pid, pd.prt_name AS pname, pd.prt_type AS ptype, cm.message AS pmessage, cm.timeStamp AS ptime, pd.prt_enter_dt AS pjointime, pd.prt_leave_dt AS pleavetime

		FROM participant_details AS pd, chat_message AS cm
		WHERE cm.participant_id LIKE pd.participant_id AND cm.room_key LIKE '$roomkey'

		ORDER BY cm.timeStamp";

		$result=mysqli_query($connection, $q) or die(mysqli_error());

		echo "<ul>";
		while($row=mysqli_fetch_array($result)){
			$time=date("H:i",strtotime($row['ptime']));
			if($time < "12:00")
			{$time.="am";}
			else{$time.="pm";}
			$pname=$row['pname']." : ";
			if($row['pid']==$partid){
				$pname="<span style='color:#333'>".$row['pname']." : </span>";
			}

			echo "<li><span class='msgTime'>".$time."</span><b>".($row['ptype']==1?"Facilitator":$pname)."</b><br/> ".$row['pmessage']." </li>";

		}
		echo "</ul>";

?>
