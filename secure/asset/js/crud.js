
"use strict";

/*replace all string*/
String.prototype.replaceAll = function (search, replacement) {
	var target = this;
	return target.split(search).join(replacement);
};

function updateValidation(target) {
	var validation_group = target.find('option:selected').attr('title');

	var options = $('.validation_rules option.' + validation_group).clone().filter(function (index, elem) {
		return elem;
	});
	target.parents('tr').find('.box-validation').each(function (index, el) {
		if (!$(this).hasClass(validation_group)) {
			$(this).remove();
		}
	});
	target.parents('tr').find('.validation').html(options).trigger('chosen:updated');
}

function addValidation(target, id, name, type, input_able, input_value, input_placeholder) {
	if (!target.parents('td').find('.box-validation.' + type).length) {

		if (typeof input_value == 'undefined') {
			var input_value = '';
		}

		if (typeof input_placeholder == 'undefined') {
			var input_placeholder = '';
		}

		var group_validation = target.find('option:selected').attr('class');

		if (input_able == 'yes') {
			var input = '<input value="' + input_value + '" placeholder="' + input_placeholder + '" class="input_validation" name="crud[' + id + '][' + name + '][validation][rules][' + type + ']" >';
			var class_validation_name = 'validation-name';
		} else {
			var input = '<input type="hidden" class="input_validation" name="crud[' + id + '][' + name + '][validation][rules][' + type + ']" >';
			var class_validation_name = 'validation-name-max';
		}


		target.parents('td').append('<div class="box-validation ' + group_validation + ' ' + type + '"> <label><div class="' + class_validation_name + '">' + type.replaceAll('_', ' ') + '</div> ' + input + '</label> <a class="delete fa fa-trash"></a> </div>');
		target.parents('td').find('.box-validation.' + type + ' input').focus();
	}
	target.val('').trigger('chosen:updated');
}

