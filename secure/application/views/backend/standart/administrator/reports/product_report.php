<style type="text/css">
    
</style>
<!-- Content Header (Page header) -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  
<section class="content-header">
   <h1>
      Product Reports<small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Product Reports</li>
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
                        <img class="img-circle" src="<?= BASE_ASSET; ?>/img/list.png" alt="User Avatar">
                     </div>
                     <!-- /.widget-user-image -->
                     <h3 class="widget-user-username">Product Reports</h3>
                     <h5 class="widget-user-desc"><?= cclang('list_all', ['Reports']); ?>  <i class="label bg-yellow"><?= $sc_items_counts; ?>  <?= cclang('items'); ?></i></h5>
                  </div>
                  <div class="table-responsive table-full-width">
                    <table class="table table-condensed table-striped table-bordered display responsive no-wrap" id="everyday_tbl">
                      <thead>
                        <tr>
                          <th>Sr. No</th>
                           <th>Product Name</th>
                           <th>Image</th>
                           <th>Remaining Stock</th>
                           <th>Items Ordered</th>
                        </tr>
                      </thead>          
                      <tbody>
                        <?php $i=1; foreach($sc_itemss as $sc_items): 
                          $itemid = $sc_items->id;
                          $getordereditems = GetTotalQuantity($itemid);
                          $quantity = 0;
                          foreach ($getordereditems as $singleitem) {
                            $quantity += $singleitem->quantity;
                          }

                     ?>            
                        <tr class="record">
                          <td><?=$i;?></td>
                           <td><?= _ent($sc_items->item_name); ?></td> 
                           <td>

                              <?php if (!empty($sc_items->item_image)): ?>

                                <?php if (is_image($sc_items->item_image)): ?>

                                <a class="fancybox" rel="group" href="<?= BASE_URL . 'uploads/sc_items/'.$sc_items->item_image; ?>">

                                  <img src="<?= BASE_URL . 'uploads/sc_items/'.$sc_items->item_image; ?>" class="image-responsive" alt="image sc_order" title="item_image" width="40px">

                                </a>

                                <?php else: ?>

                                  <a href="<?= BASE_URL . 'administrator/file/download/sc_items/' . $sc_items->item_image; ?>">

                                   <img src="<?= get_icon_file($sc_items->item_image); ?>" class="image-responsive image-icon" alt="image sc_order" title="item_image <?= $sc_items->item_image; ?>" width="40px"> 

                                 </a>

                                <?php endif; ?>

                              <?php endif; ?>

                           </td>

                            

                           <td><?= _ent($sc_items->quantity); ?></td> 

                           <td><?= $quantity; ?></td>

                           </tr>

                      <?php $i++; endforeach; ?>
                          <?php if ($sc_items_counts == 0) :?>

                         <tr>

                           <td colspan="100">

                           Product data is not available

                           </td>

                         </tr>

                      <?php endif; ?>
                                 
                      </tbody>  
                    </table>
                  </div>
                  

                  

               </div>

               <hr>

               
            </div>

            <!--/box body -->

         </div>

         <!--/box -->

      </div>

   </div>

</section>

<!-- /.content -->



<!-- Page script -->
   <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/responsive/1.0.5/js/dataTables.responsive.min.js"></script>

<script type="text/JavaScript">
        $(document).ready(function(){
            $('#everyday_tbl').DataTable({
                responsive: true,
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bInfo": false,
                "bAutoWidth": true,
            });
        });
    </script>


