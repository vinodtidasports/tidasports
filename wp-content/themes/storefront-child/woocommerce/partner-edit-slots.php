<section class="available_slots_configuration_section">
	<div class="available_slots_configuration_wrapper">
	  <div class="available_slots_configuration_table_wrapper">	  
		<form method="POST" class="available_slots" name="available_slots_<?php echo $resource_id; ?>" id="available_slots_<?php echo $resource_id; ?>">
			<!--<div class="slot_form_field"><label for="slot_type">Slot Type</label>
			<select class="slot_type" name="slot_type">
			<option value="custom:daterange">Date Time</option>
			<option value="time">Time</option></select></div>-->
			<div class="slot_form_field"><label for="slot_type">Select Date</label>
			<input type="date" name="from_date" class="time_start" min="<?php echo date('Y-m-d'); ?>" /></div>
			<div class="slot_form_field"><label for="select_slot">Select Slot</label>
			<select name="select_slot" class="select_slot" placeholder="select_slot" ><option>-Select Slot-</option></select>
			</div>
			<!--<input type="number" name="priority" min="1" max="10" value="9" />-->
			<input type="hidden" name="resource_id" class="resource_id" value="<?php  echo $resource_id;  ?>" />
			<input type="hidden" name="product_id" class="product_id" value="<?php  echo $product_id; ?>" />
			<input type="hidden" name="action" class="action" value="action_new_slot" />
			<input type="submit" name="add_new_slot" class="add_new_slot" value="Disable Slot" />
		</form>
	    <table class="available_slots_configuration">
		<tr class="slot row">
			<!--<td class="slot_type col-xs-3">Type</td>-->
			<td class="slot_from col-xs-3">Slot</td>
			<!--<td class="slot_to col-xs-3">To</td>-->
			<td class="slot_bookable col-xs-3">Booking</td>
			<!--<td class="slot_priority col-xs-3">Priority</td>-->
			<td class="slot_priority col-xs-3">Action</td>
		</tr>
		<?php //echo '<pre>'; print_r($slots); echo '</pre>';
		foreach($slots as $key=>$slot){ 
		?>
			<tr class="slot row">
				<!--<td class="slot_type col-xs-3"><?php echo ucfirst($slot['type']); ?></td>-->
				<td class="slot_from col-xs-3"><?php if(isset($slot['from_date'])){ echo $slot['from_date'].' '; echo date(
"g:i a",strtotime($slot['from'])); }else if($slot['type'] == 'custom'){ echo $slot['from']; }else{  echo date(
"g:i a",strtotime($slot['from'])); }
 ?></td>
				<!--<td class="slot_to col-xs-3"><?php if(isset($slot['to_date'])){ echo $slot['to_date'].' '; 
				echo date("g:i a",strtotime($slot['to'])); }else if($slot['type'] == 'custom'){ echo $slot['to']; } ?></td>-->
				<td class="slot_bookable col-xs-3"><?php echo ucfirst($slot['bookable']); ?></td>
				<!--<td class="slot_priority col-xs-3"><?php echo $slot['priority']; ?></td>-->
				<?php 
				if(isset($slot['added_by']) || $slot['type'] == 'custom:daterange' || $slot['type'] == 'custom') {
				?>				
				<td class="slot_priority col-xs-3"><a href="javascript:void:(0);" class="delete_slot" data-resource="<?php echo
 $resource_id; ?>" data-id="<?php echo $product_id; ?>" data-key="<?php echo $key; ?>">Delete</a></td>
				<?php
				}else{
				?>
				<?php if(($slot['bookable']) == 'yes') { ?>
				<td class="slot_priority col-xs-3"><!--<a href="javascript:void:(0);" class="disable_slot" data-resource="<?php 
echo $resource_id; ?>" data-id="<?php echo $product_id; ?>" data-key="<?php echo $key; ?>">Disable</a>--></td>
				<?php }else{ ?>
				<td class="slot_priority col-xs-3"><!--<a href="javascript:void:(0);" class="enable_slot" data-resource="<?php echo
 $resource_id; ?>" data-id="<?php echo $product_id; ?>" data-key="<?php echo $key; ?>">Enable</a>--></td>
				<?php }} ?>
			</tr>
		<?php
		}
		?>
	    </table>
	  </div>
	</div>
</section>
