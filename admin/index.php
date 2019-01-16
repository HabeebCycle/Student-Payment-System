<?php include_once("header.php");
	$cel = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM courses where deleted=0 order by id desc limit 1"));
	$cem = mysqli_query($connection,"select * from courses where deleted=0");
	$inf = mysqli_query($connection,"select * from notice order by id desc limit 10");
	$cen = mysqli_num_rows($cem);
	$tot = mysqli_num_rows(mysqli_query($connection,"select * from student where deleted=0"));
	$tos = mysqli_num_rows(mysqli_query($connection,"select distinct student from payment where confirm=1 and valid=0"));
	$mos = mysqli_num_rows(mysqli_query($connection,"select distinct course from payment where confirm=1 and valid=0"));
	$fun = mysqli_num_rows(mysqli_query($connection,"select * from payment where confirm=1"));
	$fus = mysqli_num_rows(mysqli_query($connection,"select * from payment where confirm=1 and valid=0"));
	$usr = mysqli_num_rows(mysqli_query($connection,"select * from lessons where duration!=0"));
	$use = mysqli_num_rows(mysqli_query($connection,"select * from lessons"));
	$mor = mysqli_num_rows(mysqli_query($connection,"select * from feedback"));
	$moz = mysqli_num_rows(mysqli_query($connection,"select * from feedback where rep=1"));
	$sta = mysqli_num_rows(mysqli_query($connection,"select * from staff where deleted=0"));
	$mem = mysqli_num_rows(mysqli_query($connection,"select * from members where deleted=0"));
	$lop = ($tot>0?(int)(100*($tos)/$tot):0);
	$mop = ($cen>0?(int)(100*($mos)/$cen):0);
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"><a href='cemsum.php'>
              <center><span class="count_top"><i class="fa fa-university"></i> Number of Courses</span>
              <div class="count green"><?php echo $cen; ?></div>
              <span class="count_bottom">Latest <i class="green"> <?php echo ($cen>0?$cel['name']:'None'); ?> </i> </span></center></a>
            </div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"><a href='cemsum.php'>
              <center><span class="count_top"><i class="fa fa-ticket"></i> Registered Students</span>
              <div class="count green"><?php echo $tot; ?></div>
              <span class="count_bottom">Subscribers <i class="green"> <?php echo ($cen>0?$tos:0); ?> </i> </span></center></a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"><a href='funsum.php'>
              <center><span class="count_top"><i class="fa fa-area-chart"></i> Subscriptions </span>
              <div class="count green"><?php echo $fun; ?></div>
			  <span class="count_bottom">Valid <i class="green"> <?php echo $fus; ?> </i> </span>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>$0.00 </i> Yesterday</span> --></center></a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"><a href='poruse.php'>
              <center><span class="count_top"><i class="fa fa-user"></i> Number of Lessons</span>
              <div class="count green"><?php echo $use; ?></div>
			  <span class="count_bottom">Completed <i class="green"> <?php echo $usr; ?> </i> </span>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>$0.00 </i> Yesterday</span>--></center></a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"><a href='regmos.php'>
              <center><span class="count_top"><i class="fa fa-building"></i>Total FeedBack</span>
              <div class="count green"><?php echo $mor; ?></div>
			  <span class="count_bottom">Replied <b><i class="green"> <?php echo $moz; ?> </i></b> </span>
              <!--<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>$0.00 </i> Yesterday</span>--></center></a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"><a href='member.php'>
              <center><span class="count_top"><i class="fa fa-users"></i> Number of Members</span>
              <div class="count green"><?php echo $mem; ?></div></a><a href='worsta.php'>
			  <span class="count_bottom">Number of Staff <b><i class="green"> <?php echo $sta; ?> </i></b> </span></center></a>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>$0.00 </i> Yesterday</span>-->
            </div>
          </div>
          <!-- /top tiles -->

          <div class="row" style="font-family:Andalus;">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h3>Erudite Millennium Limited </h3>
                  </div>

                </div>

                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Recent Information</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">

                    <ul class="list-unstyled timeline widget">
					<?php
						while($dns = mysqli_fetch_array($inf)){
					?>
                      <li>
                        <div class="block">
                          <div class="block_content">
                            <h2 class="title">
                                              <a><?php echo $dns['title']; ?></a>
                                          </h2>
                            <div class="byline">
                              <span><?php echo $dns['date']; ?></span> by <a><?php echo $dns['author']; ?></a>
                            </div>
                            <p class="excerpt"><?php echo substr($dns['message'],0,100).' ...'; ?><?php if(strlen($dns['message'])>100){ ?>
							<a class="btn btn-xs btn-info" data-toggle="modal" data-target=".ann-modal-<?php echo $dns['id']; ?>">Read&nbsp;More</a> <?php } ?>
							</p>
                          </div>
                        </div>
                      </li>
					  <div class="modal fade ann-modal-<?php echo $dns['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-xs" style="color:#000;font-family:Andalus;font-size:16px;text-align:left;">
								<div class="modal-content">
									<div class="modal-header">
									  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
									  </button>
									  <h3 class="modal-title" id="myModalLabel2"><?php echo $dns['title']; ?> </h3>
									</div>
									<div class="modal-body">
									<h5><?php echo $dns['author']; ?> on <?php echo $dns['date']; ?></h5>
										<p style="color:darkblue;"><?php echo $dns['message']; ?></p>

										<div class="ln_solid"></div>
									</div>
									<div class="modal-footer">
									  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
									</div>
								</div>
							</div>
						</div>
					  <?php } ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>


            <div class="col-md-6 col-sm-6 col-xs-12">
              <!--<div class="x_panel tile fixed_height_320">-->
			  <div class="x_panel">
                <div class="x_title">
                  <h2>Lessons Completed</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
				  <?php
					while($dn = mysqli_fetch_array($cem)){
						$ddd = mysqli_num_rows(mysqli_query($connection,"select * from lessons where duration!=0 and course=".$dn['id']));
						$dde = mysqli_num_rows(mysqli_query($connection,"select * from lessons where course=".$dn['id']));
						$t4 = $dde==0?0:(($ddd/$dde)*100);
				  ?>
                  <div class="widget_summary">
                    <div class="w_left w_25">
                      <span><?php echo $dn['code']; ?></span>
                    </div>
                    <div class="w_center w_55">
                      <div class="">
						<div class="progress progress_sm" style="width: 100%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $t4; ?>"></div>
                        </div>
                      </div>
                    </div>
                    <div class="w_left w_20">
                      <span> &nbsp; <?php echo (int)($t4).'%'; ?></span>
                    </div>
                    <div class="clearfix"></div>
                  </div>
					<?php } ?>

                </div>
              </div>
            </div>
				</div>

                <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2>Subscription Overview</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
                      <p> Users' Subsription (<?php echo $tos.'/'.$tot; ?>)</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 100%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $lop; ?>"></div>
                        </div>
                      </div>
                    </div>
                    <div>
                      <p>Courses' Subscription (<?php echo $mos.'/'.$cen; ?>)</p>
                      <div class="">
                        <div class="progress progress_sm" style="width: 100%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $mop; ?>"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-6">

                  </div>

                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />

          <div class="row">

          </div>



        </div>
        <!-- /page content -->

        <?php include_once("footer.php"); ?>

  </body>
</html>
