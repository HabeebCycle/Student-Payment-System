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
                    <h2>List of Erudite Members<small>Details</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30" >
                      List of members under Erudite.
                    </p>


                    <table id="datatable-responsive" class="table table-striped jambo_table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Member name</th>
                          <th>Phone number</th>
                          <th>Email</th>
						  <th>Department</th>
                          <th>Position</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
						$qnx = mysqli_query($connection,"select * from members where deleted=0");
						while($dn = mysqli_fetch_array($qnx)){
							$tr1 = mysqli_fetch_array(mysqli_query($connection,"select name from department where id=".$dn['dept']));

					  ?>
                        <tr>
                          <td><?php echo $dn['name']; ?></td>
                          <td><?php echo $dn['phone']; ?></td>
                          <td><?php echo $dn['email']; ?></td>
						  <td><?php echo $tr1['name']; ?></td>
                          <td><?php echo $dn['position']; ?></td>
                        </tr>

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
