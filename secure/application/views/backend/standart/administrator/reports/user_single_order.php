<!-- Content Header (Page header) -->
 
<section class="content-header">
   <h1>
      User Orders Reports<small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">User Order Reports</li>
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

                  </div>

                  <div id='DivIdToPrint'>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="invoice-title">
                          <h2>Invoice</h2><h3 class="pull-right">Order # <?php echo $orderdetail[0]->order_id; ?></h3>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-xs-6">
                            <address>
                            <strong>From:</strong><br>
                              <?= $userinfo->user_name; ?><br>
                              <?= $userinfo->user_address; ?><br>
                              <?= $userinfo->city.', '.$userinfo->state.'<br>'.$userinfo->pincode; ?>
                            </address>
                          </div>
                          <div class="col-xs-6">
                            <address>
                              <strong>To:</strong><br>
                              SS Cart<br>
                              1234 Main<br>
                              Apt. 4B<br>
                              Springfield, ST 54321
                            </address>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 col-md-offset-6 text-right">
                            <address>
                              <strong>Order Date:</strong><br>
                              <?= $orderdetail[0]->created_at; ?><br><br>
                            </address>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title"><strong>Order summary</strong></h3>
                          </div>
                          <div class="panel-body">
                            <div class="table-responsive">
                              <table class="table table-condensed">
                                <thead>
                                  <tr>
                                    <td style="text-align:center;"><strong>Item</strong></td>
                                    <td style="text-align:center;"><strong>Price</strong></td>
                                    <td style="text-align:center;"><strong>Quantity</strong></td>
                                    <td style="text-align:center;"><strong>Totals</strong></td>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderdetail as $singleitem) {?>
                                      <tr>
                                        <td style="text-align:center;"><?php echo $singleitem->item_name; $rrows['item_name']; ?></td>
                                        <td style="text-align:center;"><?php echo $singleitem->price;?></td>
                                        <td style="text-align:center;"><?php echo $singleitem->quantity;?> KG</td>
                                        <td style="text-align:center;"><?php echo $singleitem->price * $singleitem->quantity;?></td>
                                      </tr>
                                    <?php } ?>
                                      <tr>
                                        <td colspan="2"></td>
                                        <td style="text-align:center;">Total Quantity : <?=$totalquantity;?> KG</td>
                                        <td style="text-align:center;">Grand Total : <?=$totalamount;?></td>
                                      </tr>
                                               
                                </tbody>
                              </table>
                              
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                
                 <a href="" class="btn btn-primary" style="float:right;" onclick='printDiv();'>Print</a>
                  <!-- Print div end -->
               </div>

               

               
            </div>

            <!--/box body -->

         </div>

         <!--/box -->

      </div>

   </div>

</section>

<!-- /.content -->



<script>
        function printDiv() 
        {
        
          var divToPrint=document.getElementById('DivIdToPrint');
        
          var newWin=window.open('','Print-Window');
        
          newWin.document.open();
        
          newWin.document.write('<html><link href="<?=base_url()?>asset//admin-lte/bootstrap/css/bootstrap.min.css" rel="stylesheet"><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        
          newWin.document.close();
        
          setTimeout(function(){newWin.close();},10);
        
        }
    </script>
