<?php
ob_start();
include_once("header.php");

if(isset($_POST['cemedt'])){
	// Initialize a session:

}elseif(isset($_POST['enclass'])){
	//header('Location: lessum.php');
	include("../classroom/Browser.php");
	include("../classroom/phpfunc.php");
	$browser = new Browser();
	$id = $_POST['roomkey'];
	$userid=$_SESSION['uid'];
	$username=$_SESSION['uname'];
	$prDateTime=date("Y-m-d h:i:s a");
	$prGeoLoc="";
	$prIP=getRealIpAddr();
	$prBrowser=$browser->getBrowser()."- ".$browser->getVersion();
	$prOS=php_uname('s')."-".php_uname('v');
	$type = 2;
	if(!isset($_SESSION['participant_logged_in'])){
		$_SESSION['room_is_open']=true;
		$sqr="SELECT * FROM participant_details WHERE prt_name LIKE '$username' AND room_key LIKE $id AND user_id LIKE '$userid'";
		if(mysqli_num_rows(mysqli_query($connection, $sqr))==0){
			$q="INSERT INTO participant_details (prt_name,room_key,user_id,prt_type,prt_enter_dt,prt_geo_location,prt_IP,prt_browser,prt_OS) VALUES('$username',$id,$userid,$type,'$prDateTime','$prGeoLoc','$prIP','$prBrowser','$prOS') ";
			$result = mysqli_query($connection, $q) or die('Query failed. ' .mysqli_error());
			if($result==1){
				//echo "<br><br> &gt;  Retrieving participant id...";
				//passing sql query to get the newly allocated participant id
				$sql="SELECT * FROM participant_details WHERE prt_name LIKE '$username' AND room_key LIKE $id AND user_id LIKE '$userid'";
				$res = mysqli_query($connection, $sql) or die('Query failed. ' . mysqli_error());
				if(mysqli_num_rows($res)==1){
					echo "<br><br> &gt;  Creating classroom session...";
					$row=mysqli_fetch_array($res);
					//creating session for participant
					$_SESSION['participant_logged_in']=true;
					$_SESSION['part_id']=$row['participant_id'];
					$_SESSION['roomkey']=$row['room_key'];
					$_SESSION['is_user']=NULL;

					//echo "<br><br> &gt;  Entering live attendee queue...";
					//entering participant into live attendees/currently available

					$q="INSERT INTO live_attendees (participant_id,room_key,mtype) VALUES(".$_SESSION['part_id'].",'".$_SESSION['roomkey']."',2)";
					$result = mysqli_query($connection, $q) or die('Query failed. ' .mysqli_error());

					//echo "<strong>successful</strong>";
					echo "<br><br> &gt;  Entering room...";
					header("location: ../classroom/room/$id") or die("Some error occured while redirecting. Please Go Back and try again.");
					exit;

				}
			}
		}else{
			$sql="SELECT * FROM participant_details WHERE prt_name LIKE '$username' AND room_key LIKE $id AND user_id LIKE '$userid'";
			$res = mysqli_query($connection, $sql) or die('Query failed. ' . mysqli_error());
			if(mysqli_num_rows($res)==1){
				echo "<br><br> &gt;  Creating classroom session...";
				$row=mysqli_fetch_array($res);
				//creating session for participant
				$_SESSION['participant_logged_in']=true;
				$_SESSION['part_id']=$row['participant_id'];
				$_SESSION['roomkey']=$row['room_key'];
				$_SESSION['is_user']=NULL;

				//echo "<br><br> &gt;  Entering live attendee queue...";
				//entering participant into live attendees/currently available
				$sqr="SELECT * FROM live_attendees WHERE participant_id LIKE ".$_SESSION['part_id']." AND room_key LIKE ".$_SESSION['roomkey'];
				if(mysqli_num_rows(mysqli_query($connection, $sqr))==0){
					$q="INSERT INTO live_attendees (participant_id,room_key,mtype) VALUES(".$_SESSION['part_id'].",'".$_SESSION['roomkey']."',2)";
					$result = mysqli_query($connection, $q) or die('Query failed. ' .mysqli_error());
				}
				//echo "<strong>successful</strong>";
				echo "<br><br> &gt;  Entering room...";
				header("location: ../classroom/room/$id") or die("Some error occured while redirecting. Please Go Back and try again.");
				exit;

			}
		}
	}else{
		//echo "<strong>successful</strong>";
		echo "<br><br> &gt;  Entering room...";
		header("location: ../classroom/room/$id") or die("Some error occured while redirecting. Please Go Back and try again.");
		exit;
	}
}
?>

<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Lessons<small>Details</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      Summary of your lessons.
                    </p>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>#</th>
						  <th>Title</th>
						  <th>Course</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from lessons where course in (select course from payment where confirm=1 and valid=0 and student=".$_SESSION['uid'].") order by date desc");
						$xyz = 0;
						while($dn = mysqli_fetch_array($qnx)){
							$tr2 = mysqli_fetch_array(mysqli_query($connection,"select name,code from courses where id = ".$dn['course']));
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from admin where id = ".$dn['teacher']));
							$xyz++;
					  ?>
                        <tr>
						  <td><?php echo $xyz; ?></td>
                          <td><?php echo $dn['title']; ?></td>
                          <td><?php echo $tr2['code']; ?></td>
                          <td><?php echo date('D d M, Y h:i A',$dn['date']); ?></td>
						  <td><?php echo ($dn['start']==0?($dn['date']<=time()?"<b class='fa fa-clock-o purple'> LOADING</b>":"<b class='fa fa-lock red'> PENDING</b>"):($dn['duration']==0?"<b class='fa fa-fighter-jet green'> ON GOING</b>":"<b class='fa fa-check blue'> DONE</b>")); ?></td>
                          <td>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button>
							<?php  if($dn['date']<=time() and $dn['duration']==0){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".cla-cem-sm-<?php echo $dn['id']; ?>">Enter Classroom</button>
						<?php  }elseif($dn['duration']>0){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".cld-cem-sm-<?php echo $dn['id']; ?>">Lesson Details</button>
						<?php } ?>
						  </td>
                        </tr>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;text-align:left;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['title']; ?> Details </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;">Title: <?php echo $dn['title']; ?> <br/>
											Course: <?php echo $tr2['name'].' ('.$tr2['code'].')'; ?> <br/>
											Date: <?php echo date('D d M, Y h:i A',$dn['date']); ?><br/>
											Facilitator:<?php echo $tr1['name']; ?><br/><br/>
											AGENDA:<br/><?php echo $dn['agenda']; ?>.
											</p>

											<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade cla-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Classroom</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Click 'Enter' button to enter the classroom</p>
												<div class="ln_solid"></div>
												<input type="hidden" name="enclass" value="TRUE" />
												<input type="hidden" name="roomkey" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Enter</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade cld-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Lesson Details</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="../classroom/viewroom.php?typer=2">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Click Show to get the details</p>
												<div class="ln_solid"></div>
												<input type="hidden" name="type" value="teach" />
												<input type="hidden" name="roomkey" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Show</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php } ?>
                      </tbody>
                    </table>


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
