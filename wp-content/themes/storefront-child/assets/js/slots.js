jQuery(function() {
	jQuery(".add_new_slot").on("click", function(e) {
		e.preventDefault();
		let form = jQuery(this).closest("form");
		jQuery.ajax({
		  type: "POST",
		  dataType: "json",
		  url: params.ajaxurl,
		  data: form.serialize(),
		  beforeSend: function () {},
		  success: function (data) { 
		  },
		  error: function (xhr, textStatus, errorThrown) {},
		  complete: function (data) { 
			alert(data.responseJSON[0].slot);
			window.location.reload();
		  },
		});
	});
	jQuery(".bookable").on("change", function (e){
		let slot_form = jQuery(this).closest("form");
		if(jQuery(this).val() == 'no'){
			jQuery('#' + slot_form.attr('id') + ' input[name="add_new_slot"]').val('Disable Slot');
		}else{
			jQuery('#' + slot_form.attr('id') + ' input[name="add_new_slot"]').val('Enable Slot');
		}
	});
	jQuery(".slot_type").on("change", function (e){
		let slot_form = jQuery(this).closest("form");
		if(jQuery(this).val() == 'time'){
			jQuery('#' + slot_form.attr('id') + ' input[type="datetime-local"]').attr('type','time');
		}else{
			jQuery('#' + slot_form.attr('id') + ' input[type="time"]').attr('type','datetime-local');
		}
	});
	jQuery(".time_start").change(function() {
		let slot_form = jQuery(this).closest("form"); 
		jQuery('#' + slot_form.attr('id') + ' select[name="select_slot"]').html('<option>Loading...</option>');
		console.log(jQuery('#' + slot_form.attr('id') + ' input[name="from_date"]').val());
		console.log(jQuery('#' + slot_form.attr('id') + ' input[name="product_id"]').val());
		console.log(jQuery('#' + slot_form.attr('id') + ' input[name="resource_id"]').val());
		jQuery.ajax({
		  type: "POST",
		  dataType: "json",
		  url: params.ajaxurl,
		  data: {
				action: "select_slot",
				date: jQuery('#' + slot_form.attr('id') + ' input[name="from_date"]').val(),
				product_id: jQuery('#' + slot_form.attr('id') + ' input[name="product_id"]').val(),
				resource_id: jQuery('#' + slot_form.attr('id') + ' input[name="resource_id"]').val()
			},
		  beforeSend: function () {},
		  success: function (data) {},
		  error: function (xhr, textStatus, errorThrown) {},
		  complete: function (data) { 
			console.log(data.responseText);
			jQuery('#' + slot_form.attr('id') + ' select[name="select_slot"]').html(data.responseText);
			//window.location.reload();
		  },
		});
	});
	jQuery(".enable_slot").on("click", function (e) {
		e.preventDefault();
		if (confirm("Are you sure you want to enable this slot?") != true) {
			return false;
		}
		jQuery.ajax({
		  type: "POST",
		  dataType: "json",
		  url: params.ajaxurl,
		  data: {
				action: "enable_slot",
				key: jQuery(this).attr('data-key'),
				product_id: jQuery(this).attr('data-id'),
				resource_id: jQuery(this).attr('data-resource')
			},
		  beforeSend: function () {},
		  success: function (data) { 
		  },
		  error: function (xhr, textStatus, errorThrown) {},
		  complete: function (data) { 
			alert(data.responseJSON[0].slot);
			window.location.reload();
		  },
		});
	});
	jQuery(".disable_slot").on("click", function (e) {
		e.preventDefault();
		if (confirm("Are you sure you want to disable this slot?") != true) {
			return false;
		}
		jQuery.ajax({
		  type: "POST",
		  dataType: "json",
		  url: params.ajaxurl,
		  data: {
				action: "disable_slot",
				key: jQuery(this).attr('data-key'),
				product_id: jQuery(this).attr('data-id'),
				resource_id: jQuery(this).attr('data-resource')
			},
		  beforeSend: function () {},
		  success: function (data) { 
		  },
		  error: function (xhr, textStatus, errorThrown) {},
		  complete: function (data) { 
			alert(data.responseJSON[0].slot);
			window.location.reload();
		  },
		});
	});
	jQuery(".delete_slot").on("click", function (e) {
		e.preventDefault();
		if (confirm("Are you sure you want to delete this slot?") != true) {
			return false;
		}
		jQuery.ajax({
		  type: "POST",
		  dataType: "json",
		  url: params.ajaxurl,
		  data: {
				action: "delete_slot",
				key: jQuery(this).attr('data-key'),
				product_id: jQuery(this).attr('data-id'),
				resource_id: jQuery(this).attr('data-resource')
			},
		  beforeSend: function () {},
		  success: function (data) { 
		  },
		  error: function (xhr, textStatus, errorThrown) {},
		  complete: function (data) { 
			alert(data.responseJSON[0].slot);
			window.location.reload();
		  },
		});
	});
});