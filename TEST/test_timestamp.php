<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 


$ts = array(1,'1','1.0','1.1','0xFF','0123','01090','-1000000','+1000000','2147483648','-2147483647',2147483647,-2147483647,mktime(),(string)(mktime()),date('r',mktime()));
foreach($ts as $t){
	echo '<br>'.date('r',(int)$t);
	echo ' : '.strtotime(date('r',(int)($t))).' == '.$t;
	if(strtotime(date('r',(int)($t))) === $t)
		echo '<br>YES';
	else
		echo '<br>NONO';
}
?>
</body>
</html>
