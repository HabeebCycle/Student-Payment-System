<?php
//mktime(hour,minute,second,month,day,year);
list($d,$m,$y,$hh,$mm)=explode('/','20/09/2017/17/30');
echo mktime($hh+1,$mm,0,$m,$d,$y);echo "<br/>";
echo mktime($hh+25,$mm,0,$m,$d,$y);echo "<br/>";
echo (24*60*60);echo "<br/>";
echo mktime($hh+24,$mm,0,$m,$d,$y) - mktime($hh,$mm,0,$m,$d,$y);
echo "<br/>";
echo time();echo "<br/>";
echo date('D d m, Y, H:i:s',time());echo "<br/>";

echo "UPDATE lessons SET duration=(".time()." - start) WHERE id LIKE 'rk'";

?><br/>
<script>
function show(r,c,reff){
	alert("subs.php?act=verify&ref="+r+"&course="+c+"&ref="+reff);
}
</script>
<button onclick="show(<?php echo 2000; ?>,<?php echo 1; ?>,<?php echo 0; ?>);">SHOW</button>
