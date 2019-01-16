<?php
include_once("header.php");

if(isset($_POST['cemrep'])){
	if ($_POST['lesson'] == '-1') {//if the email supplied is empty
        $cor = "Please select the lesson"; $f = 'fz0';
    } else {
        $rk = mysqli_real_escape_string($connection,$_POST['lesson']);
    }
	$msg = $_POST['message'];
	if(empty($f)){
		if(!empty($msg)){
			$dat = time();
			mysqli_query($connection,"insert into feedback(student,lesson,teacher,message,dat) values (".$_SESSION['uid'].",$rk,(select teacher from lessons where id=$rk),'$msg',$dat)");
			echo "<script>alert('Thanks for your feedback.\\n Our correspondent will reply you shortly.');</script>";
		}else{
			echo "<script>alert('Error! You can not send an empty feedback');</script>";
		}
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
                    <h2>FeedBack<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-bullhorn"><b> Send FeedBack</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30" >

                    </p>


							<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">New FeedBack</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="">
											<div class="modal-body">
												<h5></h5>
												<select name="lesson" class="form-control"><option value="-1">Select Lesson</option>
													<?php
													$qns = mysqli_query($connection,"SELECT * FROM lessons");
													while($dns = mysqli_fetch_array($qns)){ ?>
													<option value="<?php echo $dns['id']; ?>"><?php echo $dns['title']; ?></option>
													<?php } ?>
												</select>		<br/>
												<textarea name="message" placeholder="Type your feedback here" class="form-control" required=""></textarea>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemrep" value="TRUE" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Send</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Message</th>
                          <th>date</th>
						  <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from feedback where student=".$_SESSION['uid']);
						while($dn = mysqli_fetch_array($qnx)){

					  ?>
                        <tr>
                          <td><?php echo wordwrap(substr($dn['message'],0,70).' ...',50,"<br>\n"); ?></td>
                          <td><?php echo date('D d M, Y h:i:s a',$dn['dat']); ?></td>
                          <td><?php echo ($dn['rep']==1?"<b class='fa fa-check green'>  Replied</b> (see details)":"<b class='fa fa-clock-o purple'>  Pending</b>"); ?></td>
                          <td>
						  <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button></td>
                        </tr>
							<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:16px;text-align:left;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2">Details</h3>
										</div>
										<div class="modal-body">
										<h5><?php echo date('D d M, Y',$dn['dat']); ?></h5>
											<b>Message</b><p style="color:darkblue;"><?php echo $dn['message']; ?></p>
											<?php if($dn['rep']==1){ ?><br/><b>Reply</b> <p style="color:purple;"><?php echo $dn['reply']; ?></p> <?php } ?>
											<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
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