$(document).ready(function () {

	$(document).on('change', 'table tr .validation', function () {
		var type = $(this).val();
		var input_able = $(this).find('option:selected').attr('title');
		var input_placeholder = $(this).find('option:selected').attr('data-placeholder');
		var id = $(this).parents('tr').find('#crud-id').val();
		var name = $(this).parents('tr').find('#crud-name').val();
		addValidation($(this), id, name, type, input_able, '', input_placeholder);
	}); /*end validation change*/

	$(document).on('click', 'table tr a.delete', function () {
		$(this).parents('.box-validation').remove();
	});/*end delte click*/

	/*update validation rules on input type change*/
	$(document).on('change', 'table tr .input_type', function () {
		updateValidation($(this));

		var relation = $(this).find('option:selected').attr('relation');
		var custom_value = $(this).find('option:selected').attr('custom-value');
		var table_relation = $(this).parents('td').find('.relation_table');
		var custom_option_container = $(this).parents('td').find('.custom-option-container');

		if (relation == 1) {
			table_relation.val('').trigger('chosen:updated').parents('.form-group').show();

		} else {
			$(this).parents('td').find('.relation_field').parents('.form-group').hide();
			$(this).parents('td').find('.relation_field').val('');
		}

		if ($(this).val() == 'chained') {
			$(this).parents('td').find('.chain_field_eq').parents('.form-group').show();
		}
		else {
			$(this).parents('td').find('.chain_field_eq').parents('.form-group').hide();
		}

		if (custom_value == 1) {
			custom_option_container.show();

		} else {
			custom_option_container.hide();
		}

	});

	$(document).on('change', 'table tr .relation_table', function () {
		var relation_value = $(this).parents('td').find('.relation_value');
		var relation_label = $(this).parents('td').find('.relation_label');
		var table_name = $(this).val();

		relation_value.parents('.form-group').show();
		relation_label.parents('.form-group').show();

		$.get(ADMIN_BASE_URL + '/crud/get_list_field_id/' + table_name, function (data) {
			var res = (data);

			if (res.success) {
				relation_value.html(res.html);
				relation_value.trigger('chosen:updated');

				relation_label.html(res.html);
				relation_label.trigger('chosen:updated');
			} else {
				$('.message').printMessage({ message: res.message, type: 'warning' });
				$('.message').fadeIn();
			}
		}).fail(function () {
			$('.message').printMessage({ message: 'Error getting data', type: 'warning' });
		})
			.always(function () {
				$('.loading').hide();
			});


		$.get(ADMIN_BASE_URL + '/crud/get_list_field_label/' + table_name, function (data) {
			var res = (data);

			if (res.success) {
				relation_label.html(res.html);
				relation_label.trigger('chosen:updated');
			} else {
				$('.message').printMessage({ message: res.message, type: 'warning' });
				$('.message').fadeIn();
			}
		}).fail(function () {
			$('.message').printMessage({ message: 'Error getting data', type: 'warning' });
		})
			.always(function () {
				$('.loading').hide();
			});

	});

	/*custom option*/

	$(document).on('click', 'table tr a.add-option', function () {
		var type = $(this).val();
		var id = $(this).parents('tr').find('#crud-id').val();
		var name = $(this).parents('tr').find('#crud-name').val();
		var time_in_ms = Date.now();
		var option = '<div class="custom-option-item custom-option-' + time_in_ms + '">' +
			'<div class="box-custom-option input padding-left-0 box-top"> ' +
			'<div class="col-md-3">value</div>  <input class="input_validation" name="crud[' + id + '][' + name + '][custom_option][' + time_in_ms + '][value]" value="" type="text"></label>' +
			'</div>' +
			'<div class="box-custom-option input padding-left-0 box-bottom"> ' +
			'<div class="col-md-3">label</div>  <input class="input_validation" name="crud[' + id + '][' + name + '][custom_option][' + time_in_ms + '][label]" value="" type="text">' +
			'</div>' +
			'<a class="text-red delete-option fa fa-trash" data-original-title="" title=""></a> ' +
			'</div>';

		$(this).parents('tr').find('.custom-option-contain').append(option);
		$('.custom-option-' + time_in_ms).hide().slideDown();
		$('.custom-option-' + time_in_ms).find('input')[0].focus();
	}); /*end option on click*/

	$(document).on('keydown', 'div.custom-option-item input', function (event) {
		if (event.keyCode == 13) {
			$(this).parents('.custom-option-container').find('a.add-option').trigger('click');
			event.preventDefault();
			return false;
		}
	});


	$(document).on('click', 'table tr a.delete-option', function () {
		$(this).parents('.custom-option-item').remove();

		return;
	});/*end delte click*/

	$('#btn_cancel').on('click', function () {
		swal({
			title: "Are you sure?",
			text: "the data that you have created will be in the exhaust!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes!",
			cancelButtonText: "No!",
			closeOnConfirm: true,
			closeOnCancel: true
		},
			function (isConfirm) {
				if (isConfirm) {
					window.location.href = HTTP_REFERER;
				}
			});

		return false;
	}); /*end btn cancel*/
	/*
		$(document).on('click', '.config-label', function(){
			$('.control-sidebar').addClass('control-sidebar-open');
		})*/

	$(document).on('click', '.btn-remove-config', function (event) {
		event.preventDefault();
		$(this).parents('.config-item').remove();
	});

	$(document).on('click', '.btn-remove-config', function (event) {
		event.preventDefault();
		$(this).parents('.config-item').remove();
	});

	$(document).on('click', '.btn-add-form-step', function (event) {
		event.preventDefault();
		var parent = $(this).parents('tr');
		var crudId = parent.find('.crud-id').val();
		var crudName = parent.find('.crud-name').val();
		if (parent.find('.config-item-wrapper [data-type="stepper"]').length == 0) {
			parent.find('.config-item-wrapper').prepend(`
	        <div class="config-item" data-type="stepper">
	            <span class="config-label"><i class="fa fa-cog btn-config"></i> stepper</span>
	            <a href="#" class="pull-right btn-remove-config"><i class="fa fa-trash"></i></a>
	            <div><input 
	            name="crud[`+ crudId + `][` + crudName + `][configs][stepper][title]" type="text" placeholder="step title" class="input-setting"></div>
	        </div>
	        	`);
		}
	});

	$(document).on('click', '.btn-add-strict-group', function (event) {
		event.preventDefault();
		var parent = $(this).parents('tr');
		var crudId = parent.find('.crud-id').val();
		var crudName = parent.find('.crud-name').val();
		if (parent.find('.config-item-wrapper [data-type="strict"]').length == 0) {
			var input = $('.container-groups').clone();


			input = input.find('#group')
				.attr('name', `crud[` + crudId + `][` + crudName + `][configs][strict][groups][]`)
				.parent().parent().parent()

			parent.find('.config-item-wrapper').prepend(`
	        <div class="config-item" data-type="strict">
	            <span class="config-label"><i class="fa fa-cog btn-config"></i> strict</span>
	            <a href="#" class="pull-right btn-remove-config"><i class="fa fa-trash"></i></a>
	            <div>`+ input.html() + `</div>
	        </div>
				`);

			parent.find('.strict-group').chosen('destroy').chosen({
				search_contains: true,
				search_contains: true,
				parser_config: {
					copy_data_attributes: true
				}
			});
		}
	});



	$(document).on('click', 'table tr i.btn-collapse-setting', function () {
		var state = $(this).data('state');
		switch (state) {
			case 1:
				$(this).parents('tr').find('.setting-container .box-setting').slideDown('fast', 'easeInOutQuart');
				$(this).data('state', 2);
				$(this).removeClass('fa-plus');
				$(this).addClass('fa-minus');
				break;
			case undefined:
			case 2:
				$(this).parents('tr').find('.setting-container .box-setting').slideUp('fast', 'easeInOutQuart');
				$(this).data('state', 1);
				$(this).addClass('fa-plus');
				$(this).removeClass('fa-minus');
				break;
		}
	});

	$(document).on('change', 'input.switch-button', function () {
		if ($(this).prop('checked')) {
			$(this).parents('.box-setting').find('.input_setting').fadeOut('easeInOutQuart');
		} else {
			$(this).parents('.box-setting').find('.input_setting').focus().fadeIn('easeInOutQuart');
		}
	});




	$('.action-item-new').on('click', function (event) {
		event.preventDefault();

		$('.modal-add-action').modal('show')

	})


	$('.action-field-new').on('click', function (event) {
		event.preventDefault();

		var url = $(this).data('href')
		$('.modal-add-field').modal('show')
		/* 	$('.modal-add-field').find('.modal-body').html(`
			<center>
			<img src="`+ BASE_URL + `/asset/img/loading-spin-primary.svg" style="width:50px; width:50px;">
			</center>
			`);
			$('.modal-add-field').find('.modal-body').load(url, function () {
	
			}) */

	})



});
