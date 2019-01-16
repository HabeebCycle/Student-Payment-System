<?php
require_once ('../../config/config.php');
if(!isset($_SESSION['islog']) and !$_SESSION['islog']){
	header('Location: ../../');
}

?>

<html>
<head>
<style type="text/css">

</style>
</head>
<body>
<?php
if(isset($_GET['pid'])) {
$pid=trim($_GET['pid']);
if($pid==""){echo "Hohoho! You missed select the participant!"; exit;}

$q="SELECT * FROM participant_details WHERE participant_id LIKE '$pid'";
$result=mysqli_query($connection, $q) or die("Query failed. - ".mysqli_query());
if(mysqli_num_rows($result)<1) {echo "Participant does not exist. ";exit;}
$row=mysqli_fetch_array($result);
$name=$row['prt_name'];
$typ = $row['prt_type']==1?"admin":"student"; //1 - moderator; 2 - student
$res = mysqli_fetch_array(mysqli_query($connection, "select * from $typ where id=".$row['user_id']));

if($res['gender']==2){$gender="Female";}else {$gender="Male";}
$prt_email=trim($res['email']);
$fname=$res['name'];
$location=$row['prt_geo_location'];
$smapURL="";
if(trim($location)!=""){

$smapURL="<img border='0' src='http://maps.google.com/maps/api/staticmap?center=".$location."&size=120x120&maptype=terrain&sensor=true&markers=color:blue|label:ABBD|$location'>";
}

echo "<table width='98%' cellpadding='2' class='floatl'  style='font-family:tahoma,arial; font-size:13px;'>
        <tr>
	        <td rowspan='5' width='20'>$smapURL</td>
          <td width='100' valign='top'><b>Name:</b></td>
          <td>$fname</td>
		</tr>
        <tr>
          <td><b>Gender:</b></td>
          <td>$gender</td>
        </tr>
        <tr>
        <td><b>Username:</b></td>
        <td>$name</td>
        </tr>
        <tr>
        <td><b>Email:</b></td>
        <td>$prt_email</td>
        </tr>
        <tr>


    </table>
";
}
else {
	echo "Click on a participant to view its details.";
}
?>
</body></html>
