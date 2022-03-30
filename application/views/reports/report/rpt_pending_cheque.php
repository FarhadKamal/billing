
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report</title>
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){

<?php 

for($a=1;$a<=999;$a=$a+1)
{
  ?>

$('#x<?php echo $a; ?>').click(function() {
	$('#y<?php echo $a; ?>').hide();
});

<?php 
}

?>


});
</script>
</head>
<body> <?php echo $table; ?>

</body>

</html>

