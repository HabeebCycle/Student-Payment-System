<?php
include_once("header.php");

if (isset($_POST['memform'])) {
    // Initialize a session:

    if (empty($_POST['title'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $title = mysqli_real_escape_string($connection,$_POST['title']);
    }
	if (empty($_POST['message'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $message = mysqli_real_escape_string($connection,$_POST['message']);
    }
	$author = $_SESSION['name'];
	$dreg = date('d-m-Y ',time());

	if (empty($f)){
		mysqli_query($connection,"insert into notice (title,message,author,date) values ('$title','$message','$author','$dreg')");
		echo "<script>alert('New annoucement created successfully');</script>";
	}
}elseif(isset($_POST['memedt'])){
	// Initialize a session:

    if (empty($_POST['title'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $title = mysqli_real_escape_string($connection,$_POST['title']);
    }
	if (empty($_POST['message'])) {//if the address supplied is empty
        $f = 'fz0';
    } else {
        $message = mysqli_real_escape_string($connection,$_POST['message']);
    }
	$author = $_SESSION['name'];
	$dreg = date('d-m-Y ',time());
	if (empty($f)){
		$id = $_POST['memid'];
		mysqli_query($connection,"update notice set title='$title',message='$message',author='$author',date='$dreg' where id=$id");
		echo "<script>alert('Announcement updated successfully');</script>";
	}
}elseif(isset($_POST['memdel'])){
	$id = $_POST['memid'];
	mysqli_query($connection,"delete from notice where id=$id");
	echo "<script>alert('Announcement deleted successfully');</script>";
}
?>
<div class="right_col" role="main">
          <div class="">
		  <div class="clearfix"></div>

            <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><span class="fa fa-bullhorn"></span> Announcement<small>Details</small></h2>
					&nbsp;&nbsp;<?php if(isset($cor)){ ?><center>
				  <div style="text-align:center;width:30%;background-color:#ccc;border:2px #f00 solid; color:#a0a; font-family:times new roman; text-size:25px">
					<?php echo $cor; ?>
				  </div></center>
			  <?php } ?><?php if($_SESSION['usertype']!=1){ ?>
                    <ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".new-cem-sm"><i class="fa fa-bullhorn"><b> Add New Announcement</b></i></button>
						</li>
                    </ul>
			  <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30" >
                      Click 'Add New Announcement' button above to add a new notice. Click on the 'Edit' button beside each annoucement details to edit.
                    </p>


<div class="modal fade new-cem-sm" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title fa fa-bullhorn" id="myModalLabel2"> Add a new notice</h4>
			</div>
			<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="modal-body">
					<h5>Fill all the fields</h5>
					<input type="text" name="title" placeholder="Title" class="form-control" required="">
					<textarea name="message" placeholder="Write the details here" class="form-control" required=""></textarea>
					<input type="text" placeholder="<?php echo $_SESSION['name']; ?>" class="form-control" disabled>
					<div class="ln_solid"></div>
					<input type="hidden" name="memform" value="TRUE" />
				</div>
				<div class="modal-footer">
				  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Add Notice</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Date</th>
                          <th>Author</th>
                          <th>Notice</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from notice order by id desc");
						while($dn = mysqli_fetch_array($qnx)){
					  ?>
                        <tr>
                          <td><?php echo $dn['title']; ?></td>
                          <td><?php echo $dn['date']; ?></td>
                          <td><?php echo $dn['author']; ?></td>
						  <td><?php echo wordwrap(substr($dn['message'],0,70).' ...',50,"<br>\n"); ?></td>
                          <td>
							<button class="btn btn-success btn-xs" data-toggle="modal" data-target=".det-cem-sm-<?php echo $dn['id']; ?>">Details</button>
							<?php if($_SESSION['usertype']!=1){ ?>
							<button class="btn btn-warning btn-xs" data-toggle="modal" data-target=".edt-cem-sm-<?php echo $dn['id']; ?>">Edit</button>
							<?php } ?>
							<?php if($_SESSION['usertype']==3){ ?>
							<button class="btn btn-danger btn-xs" data-toggle="modal" data-target=".del-cem-sm-<?php echo $dn['id']; ?>">Delete</button>
							<?php } ?>
						  </td>
                        </tr>

						<div class="modal fade det-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:16px;text-align:left;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h3 class="modal-title" id="myModalLabel2"><?php echo $dn['title']; ?> </h3>
										</div>
										<div class="modal-body">
										<h5><?php echo $dn['author']; ?> on <?php echo $dn['date']; ?></h5>
											<p style="color:darkblue;"><?php echo $dn['message']; ?></p>

											<div class="ln_solid"></div>
										</div>
										<div class="modal-footer">
										  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
										</div>
									</div>
								</div>
							</div>
						<div class="modal fade edt-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:15px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Edit notice <?php echo $dn['title']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5>Fill all the fields</h5>
												Title:<input type="text" name="title" value="<?php echo $dn['title']; ?>" class="form-control" required="">
												Write the details <textarea name="message" class="form-control" required=""><?php echo $dn['message']; ?>"</textarea>
												<input type="text" placeholder="<?php echo $_SESSION['name']; ?>" class="form-control" disabled>
												<div class="ln_solid"></div>
												<input type="hidden" name="memedt" value="TRUE" />
												<input type="hidden" name="memid" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Save</button>
											  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal fade del-cem-sm-<?php echo $dn['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-sm" style="color:#000;font-family:Andalus;font-size:18px;text-align:justify;">
									<div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
										  </button>
										  <h4 class="modal-title" id="myModalLabel2">Delete notice <?php echo $dn['title']; ?></h4>
										</div>
										<form class="form-horizontal form-label-left" name="mosque" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
											<div class="modal-body">
												<h5></h5>
												<p style="color:purple;">Are you sure to delete <?php echo $dn['title']; ?></p>
												<div class="ln_solid"></div>
												<input type="hidden" name="memdel" value="TRUE" />
												<input type="hidden" name="memid" value="<?php echo $dn['id']; ?>" />
											</div>
											<div class="modal-footer">
											  <button type="submit" value="submit" name="submit" class="btn btn-primary submit" onclick="">Delete</button>
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
