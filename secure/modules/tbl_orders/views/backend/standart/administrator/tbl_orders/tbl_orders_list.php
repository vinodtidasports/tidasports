
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>

<script type="text/javascript">
//This page is a result of an autogenerated content made by running test.html with firefox.
function domo(){
 
   // Binding keys
   $('*').bind('keydown', 'Ctrl+a', function assets() {
       window.location.href = BASE_URL + '/administrator/Tbl_orders/add';
       return false;
   });

   $('*').bind('keydown', 'Ctrl+f', function assets() {
       $('#sbtn').trigger('click');
       return false;
   });

   $('*').bind('keydown', 'Ctrl+x', function assets() {
       $('#reset').trigger('click');
       return false;
   });

   $('*').bind('keydown', 'Ctrl+b', function assets() {

       $('#reset').trigger('click');
       return false;
   });
}

jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
   <h1>
      Orders<small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
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
                     <div class="row pull-right">
                        <?php is_allowed('tbl_orders_export', function(){?>
                        <a class="btn btn-flat btn-success" title="<?= cclang('export'); ?> Tbl Orders" href="<?= site_url('administrator/tbl_orders/export'); ?>"><i class="fa fa-file-excel-o" ></i> <?= cclang('export'); ?> XLS</a>
                        <?php }) ?>
                        <?php is_allowed('tbl_orders_export', function(){?>
                        <a class="btn btn-flat btn-success" title="<?= cclang('export'); ?> pdf Tbl Orders" href="<?= site_url('administrator/tbl_orders/export_pdf'); ?>"><i class="fa fa-file-pdf-o" ></i> <?= cclang('export'); ?> PDF</a>
                        <?php }) ?>
                     </div>
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET; ?>/img/list.png" alt="User Avatar">
                     </div>
                     <!-- /.widget-user-image -->
                     <h3 class="widget-user-username">Orders</h3>
                     <h5 class="widget-user-desc"><?= cclang('list_all', ['Orders']); ?>  <i class="label bg-yellow"><?= $tbl_orders_counts; ?>  <?= cclang('items'); ?></i></h5>
                  </div>

                  <form name="form_tbl_orders" id="form_tbl_orders" action="<?= base_url('administrator/tbl_orders/index'); ?>">
                  

                  <div class="table-responsive"> 
                  <table class="table table-bordered table-striped dataTable">
                     <thead>
                        <tr class="">
                           <th>
                            <input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all">
                           </th>
                           <th>User</th>
                           <th>Partner</th>
                           <th>Type</th>                           
                           
                           <th>Status</th>                           
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody id="tbody_tbl_orders">
                     <?php foreach($tbl_orderss as $tbl_orders): ?>
                        <tr>
                           <td width="5">
                              <input type="checkbox" class="flat-red check" name="id[]" value="<?= $tbl_orders->id; ?>">
                           </td>
                           
                           <td><a href="<?=base_url('administrator/tbl_user/view/').$tbl_orders->user_id;?>"><?= GetUserInfo($tbl_orders->user_id)->name; ?></a></td> 
                           <td><a href="<?=base_url('administrator/tbl_user/view/').$tbl_orders->partner_id;?>"><?= GetUserInfo($tbl_orders->partner_id)->name; ?></a></td> 
                           <?php
                              $type = $tbl_orders->type;
                              if($type==1){
                                 $typename = "Facility Booking";
                              }elseif($type==2){
                                 $typename = "Academy Session booking";
                              }elseif($type==3){
                                 $typename = "Tournament Booking";
                              }else{
                                 $typename = "";
                              }

                              
                              $status = $tbl_orders->status;
                              if($status==1){
                                 $statusvalue = "Complete";
                              }elseif($status==2){
                                 $statusvalue = "Pending";
                              }elseif($status==3){
                                 $statusvalue = "Failed";
                              }else{
                                 $statusvalue = "";
                              }
                           
                           ?>
                           <td><?= $typename; ?></td>                            
                           
                           <td><?= $statusvalue; ?></td> 
                           <td width="200">
                              <?php is_allowed('tbl_orders_view', function() use ($tbl_orders){?>
                              <a href="<?= site_url('administrator/tbl_orders/view/' . $tbl_orders->id); ?>" class="label-default"><i class="fa fa-newspaper-o"></i> <?= cclang('view_button'); ?>
                              <?php }) ?>
                              <?php is_allowed('tbl_orders_delete', function() use ($tbl_orders){?>
                              <a href="javascript:void(0);" data-href="<?= site_url('administrator/tbl_orders/delete/' . $tbl_orders->id); ?>" class="label-default remove-data"><i class="fa fa-close"></i> <?= cclang('remove_button'); ?></a>
                               <?php }) ?>
                           </td>
                        </tr>
                      <?php endforeach; ?>
                      <?php if ($tbl_orders_counts == 0) :?>
                         <tr>
                           <td colspan="100">
                           Orders data is not available
                           </td>
                         </tr>
                      <?php endif; ?>
                     </tbody>
                  </table>
                  </div>
               </div>
               <hr>
               <!-- /.widget-user -->
               <div class="row">
                  <div class="col-md-8">
                     <div class="col-sm-2 padd-left-0 " >
                        <select type="text" class="form-control chosen chosen-select" name="bulk" id="bulk" placeholder="Site Email" >
                           <option value="">Bulk</option>
                           <option value="delete">Delete</option>
                        </select>
                     </div>
                     <div class="col-sm-2 padd-left-0 ">
                        <button type="button" class="btn btn-flat" name="apply" id="apply" title="<?= cclang('apply_bulk_action'); ?>"><?= cclang('apply_button'); ?></button>
                     </div>
                     <div class="col-sm-3 padd-left-0  " >
                        <input type="text" class="form-control" name="q" id="filter" placeholder="<?= cclang('filter'); ?>" value="<?= $this->input->get('q'); ?>">
                     </div>
                     <div class="col-sm-3 padd-left-0 " >
                        <select type="text" class="form-control chosen chosen-select" name="f" id="field" >
                           <option value=""><?= cclang('all'); ?></option>
                            <option <?= $this->input->get('f') == 'user_id' ? 'selected' :''; ?> value="user_id">User Id</option>
                           <option <?= $this->input->get('f') == 'partner_id' ? 'selected' :''; ?> value="partner_id">Partner Id</option>
                           <option <?= $this->input->get('f') == 'type' ? 'selected' :''; ?> value="type">Type</option>
                           <option <?= $this->input->get('f') == 'facility_booking_id' ? 'selected' :''; ?> value="facility_booking_id">Facility Booking Id</option>
                           <option <?= $this->input->get('f') == 'session_id' ? 'selected' :''; ?> value="session_id">Session Id</option>
                           <option <?= $this->input->get('f') == 'tournament_id' ? 'selected' :''; ?> value="tournament_id">Tournament Id</option>
                           <option <?= $this->input->get('f') == 'transaction_id' ? 'selected' :''; ?> value="transaction_id">Transaction Id</option>
                           <option <?= $this->input->get('f') == 'status' ? 'selected' :''; ?> value="status">Status</option>
                           <option <?= $this->input->get('f') == 'created_at' ? 'selected' :''; ?> value="created_at">Created At</option>
                           <option <?= $this->input->get('f') == 'updated_at' ? 'selected' :''; ?> value="updated_at">Updated At</option>
                          </select>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="Apply" title="<?= cclang('filter_search'); ?>">
                        Filter
                        </button>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= base_url('administrator/tbl_orders');?>" title="<?= cclang('reset_filter'); ?>">
                        <i class="fa fa-undo"></i>
                        </a>
                     </div>
                  </div>
                  </form>                  <div class="col-md-4">
                     <div class="dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate" >
                        <?= $pagination; ?>
                     </div>
                  </div>
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
   
    $('.remove-data').click(function(){

      var url = $(this).attr('data-href');

      swal({
          title: "<?= cclang('are_you_sure'); ?>",
          text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
          cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            document.location.href = url;            
          }
        });

      return false;
    });


    $('#apply').click(function(){

      var bulk = $('#bulk');
      var serialize_bulk = $('#form_tbl_orders').serialize();

      if (bulk.val() == 'delete') {
         swal({
            title: "<?= cclang('are_you_sure'); ?>",
            text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
            cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
            closeOnConfirm: true,
            closeOnCancel: true
          },
          function(isConfirm){
            if (isConfirm) {
               document.location.href = BASE_URL + '/administrator/tbl_orders/delete?' + serialize_bulk;      
            }
          });

        return false;

      } else if(bulk.val() == '')  {
          swal({
            title: "Upss",
            text: "<?= cclang('please_choose_bulk_action_first'); ?>",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Okay!",
            closeOnConfirm: true,
            closeOnCancel: true
          });

        return false;
      }

      return false;

    });/*end appliy click*/


    //check all
    var checkAll = $('#check_all');
    var checkboxes = $('input.check');

    checkAll.on('ifChecked ifUnchecked', function(event) {   
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });

  }); /*end doc ready*/
</script>