<?php
	$data = $_POST['data'];
	$file = 'white_board.png';

	// remove "data:image/png;base64,"
	$uri =  substr($data,strpos($data,",")+1);

	// save to file
	file_put_contents('images/'.$file, base64_decode($uri));
	//echo $file; exit;
?>
