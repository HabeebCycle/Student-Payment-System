<?php
require_once ('../../config/config.php');
if(!isset($_SESSION['islog']) and !$_SESSION['islog']){
	header('Location: ../../');
}
header('Content-type: application/xml');
@$act = $_GET['act'];
@$idr = $_GET['idr'];

if(isset($act)){
	if($act == 'lott'){
		$result = "<state>";
		$res = mysqli_fetch_array(mysqli_query($connection,"select name from admin where id=(select teacher from courses where id=$idr)"))['name'];
		$result.="<cordinate>".$res."</cordinate></state>";
		print $result;
		//echo "<lotnumber>Working</lotnumber>";
	}
}else{

}

?>
