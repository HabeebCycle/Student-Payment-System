<?php
include_once("header.php");
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>List of Courses<small>Details</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of courses in Erudite Millennium.
                    </p>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Code</th>
                          <th>Price</th>
                          <th>Lessons</th>
						  <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from courses where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){
							$fac = mysqli_query($connection, "select distinct teacher from lessons where course=".$dn['id']);
							$tr2 = mysqli_num_rows(mysqli_query($connection,"select * from lessons where course = ".$dn['id']));
							$num = mysqli_num_rows(mysqli_query($connection,"select * from payment where confirm=1 and valid=0 and student=".$_SESSION['uid']." and course=".$dn['id']));
							$reff = mysqli_query($connection,"select * from payment where confirm=1 and valid=1 and student=".$_SESSION['uid']." and course=".$dn['id']);
							$nux = mysqli_num_rows($reff);
					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $dn['code']; ?></td>
                          <td><?php echo $dn['deno'].' '.$dn['price']; ?></td>
                          <td><?php echo $tr2; ?></td>
						  <td><?php echo($num==0?($nux>0?"Subscription Expired":"No Subscription"):"Subscription Active"); ?></td>
                          <td>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button>
						  </td>
                        </tr>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;text-align:left;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['name']; ?> Details </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;">Course <?php echo $dn['name']; ?> <br/><?php echo $dn['descr']; ?>.<br/>It has total number of <?php echo $tr2; ?> lessons.</p>
											<b>Price</b><br/>
											<p><?php echo $dn['deno'].$dn['price']; ?> monthly.</p>
											<b>Description</b><br/>
											<?php echo $dn['descr']; ?><br/><br/>
											<b>Facilitators</b>
											<?php
											$output = "";
											while($rr = mysqli_fetch_array($fac)){
												$fk = mysqli_fetch_array(mysqli_query($connection,"select name,info from admin where usertype=2 and id=".$rr['teacher']));
												$output = $output."<b>".$fk['name']."</b>: ".$fk['info']."<br/><br/>";
											}
											echo "<p>".$output."</p>";
											?>
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
