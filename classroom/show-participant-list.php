<?php
ob_start();
require_once ('../../config/config.php');
include("phpfunc.php");

@$roomkey=$_SESSION['roomkey'];

$q="SELECT * FROM live_attendees AS at,participant_details AS pd WHERE pd.room_key LIKE '$roomkey' AND at.participant_id=pd.participant_id AND at.room_key=pd.room_key ORDER BY prt_name ";
$result=mysqli_query($connection, $q)or die("Query failed. Error resolving roomkey. -".mysqli_error());

	echo "<ul><br>";
		while ($row=mysqli_fetch_array($result))
		{
			$typ = $row['prt_type']==1?"admin":"student"; //1 - moderator; 2 - student
			$res = mysqli_fetch_array(mysqli_query($connection, "select * from $typ where id=".$row['user_id']));
			$name=$row['prt_name'];
			if($row['prt_type']==1){$foto="../images/moderator-24.png"; $name="$name <i>(Tutor)</i>";}
			else if($res['gender']==2){$foto="../images/female-24.png";}
			else {$foto="../images/male-24.png";}
			$prt_email=trim($res['email']);
			$prtid = $row['participant_id'];
			echo "<li title='$name &lt; $prt_email &gt;'><a href='#' data-toggle='modal' data-target='.partdet-".$prtid."'><img src='../images/icon-info.gif' alt='Info'  border='0' class='floatr' title='Info'> <img src='../images/webcam-icon-small.gif' alt='Webcam' width='0' height='0' border='0' class='floatr' title='Webcam'><img src='$foto' alt='$name' width='16' height='16' border='0'> $name </a></li>";
			/*
			echo "<div class='modal fade partdet-".$prtid."' tabindex='-1' role='dialog' aria-hidden='true'>
					<div class='modal-dialog modal-xs' style='color:#000;font-family:calibri;font-size:12px;'>
						<div class='modal-content'>
							<div class='modal-header'>
							  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>×</span>
							  </button>
							  <h4 class='modal-title' id='myModalLabel2'><b class='fa fa-user'></b> Participant's Details</h4>
							</div>
							<div class='modal-body'>
								<iframe name='' id='fraViewParticipantDetail' align='middle' src='../view_participant_detail.php?pid=".$prtid."' style='width:95%; border:0px; font-family:tahoma; text-align:center; overflow:auto;' frameborder='0' allowtransparency='true'></iframe>
							</div>
							<div class='modal-footer'>
							  <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
							</div>
						</div>
					</div>
				</div>";
				*/
		}
	echo "</ul>";

?>
