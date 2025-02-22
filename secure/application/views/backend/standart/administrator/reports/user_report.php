<style type="text/css">
    
</style>
<!-- Content Header (Page header) -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  
<section class="content-header">
   <h1>
      User Orders Reports<small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">User Orders Reports</li>
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

                     <h3 class="widget-user-username">User Orders Reports</h3>

                     <h5 class="widget-user-desc"><?= cclang('list_all', ['User Orders']); ?>  <i class="label bg-yellow"><?= count($userorders); ?>  <?= cclang('items'); ?></i></h5>

                  </div>

                  <div class="table-responsive table-full-width">
                    <table class="table table-condensed table-striped table-bordered display responsive no-wrap" id="everyday_tbl">
                      <thead>
                        <tr>
                          <th>Sno</th>
                          <th>Name</th>
                          <th>Mobile</th>
                          <th>Email</th>
                          <th>OrderId</th>
                          <th>Total Qty Purchased</th>                          
                          <th>Order Created Date</th>
                          <th>More Details</th>
                        </tr>
                      </thead>          
                      <tbody>
                        <?php $i=1; foreach ($userorders as $singleorder) {?>              
                        <tr class="record">
                          <td> <?php echo $i; ?> </td>
                          <td> <?php echo $singleorder['username']; ?> </td>
                          <td> <?php echo $singleorder['mobile']; ?> </td>
                          <td> <?php echo $singleorder['email']; ?> </td>
                          <td><?=$singleorder['orderid'];?></td>
                          <td> <?= $singleorder['quantity']?> KG </td>
                          <td> <?= $singleorder['created_at']?></td>
                          <td><?php if($singleorder['quantity'] > 0)  
                            { ?> <a href="<?= base_url('administrator/reports/singleUserOrder/').$singleorder['userid'].'/'.$singleorder['orderid']?>" class="btn btn-primary btn-sm">More Details</a>
                            <?php } ?>
                          </td>
                        </tr>  
                        <?php $i++; } ?>              
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
