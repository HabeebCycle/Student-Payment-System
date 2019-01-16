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
                    <h2>List of Lessons<small>Details</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      List of lessons available under Erudite courses.
                    </p>



                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Title</th>
						  <th>Course</th>
                          <th>Facilitator</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Resources</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from lessons order by id desc");
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from admin where usertype=2 and id=".$dn['teacher']));
							$tr2 = mysqli_fetch_array(mysqli_query($connection,"select name,code from courses where id = ".$dn['course']));

					  ?>
                        <tr>
                          <td><?php echo $dn['title']; ?></td>
                          <td><?php echo $tr2['code']; ?></td>
						  <td><?php echo ($_SESSION['uid']==$dn['teacher']?"YOU":$tr1['name']); ?></td>
                          <td><?php echo date('D d M, Y h:i A',$dn['date']); ?></td>
						  <td><?php echo ($dn['start']==0?"PENDING":($dn['duration']==0?"ON GOING":"DONE")); ?></td>
						  <td><?php echo ($dn['duration']>0?($_SESSION['uid']!=$dn['teacher']?"On Request":"<a href='../classroom/res/lessons/".$dn['flip_url']."' target='_blank' class='btn btn-success btn-xs'>Lesson</a><a href='../classroom/res/lessons/".$dn['chat_url']."' target='_blank' class='btn btn-info btn-xs'>Chat</a>"):"Not Available"); ?></td>
                          <td>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button>
						  </td>
                        </tr>
						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['title']; ?> Details </h3>
										</div>
										<div class="modal-body">
										<h5></h5>
											<p style="color:darkblue;">Lesson <?php echo $dn['title']; ?> <br/>AGENDA:<br/><?php echo $dn['agenda']; ?>.<br/>It is going to be taken by <?php echo $tr1['name']; ?> on <?php echo date('D d M, Y h:i A',$dn['date']); ?>. The lesson resources can be downloaded at<br/>
											<?php echo $dn['duration']>0?($_SESSION['uid']==$dn['teacher']?"On Request":"<a href='../classroom/res/lessons/".$dn['flip_url']."' target='_blank' class='btn btn-success btn-xs'>Flip</a><a href='../classroom/res/lessons/".$dn['chat_url']."' target='_blank' class='btn btn-info btn-xs'>Chat</a>"):"Download link not available"; ?>
											</p>

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
