<section class="available_slots_configuration_section">
	<div class="available_slots_configuration_wrapper">
	  <div class="available_slots_configuration_table_wrapper">	  
		<form method="POST" class="available_slots" name="available_slots_<?php echo $resource_id; ?>" id="available_slots_<?php echo $resource_id; ?>">
			<div class="slot_form_field"><label for="slot_type">Slot Type</label>
			<select class="slot_type" name="slot_type">
			<option value="custom:daterange">Date Time</option>
			<option value="time">Time</option></select></div>
			<div class="slot_form_field"><label for="slot_type">Select From DateTime</label>
			<input type="datetime-local" name="from_date" class="time_start" id="time_start" /></div>
			<div class="slot_form_field"><label for="slot_type">Select To DateTime</label>
			<input type="datetime-local" name="to_date" class="time_end" id="time_end" /></div>
			<div class="slot_form_field"><label for="slot_type">Select Bookable Type</label>		
			<select type="text" name="bookable" class="bookable" placeholder="Bookable">
				<option value="no">No</option>
				<option value="yes">Yes</option>
			</select></div>
			<input type="number" name="priority" min="1" max="10" value="9" />
			<input type="hidden" name="resource_id" class="resource_id" value="<?php  echo $resource_id;  ?>" />
			<input type="hidden" name="product_id" class="product_id" value="<?php  echo $product_id; ?>" />
			<input type="hidden" name="action" class="action" value="action_new_slot" />
			<input type="submit" name="add_new_slot" class="add_new_slot" value="Disable Slot" />
		</form>
	    <table class="available_slots_configuration">
		<tr class="slot row">
			<td class="slot_type col-xs-3">Type</td>
			<td class="slot_from col-xs-3">From</td>
			<td class="slot_to col-xs-3">To</td>
			<td class="slot_bookable col-xs-3">Bookable</td>
			<td class="slot_priority col-xs-3">Priority</td>
			<td class="slot_priority col-xs-3">Action</td>
		</tr>
		<?php 
		foreach($slots as $key=>$slot){ 
		?>
			<tr class="slot row">
				<td class="slot_type col-xs-3"><?php echo ucfirst($slot['type']); ?></td>
				<td class="slot_from col-xs-3"><?php if(isset($slot['from_date'])){ echo $slot['from_date'].' '; } echo date(
"g:i a",strtotime($slot['from'])); ?></td>
				<td class="slot_to col-xs-3"><?php if(isset($slot['to_date'])){ echo $slot['to_date'].' '; }  echo date("g:i a"
,strtotime($slot['to'])); ?></td>
				<td class="slot_bookable col-xs-3"><?php echo $slot['bookable']; ?></td>
				<td class="slot_priority col-xs-3"><?php echo $slot['priority']; ?></td>
				<?php 
				if(isset($slot['added_by'])) {
				?>				
				<td class="slot_priority col-xs-3"><a href="javascript:void:(0);" class="delete_slot" data-resource="<?php echo
 $resource_id; ?>" data-id="<?php echo $product_id; ?>" data-key="<?php echo $key; ?>">Delete</a></td>
				<?php
				}else{
				?>
				<?php if(($slot['bookable']) == 'yes') { ?>
				<td class="slot_priority col-xs-3"><a href="javascript:void:(0);" class="disable_slot" data-resource="<?php 
echo $resource_id; ?>" data-id="<?php echo $product_id; ?>" data-key="<?php echo $key; ?>">Disable</a></td>
				<?php }else{ ?>
				<td class="slot_priority col-xs-3"><a href="javascript:void:(0);" class="enable_slot" data-resource="<?php echo
 $resource_id; ?>" data-id="<?php echo $product_id; ?>" data-key="<?php echo $key; ?>">Enable</a></td>
				<?php }} ?>
			</tr>
		<?php
		}
		?>
	    </table>
	  </div>
	</div>
</section>

<script>
    
    /*function getFormattedDate(date) {
        //return date.toISOString().slice(0, date.toISOString().lastIndexOf(":"));
	return date.toISOString().slice(0, 16);
    }*/
/*function getFormattedDate(date) {
    return date.toLocaleString('en-US', { timeZone: 'Asia/Kolkata' });
}*/
function getFormattedDate(date) {
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var day = date.getDate().toString().padStart(2, '0');
        var hours = date.getHours().toString().padStart(2, '0');
        var minutes = date.getMinutes().toString().padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    var fromDateInput = document.getElementById("time_start");
    var toDateInput = document.getElementById("time_end");
    var now = new Date();
    fromDateInput.min = getFormattedDate(now);
    toDateInput.min = getFormattedDate(now);

    function updateToDateMin() {
        var now = new Date();

	if (new Date(fromDateInput.value) < now) {
		//console.log(getFormattedDate(now));
		//console.log(now);
            fromDateInput.value = getFormattedDate(now);
            toDateInput.min = getFormattedDate(now);
            alert("Please Select Future Time");
        } else {
            var fromDateValue = new Date(fromDateInput.value);
            toDateInput.min = getFormattedDate(fromDateValue);
            if (new Date(toDateInput.value) < fromDateValue) {
                toDateInput.value = getFormattedDate(fromDateValue);
            }
        }
    }

    fromDateInput.addEventListener('change', updateToDateMin);
    toDateInput.addEventListener('change', function () {
      //console.log("Change Event is working");
	
      var fromDateValue = new Date(fromDateInput.value);
      var toDateValue = new Date(toDateInput.value);
      var now = new Date();
	//console.log(fromDateValue, toDateValue);

      if (toDateValue < now || toDateValue < fromDateValue) {
	if(fromDateValue != "Invalid Date"){	
          toDateInput.value = fromDateValue > now ? fromDateInput.value : getFormattedDate(now); ;
          alert("To Date Can't be before from Date");
	}else{
	  toDateInput.value = getFormattedDate(now);
	  alert("To Date Can't be before now");
	}
      }
    });


    if (fromDateInput.value) {
        updateToDateMin();
    }
</script>
