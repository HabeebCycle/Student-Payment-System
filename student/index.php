<?php include_once("header.php");
	  require_once("../../config/site_func.php");
	$cel = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM courses where deleted=0 order by id desc limit 1"));
	$cem = mysqli_query($connection,"select * from courses where deleted=0");
	$inf = mysqli_query($connection,"select * from notice order by id desc limit 10");
	$cen = mysqli_num_rows($cem);
	$mor = mysqli_query($connection,"select distinct course from payment where confirm=1 and valid=0 and student=".$_SESSION['uid']);
	$mos = mysqli_num_rows($mor);
	$mdx = mysqli_query($connection,"select distinct course from payment where student=".$_SESSION['uid']);
	$mox = mysqli_num_rows($mdx);
	$usr = mysqli_num_rows(mysqli_query($connection,"select * from lessons where duration!=0 and course in (select course from payment where confirm=1 and valid=0 and student=".$_SESSION['uid'].")"));
	$use = mysqli_num_rows(mysqli_query($connection,"select * from lessons where course in (select course from payment where confirm=1 and valid=0 and student=".$_SESSION['uid'].")"));
	$moj = mysqli_num_rows(mysqli_query($connection,"select * from feedback where student=".$_SESSION['uid']));
	$moz = mysqli_num_rows(mysqli_query($connection,"select * from feedback where rep=1 and student=".$_SESSION['uid']));
	//$lop = ($tot>0?(int)(100*($tos)/$tot):0);
	$mop = ($cen>0?(int)(100*($mos)/$cen):0);
	$sfunc = new SiteFunction();
?>

<script>

  function payWithPaystack(price, course, subr){
	  var amt = price * 100;
	  var refs = '<?php echo $_SESSION['uid'].$sfunc->get_rand_id(10).time(); ?>';
	  alert("Please wait while we connect to PayStack server. Click OK and wait...");

    var handler = PaystackPop.setup({
      key: 'pk_test_598971ef046828b570e692ac7ba5ed1ca54a25ed', //Replace with your live key.
      email: '<?php echo $_SESSION['email']; ?>',
      amount: amt,
	  ref: ''+refs,//ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "<?php echo $_SESSION['phone']; ?>"
            }
         ]
      },
      callback: function(response){
          //alert('success. transaction ref is ' + response.reference);
		  verifyPayment(response.reference, course, subr);
      },
      onClose: function(){
          alert('window closed');
      }
    });
    handler.openIframe();
  }

  function verifyPayment(reference,course,subr){
	  var sendto = "subs.php?act=verify&ref="+reference+"&course="+course+"&sub="+subr;
	if(window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET",sendto,false);
		xmlhttp.send(null);
	}
	else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		xmlhttp.open("GET",sendto,false);
		xmlhttp.send();
	}
	//If an error occurs the 'send.php' file send`s the number of the error and based on that number a message is displayed
	alert(String(xmlhttp.responseText));
	location.reload(true);
  }

