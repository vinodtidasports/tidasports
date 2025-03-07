
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
        Amenities        <small><?= cclang('new', ['Amenities']); ?> </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a  href="<?= site_url('administrator/tbl_amenities'); ?>">Amenities</a></li>
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
                            <h3 class="widget-user-username">Amenities</h3>
                            <h5 class="widget-user-desc"><?= cclang('new', ['Amenities']); ?></h5>
                            <hr>
                        </div>
                        <?= form_open('', [
                            'name'    => 'form_tbl_amenities', 
                            'class'   => 'form-horizontal', 
                            'id'      => 'form_tbl_amenities', 
                            'enctype' => 'multipart/form-data', 
                            'method'  => 'POST'
                            ]); ?>
                         
                                                <div class="form-group ">
                            <label for="name" class="col-sm-2 control-label">Name 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= set_value('name'); ?>">
                                <small class="info help-block">
                                <b>Input Name</b> Max Length : 255.</small>
                            </div>
                        </div>
                                                 						<div class="form-group menu-only">                        <label for="content" class="col-sm-2 control-label"><?= cclang('label') ?> </label>                        <div class="col-sm-8">                           <input type="hidden" name="icon" id="icon">                                                      <div class="icon-preview">                            <span class="icon-preview-item"><i class="fa fa-rss fa-lg"></i></span>                          </div>                           <br>                           <br>                           <a class="btn btn-default btn-select-icon btn-flat"><?= cclang('select_icon') ?></a>                           <select  class="chosen  chosen-select-deselect" name="icon_color" id="icon_color" tabi-ndex="5" data-placeholder="Select Color">                            <option value="default">Default</option>                            <?php foreach ($color_icon as $color): ?>                            <option value="<?= $color; ?>"><?= ucwords($color); ?></option>                            <?php endforeach; ?>                             </select>                                                   </div>                    </div>
						<div class="form-group ">
                            <label for="icon" class="col-sm-2 control-label">Icon 
                            <i class="required">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="icon" id="icon" placeholder="Icon" value="<?= set_value('icon'); ?>">
                                <small class="info help-block">
                                <b>Input Icon</b> Max Length : 255.</small>
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
                                    <option value="2">inActive</option>
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
            /*icon*/    $('.btn-select-icon').click(function(event) {      event.preventDefault();      $('#modalIcon').modal('show');    });    $('.icon-container').click(function(event) {       $('#modalIcon').modal('hide');       var icon = $(this).find('.icon-class').html();       icon = $.trim(icon);       $('#icon').val(icon);       $('.icon-preview-item .fa').attr('class', 'fa fa-lg '+icon);    });    $('#icon_color').change(function(event) {      $('.icon-preview-item').attr('class', 'icon-preview-item '+$(this).val());    });    $('#find-icon').keyup(function(event) {      $('.icon-container').hide();      var search = $(this).val();      $('.icon-class').each(function(index, el) {        var str = $(this).html();        var patt = new RegExp(search);        var res = patt.test(str);        if (res) {          $(this).parent('.icon-container').show();        }      });    });    $('.category-icon').each(function(index) {      $('#category-icon-filter').append('<option value="'+$(this).attr('id')+'">'+$(this).attr('id')+'</option>');    });    $('#category-icon-filter').change(function(event) {      var type = $('#category-icon-filter').val();      $('.category-icon').hide();      $('.category-icon#'+type).show();      if (type == 'all') {        $('.category-icon').show();      }    });    /*end*/         
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
              window.location.href = BASE_URL + 'administrator/tbl_amenities';
            }
          });
    
        return false;
      }); /*end btn cancel*/
    
      $('.btn_save').click(function(){
        $('.message').fadeOut();
            
        var form_tbl_amenities = $('#form_tbl_amenities');
        var data_post = form_tbl_amenities.serializeArray();
        var save_type = $(this).attr('data-stype');

        data_post.push({name: 'save_type', value: save_type});
    
        $('.loading').show();
    
        $.ajax({
          url: BASE_URL + '/administrator/tbl_amenities/add_save',
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
      
       
 
       
    
    
    }); /*end doc ready*/
</script>