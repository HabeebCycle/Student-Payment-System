<?php
include_once("header.php");

if(isset($_POST['cemrep'])){
	$id = $_POST['cemid'];
	$jd = $_POST['message'];
	if(!empty($jd)){
		mysqli_query($connection,"update feedback set rep=1,reply='$jd' where id=$id");
		echo "<script>alert('Reply successful');</script>";
	}else{
		echo "<script>alert('Empty Message');</script>";
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

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">

                    </p>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Student</th>
						  <th>Details</th>
                          <th>Message</th>
                          <th>date</th>
						  <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from feedback where teacher=".$_SESSION['uid']);
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name,email,phone from student where id=".$dn['student']));

					  ?>
                        <tr>
						  <td><?php echo $tr1['name']; ?></td>
                          <td><?php echo $tr1['phone'].'; '.$tr1['email']; ?></td>
                          <td><?php echo wordwrap(substr($dn['message'],0,70).' ...',50,"<br>\n"); ?></td>
                          <td><?php echo date('D d M, Y',$dn['dat']); ?></td>
                          <td><?php echo ($dn['rep']==1?"Replied":"Pending"); ?></td>
                          <td>
						  <button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button>
							<?php if($dn['rep']==0){ ?>
						<button class="btn btn-info btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>">Reply</button><?php } ?>
						  </td>
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
											Message<p style="color:darkblue;"><?php echo $dn['message']; ?></p>
											<?php if($dn['rep']==1){ ?>Reply <p style="color:purple;"><?php echo $dn['reply']; ?></p> <?php } ?>
											<div class="ln_solid"></div>
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
										  <h4 class="modal-title" id="myModalLabel2">Reply</h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Type your message here</p>
												<textarea name="message" placeholder="Type here" class="form-control" required=""></textarea>
												<div class="ln_solid"></div>
												<input type="hidden" name="cemrep" value="TRUE" />
												<input type="hidden" name="cemid" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Send</button>
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
