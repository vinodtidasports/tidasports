
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
    function domo(){
     
       // Binding keys
       $('*').bind('keydown', 'Ctrl+s', function assets() {
          $('#btn_save').trigger('click');
           return false;
       });
    
       $('*').bind('keydown', 'Ctrl+x', function assets() {
          $('#btn_cancel').trigger('click');
           return false;
       });
    
      $('*').bind('keydown', 'Ctrl+d', function assets() {
          $('.btn_save_back').trigger('click');
           return false;
       });
        
    }
    
    jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Tbl Facilities        <small>Edit Tbl Facilities</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a  href="<?= site_url('administrator/tbl_facilities'); ?>">Tbl Facilities</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row" >
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header ">
                            <div class="widget-user-image">
                                <img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username">Tbl Facilities</h3>
                            <h5 class="widget-user-desc">Edit Tbl Facilities</h5>
                            <hr>
                        </div>
                        <?= form_open(base_url('administrator/tbl_facilities/edit_save/'.$this->uri->segment(4)), [
                            'name'    => 'form_tbl_facilities', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_tbl_facilities', 
                            'method'  => 'POST'
                            ]); ?>
                         
                                                <div class="form-group ">
                            <label for="title" class="col-sm-2 control-label">Title 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?= set_value('title', $tbl_facilities->title); ?>">
                                <small class="info help-block">
                                <b>Input Title</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="venue_id" class="col-sm-2 control-label">Venue Id 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="venue_id" id="venue_id" data-placeholder="Select Venue Id" >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_venue') as $row): ?>
                                    <option <?=  $row->id ==  $tbl_facilities->venue_id ? 'selected' : ''; ?> value="<?= $row->id ?>"><?= $row->title; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="no_of_inventories" class="col-sm-2 control-label">No Of Inventories 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="no_of_inventories" id="no_of_inventories" placeholder="No Of Inventories" value="<?= set_value('no_of_inventories', $tbl_facilities->no_of_inventories); ?>">
                                <small class="info help-block">
                                <b>Input No Of Inventories</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="min_players" class="col-sm-2 control-label">Min Players 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="min_players" id="min_players" placeholder="Min Players" value="<?= set_value('min_players', $tbl_facilities->min_players); ?>">
                                <small class="info help-block">
                                <b>Input Min Players</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="max_players" class="col-sm-2 control-label">Max Players 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="max_players" id="max_players" placeholder="Max Players" value="<?= set_value('max_players', $tbl_facilities->max_players); ?>">
                                <small class="info help-block">
                                <b>Input Max Players</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="default_players" class="col-sm-2 control-label">Default Players 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="default_players" id="default_players" placeholder="Default Players" value="<?= set_value('default_players', $tbl_facilities->default_players); ?>">
                                <small class="info help-block">
                                <b>Input Default Players</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="price_per_slot" class="col-sm-2 control-label">Price Per Slot 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="price_per_slot" id="price_per_slot" placeholder="Price Per Slot" value="<?= set_value('price_per_slot', $tbl_facilities->price_per_slot); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="opening_time" class="col-sm-2 control-label">Opening Time 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="opening_time" id="opening_time" placeholder="Opening Time" value="<?= set_value('opening_time', $tbl_facilities->opening_time); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="closing_time" class="col-sm-2 control-label">Closing Time 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="closing_time" id="closing_time" placeholder="Closing Time" value="<?= set_value('closing_time', $tbl_facilities->closing_time); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="available_24_hours" class="col-sm-2 control-label">Available 24 Hours 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select" name="available_24_hours" id="available_24_hours" data-placeholder="Select Available 24 Hours" >
                                    <option <?= $tbl_facilities->available_24_hours == "0" ? 'selected' :''; ?> value="0">No</option>
                                    <option <?= $tbl_facilities->available_24_hours == "1" ? 'selected' :''; ?> value="1">Yes</option>
                                    </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="slot_length_hrs" class="col-sm-2 control-label">Slot Length Hrs 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="slot_length_hrs" id="slot_length_hrs" placeholder="Slot Length Hrs" value="<?= set_value('slot_length_hrs', $tbl_facilities->slot_length_hrs); ?>">
                                <small class="info help-block">
                                <b>Input Slot Length Hrs</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="slot_length_min" class="col-sm-2 control-label">Slot Length Min 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="slot_length_min" id="slot_length_min" placeholder="Slot Length Min" value="<?= set_value('slot_length_min', $tbl_facilities->slot_length_min); ?>">
                                <small class="info help-block">
                                <b>Input Slot Length Min</b> Max Length : 11.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="slot_frequency" class="col-sm-2 control-label">Slot Frequency 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="slot_frequency" id="slot_frequency" placeholder="Slot Frequency" value="<?= set_value('slot_frequency', $tbl_facilities->slot_frequency); ?>">
                                <small class="info help-block">
                                <b>Input Slot Frequency</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="activity" class="col-sm-2 control-label">Activity 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="activity" id="activity" placeholder="Activity" value="<?= set_value('activity', $tbl_facilities->activity); ?>">
                                <small class="info help-block">
                                <b>Input Activity</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="status" class="col-sm-2 control-label">Status 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select" name="status" id="status" data-placeholder="Select Status" >
                                    <option value=""></option>
                                    <option <?= $tbl_facilities->status == "1" ? 'selected' :''; ?> value="1"><?php  echo 'Active'; ?></option>
                                    <option <?= $tbl_facilities->status == "2" ? 'selected' :''; ?> value="2"><?php  echo 'Inactive'; ?></option>
                                    </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                
                        <div class="message"></div>
                        <div class="row-fluid col-md-7">
                            <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="<?= cclang('save_button'); ?> (Ctrl+s)">
                            <i class="fa fa-save" ></i> <?= cclang('save_button'); ?>
                            </button>
                            <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
                            <i class="ion ion-ios-list-outline" ></i> <?= cclang('save_and_go_the_list_button'); ?>
                            </a>
                            <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
                            <i class="fa fa-undo" ></i> <?= cclang('cancel_button'); ?>
                            </a>
                            <span class="loading loading-hide">
                            <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> 
                            <i><?= cclang('loading_saving_data'); ?></i>
                            </span>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
                <!--/box body -->
            </div>
            <!--/box -->
        </div>
    </div>
</section>
<!-- /.content -->
<!-- Page script -->
<script>
    $(document).ready(function(){
      
             
      $('#btn_cancel').click(function(){
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
          function(isConfirm){
            if (isConfirm) {
              window.location.href = BASE_URL + 'administrator/tbl_facilities';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
            
        var form_tbl_facilities = $('#form_tbl_facilities');
        var data_post = form_tbl_facilities.serializeArray();
        var save_type = $(this).attr('data-stype');
        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: form_tbl_facilities.attr('action'),
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            var id = $('#tbl_facilities_image_galery').find('li').attr('qq-file-id');
            if (save_type == 'back') {
              window.location.href = res.redirect;
              return;
            }
    
            $('.message').printMessage({message : res.message});
            $('.message').fadeIn();
            $('.data_file_uuid').val('');
    
          } else {
            $('.message').printMessage({message : res.message, type : 'warning'});
          }
    
        })
        .fail(function() {
          $('.message').printMessage({message : 'Error save data', type : 'warning'});
        })
        .always(function() {
          $('.loading').hide();
          $('html, body').animate({ scrollTop: $(document).height() }, 2000);
        });
    
        return false;
      }); /*end btn save*/
      
       
       
           
    
    }); /*end doc ready*/
</script>