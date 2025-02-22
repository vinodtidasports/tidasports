
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
        Tbl Experience        <small><?= cclang('new', ['Tbl Experience']); ?> </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a  href="<?= site_url('administrator/tbl_experience'); ?>">Tbl Experience</a></li>
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
                            <h3 class="widget-user-username">Tbl Experience</h3>
                            <h5 class="widget-user-desc"><?= cclang('new', ['Tbl Experience']); ?></h5>
                            <hr>
                        </div>
                        <?= form_open('', [
                            'name'    => 'form_tbl_experience', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_tbl_experience', 
                            'enctype' => 'multipart/form-data', 
                            'method'  => 'POST'
                            ]); ?>
                         
                                                <div class="form-group ">
                            <label for="title" class="col-sm-2 control-label">Title 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?= set_value('title'); ?>">
                                <small class="info help-block">
                                <b>Input Title</b> Max Length : 200.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="description" class="col-sm-2 control-label">Description 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description" rows="5" class="textarea"><?= set_value('description'); ?></textarea>
                                <small class="info help-block">
                                <b>Input Description</b> Max Length : 500.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="price" class="col-sm-2 control-label">Price 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?= set_value('price'); ?>">
                                <small class="info help-block">
                                <b>Input Price</b> Max Length : 100.</small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="image" class="col-sm-2 control-label">Image 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <div id="tbl_experience_image_galery"></div>
                                <div id="tbl_experience_image_galery_listed"></div>
                               <small class="info help-block">
									<b>Input Image</b> Max Resolution Size :- 800px : 600px</small>
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
                                </small>
                            </div>
                        </div>
                                                 
                         
                         
                                                <div class="form-group ">
                            <label for="venue_id" class="col-sm-2 control-label">Venue Id 
                            </label>
                            <div class="col-sm-8">
                                <select  class="form-control chosen chosen-select-deselect" name="venue_id" id="venue_id" data-placeholder="Select Venue Id" >
                                    <option value=""></option>
                                    <?php foreach (db_get_all_data('tbl_venue') as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->title; ?></option>
                                    <?php endforeach; ?>  
                                </select>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>

                                                 
                                                <div class="form-group ">
                            <label for="address" class="col-sm-2 control-label">Address 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?= set_value('address'); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                                                 
                                                <div class="form-group ">
                            <label for="start_time" class="col-sm-2 control-label">Start Time 
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="start_time" id="start_time" placeholder="Start Time" value="<?= set_value('start_time'); ?>">
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
              window.location.href = BASE_URL + 'administrator/tbl_experience';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
            
        var form_tbl_experience = $('#form_tbl_experience');
        var data_post = form_tbl_experience.serializeArray();
        var save_type = $(this).attr('data-stype');

        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: BASE_URL + '/administrator/tbl_experience/add_save',
          type: 'POST',
          dataType: 'json',
          data: data_post,
        })
        .done(function(res) {
          if(res.success) {
            
            if (save_type == 'back') {
              window.location.href = res.redirect;
              return;
            }
    
            $('.message').printMessage({message : res.message});
            $('.message').fadeIn();
            resetForm();
            $('#tbl_experience_image_galery').find('li').each(function() {
               $('#tbl_experience_image_galery').fineUploader('deleteFile', $(this).attr('qq-file-id'));
            });
            $('.chosen option').prop('selected', false).trigger('chosen:updated');
                
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

       $('#tbl_experience_image_galery').fineUploader({
          template: 'qq-template-gallery',
          request: {
              endpoint: BASE_URL + '/administrator/tbl_experience/upload_image_file',
              params : params
          },
          deleteFile: {
              enabled: true, 
              endpoint: BASE_URL + '/administrator/tbl_experience/delete_image_file',
          },
          thumbnails: {
              placeholders: {
                  waitingPath: BASE_URL + '/asset/fine-upload/placeholders/waiting-generic.png',
                  notAvailablePath: BASE_URL + '/asset/fine-upload/placeholders/not_available-generic.png'
              }
          },
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
                   var uuid = $('#tbl_experience_image_galery').fineUploader('getUuid', id);
                   $('#tbl_experience_image_galery_listed').append('<input type="hidden" class="listed_file_uuid" name="tbl_experience_image_uuid['+id+']" value="'+uuid+'" /><input type="hidden" class="listed_file_name" name="tbl_experience_image_name['+id+']" value="'+xhr.uploadName+'" />');
                } else {
                   toastr['error'](xhr.error);
                }
              },
              onDeleteComplete : function(id, xhr, isError) {
                if (isError == false) {
                  $('#tbl_experience_image_galery_listed').find('.listed_file_uuid[name="tbl_experience_image_uuid['+id+']"]').remove();
                  $('#tbl_experience_image_galery_listed').find('.listed_file_name[name="tbl_experience_image_name['+id+']"]').remove();
                }
              }
          }
      }); /*end image galery*/
              
    
    
    }); /*end doc ready*/
</script>