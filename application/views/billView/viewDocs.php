<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<div align ="center">


<?php
					$sl=1;
					foreach($docs->result() as $row)
					{ 
?>
					
						<a href="ftp://bill:bill007@192.168.1.117/BILL/<?php echo $row->doc_file; ?>">document-<?php echo $sl?></a><br/><br/>
				

<?php				$sl=$sl+1;
					}
				?>
</div>
</html>