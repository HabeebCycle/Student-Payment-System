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
                    <h2>List of Registered Students<small>Details</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of students registered for your courses
                    </p>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Student Name</th>
                          <th>E-mail</th>
                          <th>Phone</th>
                          <th>Country</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx1 = mysqli_query($connection,"select distinct student as sid from payment where confirm=1 and valid=0 and course in (select distinct course from lessons where teacher=".$_SESSION['uid'].")");
						while($dn1 = mysqli_fetch_array($qnx1)){
						$qnx = mysqli_query($connection,"select * from student where deleted=0 and id=".$dn1['sid']);
						while($dn = mysqli_fetch_array($qnx)){
					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $dn['email']; ?></td>
                          <td><?php echo $dn['phone']; ?></td>
						  <th><?php echo $dn['country']; ?></th>
                          <td>
							<button class="btn btn-info btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Info</button>
						  </td>
                        </tr>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:18px;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['name']; ?> Info </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;"><b>Other details are</b><br/><?php echo $dn['info']; ?>.</p>

											<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
									</div>
								</div>
							</div>

						<?php }} ?>
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
