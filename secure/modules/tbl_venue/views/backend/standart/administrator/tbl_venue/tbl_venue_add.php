<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCOi5vktJx2fjOA4X9orhT_-v2SIvsv060 "></script>
<script src="<?= BASE_ASSET; ?>jquery-map/dist/jquery.addressPickerByGiro.js"></script>
<link href="<?= BASE_ASSET; ?>jquery-map/dist/jquery.addressPickerByGiro.css" rel="stylesheet" media="screen">

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
        Tbl Venue        <small><?= cclang('new', ['Tbl Venue']); ?> </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a  href="<?= site_url('administrator/tbl_venue'); ?>">Tbl Venue</a></li>
        <li class="active"><?= cclang('new'); ?></li>
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
                            <h3 class="widget-user-username">Tbl Venue</h3>
                            <h5 class="widget-user-desc"><?= cclang('new', ['Tbl Venue']); ?></h5>
                            <hr>
                        </div>
                        <?= form_open('', [
                            'name'    => 'form_tbl_venue', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_tbl_venue', 
                            'enctype' => 'multipart/form-data', 
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
                                    <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                <b>Input User Id</b> Max Length : 11.</small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="image" class="col-sm-2 control-label">Image 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <div id="tbl_venue_image_galery"></div>
                                <input class="data_file" name="tbl_venue_image_uuid" id="tbl_venue_image_uuid" type="hidden" value="<?= set_value('tbl_venue_image_uuid'); ?>">
                                <input class="data_file" name="tbl_venue_image_name" id="tbl_venue_image_name" type="hidden" value="<?= set_value('tbl_venue_image_name'); ?>">
                                <small class="info help-block">
									<b>Input Image</b> Max Resolution Size :- 800px : 600px</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="title" class="col-sm-2 control-label">Title 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?= set_value('title'); ?>">
                                <small class="info help-block">
                                <b>Input Title</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="sports" class="col-sm-2 control-label">Sports 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="sports[]" id="sports" data-placeholder="Select Sports" multiple >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_sports') as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->sport_name; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                <b>Input Sports</b> Max Length : 255.</small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="amenities" class="col-sm-2 control-label">Amenities 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="amenities[]" id="amenities" data-placeholder="Select Amenities" multiple>
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_amenities') as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                <b>Input Amenities</b> Max Length : 500.</small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="description" class="col-sm-2 control-label">Description 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description" rows="5" cols="80"><?= set_value('Description'); ?></textarea>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="address" class="col-sm-2 control-label">Address 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <textarea id="address" name="address" rows="5" class="textarea"><?= set_value('address'); ?></textarea>
                                <small class="info help-block">
                                <b>Input Address</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="address_map" class="col-sm-2 control-label">Address Map 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input autocomplete="off" type="text" class="form-control" name="address_map" id="address_map" placeholder="Address Map" value="<?= set_value('address_map'); ?>">
                                <small class="info help-block">
                                <b>Input Address Map</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="latitude" class="col-sm-2 control-label">Latitude 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Latitude" value="<?= set_value('latitude'); ?>">
                                <small class="info help-block">
                                <b>Input Latitude</b> Max Length : 500.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="longitude" class="col-sm-2 control-label">Longitude 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Longitude" value="<?= set_value('longitude'); ?>">
                                <small class="info help-block">
                                <b>Input Longitude</b> Max Length : 500.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="video_url" class="col-sm-2 control-label">Video Url 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="video_url" id="video_url" placeholder="Video Url" value="<?= set_value('video_url'); ?>">
                                <small class="info help-block">
                                <b>Input Video Url</b> Max Length : 500.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="status" class="col-sm-2 control-label">Status 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select" name="status" id="status" data-placeholder="Select Status" >
                                    <option value=""></option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                    </select>
                                <small class="info help-block">
                                <b>Input Status</b> Max Length : 1.</small>
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
      $('#address_map').addressPickerByGiro({distanceWidget: true});
            CKEDITOR.replace('description'); 
      var description = CKEDITOR.instances.description;
                   
      $('#btn_cancel').click(function(){
        swal({
            title: "<?= cclang('are_you_sure'); ?>",
            text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
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
              window.location.href = BASE_URL + 'administrator/tbl_venue';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
        $('#description').val(description.getData());
                    
        var form_tbl_venue = $('#form_tbl_venue');
        var data_post = form_tbl_venue.serializeArray();
        var save_type = $(this).attr('data-stype');

        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: BASE_URL + '/administrator/tbl_venue/add_save',
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            var id_image = $('#tbl_venue_image_galery').find('li').attr('qq-file-id');
            
            if (save_type == 'back') {
              window.location.href = res.redirect;
              return;
            }
    
            $('.message').printMessage({message : res.message});
            $('.message').fadeIn();
            resetForm();
            if (typeof id_image !== 'undefined') {
                    $('#tbl_venue_image_galery').fineUploader('deleteFile', id_image);
                }
            $('.chosen option').prop('selected', false).trigger('chosen:updated');
            description.setData('');
                
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

       $('#tbl_venue_image_galery').fineUploader({
          template: 'qq-template-gallery',
          request: {
              endpoint: BASE_URL + '/administrator/tbl_venue/upload_image_file',
              params : params
          },
          deleteFile: {
              enabled: true, 
              endpoint: BASE_URL + '/administrator/tbl_venue/delete_image_file',
          },
          thumbnails: {
              placeholders: {
                  waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                  notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
              }
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
                   var uuid = $('#tbl_venue_image_galery').fineUploader('getUuid', id);
                   $('#tbl_venue_image_uuid').val(uuid);
                   $('#tbl_venue_image_name').val(xhr.uploadName);
                } else {
                   toastr['error'](xhr.error);
                }
              },
              onSubmit : function(id, name) {
                  var uuid = $('#tbl_venue_image_uuid').val();
                  $.get(BASE_URL + '/administrator/tbl_venue/delete_image_file/' + uuid);
              },
              onDeleteComplete : function(id, xhr, isError) {
                if (isError == false) {
                  $('#tbl_venue_image_uuid').val('');
                  $('#tbl_venue_image_name').val('');
                }
              }
          }
      }); /*end image galery*/
              
 
       
    
    
    }); /*end doc ready*/
</script>