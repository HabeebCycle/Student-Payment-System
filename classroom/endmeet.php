<?php
require_once ('../../config/config.php');
if(!isset($_SESSION['islog']) and !$_SESSION['islog']){
	header('Location: ../../');
}
ob_start();
if(isset($_POST['submitted'])){
	if(isset($_POST['pidi']) && isset($_POST['rki'])) {
	$pid=$_POST['pidi'];
	$rk=$_POST['rki'];
	$msg=$_POST['feedbackMsg'];
	if(strlen($msg)>0){
		$q="UPDATE participant_details SET prt_feedback='$msg' WHERE participant_id LIKE $pid AND 	room_key LIKE '$rk'";
		$result=mysqli_query($connection, $q) or die("Query failed. Error resolving roomkey. -".mysqli_error());
		$dat = time();
		$q="insert into feedback(student,lesson,teacher,message,dat) values (".$_SESSION['uid'].",$rk,(select teacher from lessons where id=$rk),'$msg',$dat)";
		mysqli_query($connection, $q) or die("Query failed. Error resolving roomkey. -".mysqli_error());
        echo "Your feedback was sent successfully. Thank You";
		echo "<script type='text/javascript'>alert('Your feedback was sent successfully. Thank You');</script>;";
	}
		header ("location: ../student/poruse.php");
		exit;
	}
}else{
	//removing entry of participant from live attendee
	if(isset($_GET['pid']) && isset($_GET['rk'])){

		$pid=$_GET['pid'];
		$rk=$_GET['rk'];
		$q="DELETE FROM live_attendees WHERE participant_id LIKE '$pid' AND room_key LIKE '$rk'";
		$result=mysqli_query($connection, $q) or die("Query failed. Error resolving classroom. -".mysqli_error());


		//removing session for the logged in participant before logging out
		if (isset($_SESSION['participant_logged_in'])) {
			unset($_SESSION['participant_logged_in']);
			unset($_SESSION['part_id']);
			unset($_SESSION['roomkey']);
			unset($_SESSION['is_user']);
			unset($_SESSION['isAdmin']);
			unset($_SESSION['room_is_open']);
		}
		//header('Location: index.php');
		$timestamp=date("Y-m-d h:m:s a");
		$q="UPDATE participant_details SET prt_leave_dt='$timestamp' WHERE room_key LIKE '$rk' AND participant_id LIKE '$pid'";
			$result=mysqli_query($connection, $q) or die("Query failed. Error resolving classroom. -".mysqli_error());

		echo "<script>alert('You are out of the class.')</script>";
		if(isset($_GET['mid']) && $_GET['mid']!=""){

			//attaching end datetime to the room
			$timestamp=date("Y-m-d h:m:s a");

			$qr="SELECT
				cm.participant_id AS pid, pd.prt_name AS pname, cm.message AS pmessage, cm.timeStamp AS ptime, pd.prt_enter_dt AS pjointime, pd.prt_leave_dt AS pleavetime FROM participant_details AS pd, lesson_note AS cm WHERE cm.participant_id LIKE pd.participant_id AND cm.room_key LIKE '$rk' ORDER BY cm.timeStamp";

				$result=mysqli_query($connection, $qr) or die(mysqli_error());
				$output = "Lesson Note Script\r\n\n";
				//echo "<ul>";
				while($row=mysqli_fetch_array($result)){
					$time=date("H:i",strtotime($row['ptime']));
					if($time < "12:00"){
						$time.="am";
					}else{
						$time.="pm";
					}
					$output = $output."(".$time.")\r\n ".$row['pmessage']."\r\n\n";
				}
				$file_lesson = "res/lessons/lesson$rk.txt";
				$file = fopen($file_lesson,"w");
				fwrite($file, $output);
				fclose($file);

			$qt="SELECT
				cm.participant_id AS pid, pd.prt_name AS pname, pd.prt_type AS ptype, cm.message AS pmessage, cm.timeStamp AS ptime, pd.prt_enter_dt AS pjointime, pd.prt_leave_dt AS pleavetime FROM participant_details AS pd, chat_message AS cm WHERE cm.participant_id LIKE pd.participant_id AND cm.room_key LIKE '$rk' ORDER BY cm.timeStamp";

				$result=mysqli_query($connection, $qt) or die(mysqli_error());
				$output = "Lesson Discussion Script\r\n\n";
				//echo "<ul>";
				while($row=mysqli_fetch_array($result)){
					$time=date("H:i",strtotime($row['ptime']));
					if($time < "12:00"){
						$time.="am";
					}else{
						$time.="pm";
					}
					$pname=$row['pname'];
					$output = $output.($row['ptype']==1?"Facilitator":$pname). "(".$time.") : ".$row['pmessage']."\r\n";
				}
				$file_chat = "res/lessons/chat$rk.txt";
				$file = fopen($file_chat,"w");
				fwrite($file, $output);
				fclose($file);

			$user_id=$_GET['mid'];
			$q="UPDATE lessons SET chat_url='chat$rk.txt', flip_url='lesson$rk.txt', duration=(".time()." - start) WHERE id LIKE '$rk'";
			$result=mysqli_query($connection, $q) or die("Query failed. Error resolving classroom. -".mysqli_error());

			//removing all the participants from live_attendees for the room
			$q="DELETE FROM live_attendees WHERE room_key LIKE '$rk'";
			$result=mysqli_query($connection, $q) or die("Query failed. Error resolving roomkey. -".mysqli_error());

			echo "<script type='text/javascript'>alert('You are out of the classroom. \\nThe classroom has been closed.');</script>;";
			header ("location: ../teach/poruse.php");
			exit;

		}else{
			$sqd = mysqli_query($connection, "select prt_feedback from participant_details where room_key LIKE '$rk' AND participant_id LIKE '$pid'");
			$res = mysqli_fetch_array($sqd)['prt_feedback'];
			if($res=="" or $res==NULL){
		?>
			<center>
			<div style="width:300px; border: 3px solid #00f;padding:5px;">
				<p>We would love to hear from you a feedback regarding the lesson and the classroom and about your experience to this application.</p><br>
			<form action="" method="post" name="feedForm">
			<input type="hidden" value="<?php echo $pid;?>" name="pidi">
			<input type="hidden" value="<?php echo $rk;?>" name="rki">
			<input type="hidden" value="TRUE" name="submitted">
			<table width="500" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>&nbsp;&nbsp;<textarea cols="30" rows="5" name="feedbackMsg" placeholder="Share your experience about the lesson here..."></textarea></td>
				</tr>
			  <tr>
				<td><br><input type="submit" value="     Send     " class="def_btn" />
				  <input type="button" value="     Close     " class="def_btn" onClick="window.location = '../student/poruse.php'" /></td>
				</tr>
			</table>
			</form>
			</div>
			</center>
<?php
			}else{
				echo "<script type='text/javascript'>alert('You are out of the classroom.');</script>;";
				header ("location: ../student/poruse.php");
				exit;
			}
		}
	}else{
		header ("location: ../");
		exit;
	}

}

?>