</script>
        <!-- page content -->
        <div class="right_col" role="main">
		<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 class="blue">INFORMATION</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php
                  	echo "<div class='fb-like' data-href='https://www.facebook.com/ERUDITEclp' data-width='800' data-layout='standard' data-action='like' data-size='large' data-show-faces='false' data-share='true'></div>";
                  ?>

                  </div>
                 </div>
                </div>
              </div>


          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count"><a href='cemsum.php'>
              <center><span class="count_top"><i class="fa fa-university"></i> Available Courses</span>
              <div class="count green"><?php echo $cen; ?></div>
              <span class="count_bottom">Latest <i class="green"> <?php echo ($cen>0?$cel['name']:'None'); ?> </i> </span></center></a>
            </div>
			<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count"><a href='cemsum.php'>
              <center><span class="count_top"><i class="fa fa-ticket"></i> Enrolled Courses</span>
              <div class="count green"><?php echo $mox; ?></div>
              <span class="count_bottom">Active Subscription <i class="green"> <?php echo ($mox>0?$mos:0); ?> </i> </span></center></a>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count"><a href='poruse.php'>
              <center><span class="count_top"><i class="fa fa-user"></i> Number of Lessons</span>
              <div class="count green"><?php echo $use; ?></div>
			  <span class="count_bottom">Completed <i class="green"> <?php echo $usr; ?> </i> </span>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>$0.00 </i> Yesterday</span>--></center></a>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count"><a href='regmos.php'>
              <center><span class="count_top"><i class="fa fa-building"></i>Total FeedBack</span>
              <div class="count green"><?php echo $moj; ?></div>
			  <span class="count_bottom">Replied <b><i class="green"> <?php echo $moz; ?> </i></b> </span>
              <!--<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>$0.00 </i> Yesterday</span>--></center></a>
            </div>
		  </div>
          <!-- /top tiles -->

		  <div class="row" style="font-family:Andalus;">
			<div class="col-md-12">
				<div class="x_panel">
                  <div class="x_title">
                    <h2 class="blue">Course Cards <span class='small'> (Click on preferred card to subscribe)</span></h2>
					  <ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
					  </ul>
					  <div class="clearfix"></div>
					</div>
				  <div class="x_content">
				  <?php

						while($dnc = mysqli_fetch_array($cem)){
							$fac = mysqli_query($connection, "select distinct teacher from lessons where course=".$dnc['id']);
							$num = mysqli_num_rows(mysqli_query($connection,"select * from payment where confirm=1 and valid=0 and student=".$_SESSION['uid']." and course=".$dnc['id']));
							$reff = mysqli_query($connection,"select * from payment where confirm=1 and valid=1 and student=".$_SESSION['uid']." and course=".$dnc['id']);
							$nux = mysqli_num_rows($reff);
							if($nux>0){$reference = mysqli_fetch_array($reff)['ref'];}
					?>
					<div class="modal fade bs<?php echo $dnc['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-sm">
							  <div class="modal-content">
								<div class="modal-header">
								  <a class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span>
								  </a>
								  <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-money"></i> Subscription Confirmation</h4>
								</div>
								<div class="modal-body">
								  <h4><b>Subscribe to <?php echo $dnc['name']." (".$dnc['code'].")"; ?></b></h4>
								<form action="" method="post">
									<script src="https://js.paystack.co/v1/inline.js"></script>
									<?php if($num==0){ ?>
								  <p>Are you sure you want to <?php echo $nux>0?"renew your subscription for":"subscribe to "; ?> <?php echo $dnc['name']; ?> course card? </p>
								    <?php }else{echo "<p>You have subscribed for this course</p>";} ?>
									<b>Price</b><br/>
									<p><?php echo $dnc['deno'].$dnc['price'].' '.($dnc['sub']==0?'Once':'Monthly');; ?> .</p>
									<b>Description</b><br/>
									<?php echo $dnc['descr']; ?><br/><br/>
									<b>Facilitator</b><br/>
									<?php
											$output = "";
											while($rr = mysqli_fetch_array($fac)){
												$fk = mysqli_fetch_array(mysqli_query($connection,"select name,info from admin where usertype=2 and id=".$rr['teacher']));
												$output = $output."<b>".$fk['name']."</b><br/>".$fk['info']."<br/><br/>";
											}
											echo "<p>".$output."</p>";
											?>
								</div>
								<input type="hidden" id="subcourse<?php echo $dnc['id']; ?>" name="subcourse" value="TRUE" />
								<input type="hidden" id="vid<?php echo $dnc['id']; ?>" name="vid" value="<?php echo $dnc['id']; ?>" />
								<div class="modal-footer">
									<a class="btn btn-default" data-dismiss="modal"><?php echo $num==0?"Cancel":"OK"; ?></a>
									<?php if($num==0){ ?>
									<button type="button" id="btn<?php echo $dnc['id']; ?>" class="btn btn-primary" onclick="payWithPaystack(<?php echo $dnc['price']; ?>,<?php echo $dnc['id']; ?>,<?php echo $nux>0?"1":"0"; ?>);" title='Clicking this means you agree to our terms & conditions'><?php echo $nux>0?"Renew":"Subscribe"; ?></button>
									<?php } ?>
								</div>
								</form>
							  </div>
							</div>
						</div>

						<a  href="#" data-toggle="modal" data-target=".bs<?php echo $dnc['id']; ?>" style="font-size:18px; color:00f;" title='Click this card to subscribe' id="nar">
							<center><div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="tile-stats alert-<?php echo($num==0?"info":"success"); ?>">
							  <div class="red"><i class="fa fa-university "></i> <?php echo $dnc['name']." (".$dnc['code'].")"; ?></div>
							  <p style="font-size:16px;"><?php echo $dnc['deno'].$dnc['price'].' '.($dnc['sub']==0?'Once':'Monthly'); ?>. <br/><b><?php echo($num==0?($nux>0?"Renew Now":"Enroll Now"):"[ACTIVE]"); ?></b></p>
							</div>
							</div></center>
						 </a>
					<?php } ?>

				</div>
				</div>
			</div>
		  </div>

          <div class="row" style="font-family:Andalus;">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h2>Dashboard Overview </h2>
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
				  if($mos>0){
					while($dn = mysqli_fetch_array($mdx)){
						$ddd = mysqli_num_rows(mysqli_query($connection,"select * from lessons where duration!=0 and course=".$dn['course']));
						$dde = mysqli_num_rows(mysqli_query($connection,"select * from lessons where course=".$dn['course']));
						$cde = mysqli_fetch_array(mysqli_query($connection,"select * from courses where id=".$dn['course']));
						$t4 = $dde==0?0:(($ddd/$dde)*100);
				  ?>
                  <div class="widget_summary">
                    <div class="w_left w_25">
                      <span><?php echo $cde['code']; ?></span>
                    </div>
                    <div class="w_center w_55">
                      <div class="">
						<div class="progress progress_sm" style="width: 100%;">
                          <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $t4; ?>"></div>
                        </div>
                      </div>
                    </div>
                    <div class="w_left w_20">
                      <span> &nbsp; <?php echo (int)$t4.'%'; ?></span>
                    </div>
                    <div class="clearfix"></div>
                  </div>
				  <?php }}else{ echo "No Subscription";} ?>

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
                      <p>Active Subscription (<?php echo $mos.'/'.$cen; ?>)</p>
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
