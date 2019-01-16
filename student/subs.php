<?php
include('../../config/config.php');
require 'paystack/Paystack.php';

if(isset($_SESSION['slog']) and $_SESSION['slog']){
	if(isset($_GET['act'])){
		if($_GET['act'] == 'verify'){
			$reference = $_GET['ref'];
			$course = $_GET['course'];
			$sub = $_GET['sub'];

			$paystack = new Paystack('sk_test_625dc7c0aa3e1d0c21d210296446f3433c468c41');
			$trx = $paystack->transaction->verify(['reference'=>$reference]);
			if(!$trx->status){
				exit($trx->message);
			}
			if('success' == $trx->data->status){
				$student = $_SESSION['uid']; $date = time();
				if($sub == 0){
					mysqli_query($connection, "insert into payment (student,course,dates,ref,confirm,valid) values ($student,$course,'$date','$reference',1,0)");
					echo "Transaction $reference was successful. You have successfully subscribed to the course";
				}else{
					$dcc = mysqli_fetch_array(mysqli_query($connection,"select * from payment where course=$course and student=$student"));
					$adate =  $dcc['date']."#%".$date; $aref = $dcc['ref']."#%".$reference;
					mysqli_query($connection,"update payment set dates='$date', ref='$ref', confirm=1, valid=0 where student=$student and course=$course");
					echo "Transaction was successful. You have successfully renewed your subscription to the course.";
				}
			}else{
				echo "Transaction $reference was unsuccessful. Please try again";
			}

		}
	}
}
?>
