<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php if(!empty($epin_data)){ ?>
	    <table>
			<?php 
				$i = 0; 
		    	foreach($epin_data  as $key => $item){
		    		if(isset($epin_data[$i]->pin)){
		    ?>
		    	<tr style="font-size: 14px;">
		    		<?php if(isset($epin_data[$i]->pin)){ ?>
			    		<td style="border: 1px solid;">
				            <span>
				            	<?php echo isset($epin_data[$i]->user->firstname) ? $epin_data[$i]->user->firstname:''; ?>

				            	<?php echo isset($epin_data[$i]->user->lastname) ? $epin_data[$i]->user->lastname:''; ?>
				            	<?php echo isset($epin_data[$i]->mobile_network) ? $epin_data[$i]->mobile_network:''; ?>
				            	<?php echo isset($epin_data[$i]->amount) ? $epin_data[$i]->amount:''; ?>
				            </span>
				            <br>
				            <span>
				            	S/N:<?php echo isset($epin_data[$i]->sno) ? $epin_data[$i]->sno:''; ?>
				            </span>
				            <br>
				            <span>
				            	Pin:<?php echo isset($epin_data[$i]->pin) ? $epin_data[$i]->pin:''; ?>
				            </span>
				            <br>
				            <span>
				            	Date:<?php echo isset($epin_data[$i]->transaction_date) ? $epin_data[$i]->transaction_date:''; ?>
				            </span>
				            <br>
				            <?php $i=$i+1; ?>
			    		</td>
		    		<?php } ?>
		    		<?php if(isset($epin_data[$i]->pin) && $epin_data[$i]->pin){ ?>
			    		<td style="border: 1px solid;">
				            <span>
				            	<?php echo isset($epin_data[$i]->user->firstname) ? $epin_data[$i]->user->firstname:''; ?>

				            	<?php echo isset($epin_data[$i]->user->lastname) ? $epin_data[$i]->user->lastname:''; ?>
				            	<?php echo isset($epin_data[$i]->mobile_network) ? $epin_data[$i]->mobile_network:''; ?>
				            	<?php echo isset($epin_data[$i]->amount) ? $epin_data[$i]->amount:''; ?>
				            </span>
				            <br>
				            <span>
				            	S/N:<?php echo isset($epin_data[$i]->sno) ? $epin_data[$i]->sno:''; ?>
				            </span>
				            <br>
				            <span>
				            	Pin:<?php echo isset($epin_data[$i]->pin) ? $epin_data[$i]->pin:''; ?>
				            </span>
				            <br>
				            <span>
				            	Date:<?php echo isset($epin_data[$i]->transaction_date) ? $epin_data[$i]->transaction_date:''; ?>
				            </span>
				            <br>
				            <?php $i=$i+1; ?>
			    		</td>
		    		<?php } ?>
		    		<?php if(isset($epin_data[$i]->pin)){ ?>
			    		<td style="border: 1px solid;">
				            <span>
				            	<?php echo isset($epin_data[$i]->user->firstname) ? $epin_data[$i]->user->firstname:''; ?>

				            	<?php echo isset($epin_data[$i]->user->lastname) ? $epin_data[$i]->user->lastname:''; ?>
				            	<?php echo isset($epin_data[$i]->mobile_network) ? $epin_data[$i]->mobile_network:''; ?>
				            	<?php echo isset($epin_data[$i]->amount) ? $epin_data[$i]->amount:''; ?>
				            </span>
				            <br>
				            <span>
				            	S/N:<?php echo isset($epin_data[$i]->sno) ? $epin_data[$i]->sno:''; ?>
				            </span>
				            <br>
				            <span>
				            	Pin:<?php echo isset($epin_data[$i]->pin) ? $epin_data[$i]->pin:''; ?>
				            </span>
				            <br>
				            <span>
				            	Date:<?php echo isset($epin_data[$i]->transaction_date) ? $epin_data[$i]->transaction_date:''; ?>
				            </span>
				            <br>
				            <?php $i=$i+1; ?>
			    		</td>
		    		<?php } ?>
		    		<?php if(isset($epin_data[$i]->pin)){ ?>
			    		<td style="border: 1px solid;">
				            <span>
				            	<?php echo isset($epin_data[$i]->user->firstname) ? $epin_data[$i]->user->firstname:''; ?>

				            	<?php echo isset($epin_data[$i]->user->lastname) ? $epin_data[$i]->user->lastname:''; ?>
				            	<?php echo isset($epin_data[$i]->mobile_network) ? $epin_data[$i]->mobile_network:''; ?>
				            	<?php echo isset($epin_data[$i]->amount) ? $epin_data[$i]->amount:''; ?>
				            </span>
				            <br>
				            <span>
				            	S/N:<?php echo isset($epin_data[$i]->sno) ? $epin_data[$i]->sno:''; ?>
				            </span>
				            <br>
				            <span>
				            	Pin:<?php echo isset($epin_data[$i]->pin) ? $epin_data[$i]->pin:''; ?>
				            </span>
				            <br>
				            <span>
				            	Date:<?php echo isset($epin_data[$i]->transaction_date) ? $epin_data[$i]->transaction_date:''; ?>
				            </span>
				            <br>
			    		</td>
		    		<?php } ?>
		    	</tr>
		    <?php } ?>
	    	<?php 
	    		$i=$i+1;
	    		if(count($epin_data) == $i)
	    		{
	    			break;
	    		}
	    	?>
    		<?php } ?>
	    </table>
    <?php } ?>
</body>
</html>