<?php
require_once ('../../config/config.php');
include("phpfunc.php");
$roomkey=$_SESSION['roomkey'];
$q="SELECT * FROM lessons WHERE id LIKE '$roomkey'";
$result=mysqli_query($connection, $q) or die("Query failed. Error resolving roomkey. -".mysqli_error());
$row=mysqli_fetch_array($result);
if(mysqli_num_rows($result)==1)
{
	$title=$row['title'];
	$sss = $row['start'];
	$startDT=($sss==0?0:(time()-$sss));
}
echo "<b>". cutString($title,35) ."</b><br>Started: ". getStartTime($startDT) ;
?>
