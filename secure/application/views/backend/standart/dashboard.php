<style type="text/css">

   .widget-user-header {

      padding-left: 20px !important;

   }

</style>



<link rel="stylesheet" href="<?= BASE_ASSET; ?>admin-lte/plugins/morris/morris.css">



<section class="content-header">

    <h1>

        <?= cclang('dashboard') ?>

        <small>

            

        <?= cclang('control_panel') ?>

        </small>

    </h1>

    <ol class="breadcrumb">

        <li>

            <a href="#">

                <i class="fa fa-dashboard">

                </i>

                <?= cclang('home') ?>

            </a>

        </li>

        <li class="active">

            <?= cclang('dashboard') ?>

        </li>

    </ol>

</section>



<section class="content">

    <div class="row">

      <?php cicool()->eventListen('dashboard_content_top'); ?>



       <div class="col-md-3 col-sm-6 col-xs-12 ">
            <div class="info-box button" onclick="goUrl('administrator/tbl_user')">
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-users">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Total Users : <?php print_r($GetTotalUser);?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/tbl_user/index?bulk=&q=2&f=type')">
                <span class="info-box-icon bg-yellow">
                    <i class="fa fa-cubes">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Service Providers : <?= $serviceprovider_count;?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/tbl_user/index?bulk=&q=1&f=type')">
                <span class="info-box-icon" style="background-color: #28a745; color:#fff;">
                    <i class="fa fa-users">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Customers : <?= $customer_count;?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/user')">
                <span class="info-box-icon" style="background-color: #dc3545; color:#fff;">
                    <i class="fa fa-user-secret">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Admin Users : <?= $auth_count;?>
                    </span>
                </div>
            </div>
        </div>
		
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/tbl_sports')">
                <span class="info-box-icon" style="background-color: #9e979dba; color:#fff;">
                    <i class="fa fa-futbol-o">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Sports Management : <?= $sports_count;?>
                    </span>
                </div>
            </div>
        </div>
		
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/tbl_user_subscription')">
                <span class="info-box-icon" style="background-color: #e757d3ba; color:#fff;">
                    <i class="fa fa-scribd">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Subscription : <?= $subscription_count;?>
                    </span>
                </div>
            </div>
        </div>

		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/tbl_amenities')">
                <span class="info-box-icon" style="background-color: #180f17ba; color:#fff;">
                    <i class="fa fa-hotel">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Amenities : <?= $amenities_count;?>
                    </span>
                </div>
            </div>
        </div>
		
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box button" onclick="goUrl('administrator/tbl_venue')">
                <span class="info-box-icon" style="background-color: #566de4ba; color:#fff;">
                    <i class="fa fa-map-marker">
                    </i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        Venue : <?= $venue_count;?>
                    </span>
                </div>
            </div>
        </div>
		
         <!-- <div class="col-md-3 col-sm-6 col-xs-12">

            <div class="info-box button" onclick="goUrl('administrator/page')">

                <span class="info-box-icon bg-aqua">

                    <i class="ion ion-ios-paper">

                    </i>

                </span>

                <div class="info-box-content">

                    <span class="info-box-text">

                        <?= cclang('page_builder') ?>

                    </span>

                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">

            <div class="info-box button" onclick="goUrl('administrator/form')">

                <span class="info-box-icon bg-yellow">

                    <i class="ion ion-android-list">

                    </i>

                </span>

                <div class="info-box-content">

                    <span class="info-box-text">

                        <?= cclang('form_builder') ?>

                    </span>

                </div>

            </div>

        </div> -->



    </div>

  

      <!-- /.row -->

      <?php cicool()->eventListen('dashboard_content_bottom'); ?>



</section>

<!-- /.content -->

