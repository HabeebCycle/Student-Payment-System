<?php
include_once("header.php");

if(isset($_POST['cemdel'])){
	$id = $_POST['cemid'];
	$jd = $_POST['jid'];
	$xd = $_POST['xid'];
	if($jd==0){
		if($xd == 1){
			mysqli_query($connection,"update payment set valid=$jd where id=$id");
			echo "<script>alert('Payment edited successfully');</script>";
		}else{
			echo "<script>alert('Please confirm the payment first');</script>";
		}
	}else{
		mysqli_query($connection,"update payment set valid=$jd where id=$id");
		echo "<script>alert('Payment edited successfully');</script>";
	}
}elseif(isset($_POST['cemcon'])){
	$id = $_POST['cemid'];
	mysqli_query($connection,"update payment set confirm=1 where id=$id");
	echo "<script>alert('Payment confirmed successfully');</script>";
}
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Payment<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>

						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">

                    </p>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Student</th>
                          <th>Course</th>
						  <th>Payment Ref/Dates</th>
						  <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from payment");
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from student where id=".$dn['student']));
							$tr2 = mysqli_fetch_array(mysqli_query($connection,"select name,code from courses where id = ".$dn['course']));

					  ?>
                        <tr>
						  <td><?php echo $tr1['name']; ?></td>
                          <td><?php echo $tr2['code']; ?></td>
                          <td><button class="btn btn-success btn-xs" data-toggle="modal" data-target=".dat-cem-sm-<?php echo $dn['id']; ?>"><i class="fa fa-money"></i> View Details</button></td>
                          <td><?php echo ($dn['confirm']==1?"Confirmed / ".($dn['valid']==1?"Expired":"Active"):"Pending"); ?></td>
                          <td>
							<?php if($_SESSION['usertype']==3){ ?>
							<button class="btn btn-<?php echo ($dn['valid']==1?"warning":"info"); ?> btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>"><?php echo ($dn['valid']==1?"Renew":"Remove"); ?></button><?php  if($dn['confirm']==0){ ?>
							<button class="btn btn-primary btn-xs" data-toggle="modal" data-target=".con-cem-sm-<?php echo $dn['id']; ?>">Confirm</button>
							<?php }} ?>
						  </td>
                        </tr>
							<div class="modal fade dat-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:16px;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-money"></i> Payment Details</h4>
										</div>
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Subscription Payment for <?php echo $tr2['code']; ?></p>
												<div class="ln_solid"></div>
												<?php $details = $dn['dates']; $refff = $dn['ref'];
													$dates = explode("#%",$details); $refs = explode("#%",$refff);
													$len = count($dates);
													for ($x=$len-1; $x>=0; $x--){
														echo ($len-$x).".   REFERENCE: ".$refs[$x]."<br/>Date: ".date('D d M, Y h:i:s a',$dates[$x])."<br/><br/>";
													}
												?>
											</div>
											<div class="modal-footer">
											  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
											</div>
									</div>
								</div>
							</div>
							<div class="modal fade del-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2"><?php echo ($dn['valid']==1?"Renew":"Remove"); ?> Access</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to <?php echo ($dn['valid']==1?"renew":"remove"); ?> access</p>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemdel" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
												<input type="hidden" name="xid" value="<?php echo $dn['confirm']; ?>" />
												<input type="hidden" name="jid" value="<?php echo $dn['valid']==1?0:1; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick=""><?php echo ($dn['valid']==1?"Renew":"Remove"); ?></button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade con-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Confirm Payment</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to confirm payment</p>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemcon" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Confirm</button>
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
