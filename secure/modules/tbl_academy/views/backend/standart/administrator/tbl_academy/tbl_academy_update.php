
<!-- Fine Uploader Gallery CSS file
    ====================================================================== -->
<link href="<?= BASE_ASSET; ?>/fine-upload/fine-uploader-gallery.min.css" rel="stylesheet">
<!-- Fine Uploader jQuery JS file
    ====================================================================== -->
<script src="<?= BASE_ASSET; ?>/fine-upload/jquery.fine-uploader.js"></script>
<?php $this->load->view('core_template/fine_upload'); ?>
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
        Tbl Academy        <small>Edit Tbl Academy</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a  href="<?= site_url('administrator/tbl_academy'); ?>">Tbl Academy</a></li>
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
                            <h3 class="widget-user-username">Tbl Academy</h3>
                            <h5 class="widget-user-desc">Edit Tbl Academy</h5>
                            <hr>
                        </div>
                        <?= form_open(base_url('administrator/tbl_academy/edit_save/'.$this->uri->segment(4)), [
                            'name'    => 'form_tbl_academy', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_tbl_academy', 
                            'method'  => 'POST'
                            ]); ?>
                         
                                                <div class="form-group ">
                            <label for="user_id" class="col-sm-2 control-label">User Id 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="user_id" id="user_id" data-placeholder="Select User Id" >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_user') as $row): ?>
                                    <option <?=  $row->id ==  $tbl_academy->user_id ? 'selected' : ''; ?> value="<?= $row->id ?>"><?= $row->name; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                <b>Input User Id</b> Max Length : 11.</small>
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
                                    <option <?=  $row->id ==  $tbl_academy->venue_id ? 'selected' : ''; ?> value="<?= $row->id ?>"><?= $row->title; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                <b>Input Venue Id</b> Max Length : 11.</small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="name" class="col-sm-2 control-label">Name 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= set_value('name', $tbl_academy->name); ?>">
                                <small class="info help-block">
                                <b>Input Name</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="address" class="col-sm-2 control-label">Address 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <textarea id="address" name="address" rows="5" class="textarea"><?= set_value('address', $tbl_academy->address); ?></textarea>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="logo" class="col-sm-2 control-label">Logo 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <div id="tbl_academy_logo_galery"></div>
                                <input class="data_file data_file_uuid" name="tbl_academy_logo_uuid" id="tbl_academy_logo_uuid" type="hidden" value="<?= set_value('tbl_academy_logo_uuid'); ?>">
                                <input class="data_file" name="tbl_academy_logo_name" id="tbl_academy_logo_name" type="hidden" value="<?= set_value('tbl_academy_logo_name', $tbl_academy->logo); ?>">
                                <small class="info help-block">
									<b>Input Image</b> Max Resolution Size :- 800px : 600px</small>
                            </div>
                        </div>
                                                  
                                                <div class="form-group ">
                            <label for="latitude" class="col-sm-2 control-label">Latitude 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Latitude" value="<?= set_value('latitude', $tbl_academy->latitude); ?>">
                                <small class="info help-block">
                                <b>Input Latitude</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="longitude" class="col-sm-2 control-label">Longitude 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Longitude" value="<?= set_value('longitude', $tbl_academy->longitude); ?>">
                                <small class="info help-block">
                                <b>Input Longitude</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="description" class="col-sm-2 control-label">Description 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description" rows="10" cols="80"> <?= set_value('description', $tbl_academy->description); ?></textarea>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="contact_no" class="col-sm-2 control-label">Contact No 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Contact No" value="<?= set_value('contact_no', $tbl_academy->contact_no); ?>">
                                <small class="info help-block">
                                <b>Input Contact No</b> Max Length : 50.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="head_coach" class="col-sm-2 control-label">Head Coach 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="head_coach" id="head_coach" placeholder="Head Coach" value="<?= set_value('head_coach', $tbl_academy->head_coach); ?>">
                                <small class="info help-block">
                                <b>Input Head Coach</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="session_timings" class="col-sm-2 control-label">Session Timings 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="session_timings" id="session_timings" placeholder="Session Timings" value="<?= set_value('session_timings', $tbl_academy->session_timings); ?>">
                                <small class="info help-block">
                                <b>Input Session Timings</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="week_days" class="col-sm-2 control-label">Week Days 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="week_days" id="week_days" placeholder="Week Days" value="<?= set_value('week_days', $tbl_academy->week_days); ?>">
                                <small class="info help-block">
                                <b>Input Week Days</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="price" class="col-sm-2 control-label">Price 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?= set_value('price', $tbl_academy->price); ?>">
                                <small class="info help-block">
                                <b>Input Price</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="remarks_price" class="col-sm-2 control-label">Remarks Price 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="remarks_price" id="remarks_price" placeholder="Remarks Price" value="<?= set_value('remarks_price', $tbl_academy->remarks_price); ?>">
                                <small class="info help-block">
                                <b>Input Remarks Price</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="skill_level" class="col-sm-2 control-label">Skill Level 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="skill_level" id="skill_level" placeholder="Skill Level" value="<?= set_value('skill_level', $tbl_academy->skill_level); ?>">
                                <small class="info help-block">
                                <b>Input Skill Level</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="academy_jersey" class="col-sm-2 control-label">Academy Jersey 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="academy_jersey" id="academy_jersey" placeholder="Academy Jersey" value="<?= set_value('academy_jersey', $tbl_academy->academy_jersey); ?>">
                                <small class="info help-block">
                                <b>Input Academy Jersey</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="capacity" class="col-sm-2 control-label">Capacity 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="capacity" id="capacity" placeholder="Capacity" value="<?= set_value('capacity', $tbl_academy->capacity); ?>">
                                <small class="info help-block">
                                <b>Input Capacity</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="remarks_current_capacity" class="col-sm-2 control-label">Remarks Current Capacity 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="remarks_current_capacity" id="remarks_current_capacity" placeholder="Remarks Current Capacity" value="<?= set_value('remarks_current_capacity', $tbl_academy->remarks_current_capacity); ?>">
                                <small class="info help-block">
                                <b>Input Remarks Current Capacity</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="session_plan" class="col-sm-2 control-label">Session Plan 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="session_plan" id="session_plan" placeholder="Session Plan" value="<?= set_value('session_plan', $tbl_academy->session_plan); ?>">
                                <small class="info help-block">
                                <b>Input Session Plan</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="remarks_session_plan" class="col-sm-2 control-label">Remarks Session Plan 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="remarks_session_plan" id="remarks_session_plan" placeholder="Remarks Session Plan" value="<?= set_value('remarks_session_plan', $tbl_academy->remarks_session_plan); ?>">
                                <small class="info help-block">
                                <b>Input Remarks Session Plan</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="age_group_of_students" class="col-sm-2 control-label">Age Group Of Students 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="age_group_of_students" id="age_group_of_students" placeholder="Age Group Of Students" value="<?= set_value('age_group_of_students', $tbl_academy->age_group_of_students); ?>">
                                <small class="info help-block">
                                <b>Input Age Group Of Students</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="remarks_students" class="col-sm-2 control-label">Remarks Students 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="remarks_students" id="remarks_students" placeholder="Remarks Students" value="<?= set_value('remarks_students', $tbl_academy->remarks_students); ?>">
                                <small class="info help-block">
                                <b>Input Remarks Students</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="equipment" class="col-sm-2 control-label">Equipment 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="equipment" id="equipment" placeholder="Equipment" value="<?= set_value('equipment', $tbl_academy->equipment); ?>">
                                <small class="info help-block">
                                <b>Input Equipment</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="remarks_on_equipment" class="col-sm-2 control-label">Remarks On Equipment 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="remarks_on_equipment" id="remarks_on_equipment" placeholder="Remarks On Equipment" value="<?= set_value('remarks_on_equipment', $tbl_academy->remarks_on_equipment); ?>">
                                <small class="info help-block">
                                <b>Input Remarks On Equipment</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="flood_lights" class="col-sm-2 control-label">Flood Lights 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="flood_lights" id="flood_lights" placeholder="Flood Lights" value="<?= set_value('flood_lights', $tbl_academy->flood_lights); ?>">
                                <small class="info help-block">
                                <b>Input Flood Lights</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="ground_size" class="col-sm-2 control-label">Ground Size 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="ground_size" id="ground_size" placeholder="Ground Size" value="<?= set_value('ground_size', $tbl_academy->ground_size); ?>">
                                <small class="info help-block">
                                <b>Input Ground Size</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="person" class="col-sm-2 control-label">Person 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="person" id="person" placeholder="Person" value="<?= set_value('person', $tbl_academy->person); ?>">
                                <small class="info help-block">
                                <b>Input Person</b> Max Length : 300.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="coach_experience" class="col-sm-2 control-label">Coach Experience 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="coach_experience" id="coach_experience" placeholder="Coach Experience" value="<?= set_value('coach_experience', $tbl_academy->coach_experience); ?>">
                                <small class="info help-block">
                                <b>Input Coach Experience</b> Max Length : 300.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="no_of_assistent_coach" class="col-sm-2 control-label">No Of Assistent Coach 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="no_of_assistent_coach" id="no_of_assistent_coach" placeholder="No Of Assistent Coach" value="<?= set_value('no_of_assistent_coach', $tbl_academy->no_of_assistent_coach); ?>">
                                <small class="info help-block">
                                <b>Input No Of Assistent Coach</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="assistent_coach_name" class="col-sm-2 control-label">Assistent Coach Name 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="assistent_coach_name" id="assistent_coach_name" placeholder="Assistent Coach Name" value="<?= set_value('assistent_coach_name', $tbl_academy->assistent_coach_name); ?>">
                                <small class="info help-block">
                                <b>Input Assistent Coach Name</b> Max Length : 200.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="feedbacks" class="col-sm-2 control-label">Feedbacks 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="feedbacks" id="feedbacks" placeholder="Feedbacks" value="<?= set_value('feedbacks', $tbl_academy->feedbacks); ?>">
                                <small class="info help-block">
                                <b>Input Feedbacks</b> Max Length : 500.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="amenities_id" class="col-sm-2 control-label">Amenities Id 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="amenities_id[]" id="amenities_id" data-placeholder="Select Amenities Id" multiple >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_amenities') as $row): ?>
                                    <option <?=  in_array($row->id, explode(',', $tbl_academy->amenities_id)) ? 'selected' : ''; ?> value="<?= $row->id ?>"><?= $row->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="info help-block">
                                <b>Input Amenities Id</b> Max Length : 255.</small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="sports" class="col-sm-2 control-label">Sports 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select" name="sports[]" id="sports" data-placeholder="Select Sports" multiple >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_sports') as $row): ?>
                                    <option <?=  in_array($row->id, explode(',', $tbl_academy->sports)) ? 'selected' : ''; ?> value="<?= $row->id ?>"><?= $row->sport_name; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                <b>Input Sports</b> Max Length : 500.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="status" class="col-sm-2 control-label">Status 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select" name="status" id="status" data-placeholder="Select Status" >
                                    <option value=""></option>
                                    <option <?= $tbl_academy->status == "1" ? 'selected' :''; ?> value="1">Active</option>
                                    <option <?= $tbl_academy->status == "2" ? 'selected' :''; ?> value="2">Inactive</option>
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
<script src="<?= BASE_ASSET; ?>ckeditor/ckeditor.js"></script>
<!-- Page script -->
<script>
    $(document).ready(function(){
      
      CKEDITOR.replace('description'); 
      var description = CKEDITOR.instances.description;
                   
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
              window.location.href = BASE_URL + 'administrator/tbl_academy';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
        $('#description').val(description.getData());
                    
        var form_tbl_academy = $('#form_tbl_academy');
        var data_post = form_tbl_academy.serializeArray();
        var save_type = $(this).attr('data-stype');
        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: form_tbl_academy.attr('action'),
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            var id = $('#tbl_academy_image_galery').find('li').attr('qq-file-id');
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
      
                     var params = {};
       params[csrf] = token;

       $('#tbl_academy_logo_galery').fineUploader({
          template: 'qq-template-gallery',
          request: {
              endpoint: BASE_URL + '/administrator/tbl_academy/upload_logo_file',
              params : params
          },
          deleteFile: {
              enabled: true, // defaults to false
              endpoint: BASE_URL + '/administrator/tbl_academy/delete_logo_file'
          },
          thumbnails: {
              placeholders: {
                  waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                  notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
              }
          },
           session : {
             endpoint: BASE_URL + 'administrator/tbl_academy/get_logo_file/<?= $tbl_academy->id; ?>',
             refreshOnRequest:true
           },
          multiple : false,
          validation: {
              allowedExtensions: ["*"],
              sizeLimit : 0,
                        },
          showMessage: function(msg) {
              toastr['error'](msg);
          },
          callbacks: {
              onComplete : function(id, name, xhr) {
                if (xhr.success) {
                   var uuid = $('#tbl_academy_logo_galery').fineUploader('getUuid', id);
                   $('#tbl_academy_logo_uuid').val(uuid);
                   $('#tbl_academy_logo_name').val(xhr.uploadName);
                } else {
                   toastr['error'](xhr.error);
                }
              },
              onSubmit : function(id, name) {
                  var uuid = $('#tbl_academy_logo_uuid').val();
                  $.get(BASE_URL + '/administrator/tbl_academy/delete_logo_file/' + uuid);
              },
              onDeleteComplete : function(id, xhr, isError) {
                if (isError == false) {
                  $('#tbl_academy_logo_uuid').val('');
                  $('#tbl_academy_logo_name').val('');
                }
              }
          }
      }); /*end logo galey*/
              
       
           
    
    }); /*end doc ready*/
</script>