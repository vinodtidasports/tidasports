<style>
   .navbar
   {
      background-color: #0e0f48;
   }
   .navbar .navbar-brand 
   {
       padding: 8px 15px;
   }

   .navbar .navbar-brand img 
   {
       width: 70px;
   }

   header
   {
      height: 70px;
   }

   body
   {
      background-color: #f7f4f4;
   }
</style>
<?php $sessiondata = $this->session->userdata()['userData']; print_r($sessiondata); print_r($profiledetail); 
   $profiletype = $sessiondata['profile_type'];
?>

<section class="userProfile secPad50">
   <div class="container bgWhite pad30">
      <div class="row rowFlex">
         <div class="col-md-5">
            <div class="userPro">
               <div class="ProfileDiv">
                  <a href="event_single.php">
                     <div class="proDiv">
                        <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/front/images/userProfile.jpg')">
                        </div>
                     </div>
                  </a>
               </div>
            </div>
         </div>
         <div class="col-md-7">
            <div class="userText">
               <h3> <?= $profiledetail->name;?> </h3>
               <ul class="list-unstyled">
                  <li> <span> <img src="<?=base_url()?>uploads/front/images/message.png" /> </span> <?= $profiledetail->email;?> </li>
                  <li> <span> <img src="<?=base_url()?>uploads/front/images/phone.png" /> </span> <?= $profiledetail->contact_no;?> </li>
                  <li> <span> <img src="<?=base_url()?>uploads/front/images/birthday.png" /> </span> <?= $profiledetail->dob;?>DD MM YYYY </li>
                  <li> <span> <img src="<?=base_url()?>uploads/front/images/nv.png" /> </span> <?= $profiledetail->address;?>House no. State City Country pin Code </li>
               </ul>
            </div>
         </div>

         <a href="#" class="btn btn-info editPro">Edit Profile</a>
         <?php if($profiletype==1){?>
            <a href="#" class="btn btn-info editPro1">Become an Influencer</a>
         <?php } ?>
      </div>

      <hr>

      <div class="row">
         <div class="col-md-12">
            <div class="panel with-nav-tabs panel-default">
               <div class="panel-heading">
                  <ul class="nav nav-tabs">
                     <li class="active"><a href="#tab1default" data-toggle="tab" aria-expanded="true">Upcoming Events</a></li>
                     <li class=""><a href="#tab2default" data-toggle="tab" aria-expanded="false">Past Events</a></li>
                  </ul>
               </div>
               <div class="panel-body">
                  <div class="tab-content">
                     <div class="tab-pane fade active in eventPage" id="tab1default">
                        <!-- Event Box -->
                        <div class="eventBox">
                           <div class="eventLeft">
                              <div class="ProfileDiv">
                                 <a href="event_single.php">
                                    <div class="proDiv">
                                       <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/front/images/eventsingle.jpg')">
                                       </div>
                                    </div>
                                 </a>
                              </div>
                              <div class="eventText">
                                 <ul class="list-unstyled">
                                    <li> 9:00 am </li> 
                                    <li> --- </li>
                                    <li> 11:00 am </li>
                                    
                                 </ul>
                              </div>
                           </div>
                           <div class="eventRight">
                              <span>Robert Gates</span>
                              <h3> <a href="event_single.php"> Growing Power Politics </a> </h3>
                              <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
                           </div>
                        </div>
                        <!-- End Of Event Box -->

                        <!-- Event Box -->
                        <div class="eventBox">
                           <div class="eventLeft">
                              <div class="ProfileDiv">
                                 <a href="event_single.php">
                                    <div class="proDiv">
                                       <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/front/images/eventsingle.jpg')">
                                       </div>
                                    </div>
                                 </a>
                              </div>
                              <div class="eventText">
                                 <ul class="list-unstyled">
                                    <li> 9:00 am </li> 
                                    <li> --- </li>
                                    <li> 11:00 am </li>
                                    
                                 </ul>
                              </div>
                           </div>
                           <div class="eventRight">
                              <span>Robert Gates</span>
                              <h3> <a href="event_single.php"> Growing Power Politics </a> </h3>
                              <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
                           </div>
                        </div>
                        <!-- End Of Event Box -->

                        <!-- Event Box -->
                        <div class="eventBox">
                           <div class="eventLeft">
                              <div class="ProfileDiv">
                                 <a href="event_single.php">
                                    <div class="proDiv">
                                       <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/front/images/eventsingle.jpg')">
                                       </div>
                                    </div>
                                 </a>
                              </div>
                              <div class="eventText">
                                 <ul class="list-unstyled">
                                    <li> 9:00 am </li> 
                                    <li> --- </li>
                                    <li> 11:00 am </li>
                                    
                                 </ul>
                              </div>
                           </div>
                           <div class="eventRight">
                              <span>Robert Gates</span>
                              <h3> <a href="event_single.php"> Growing Power Politics </a> </h3>
                              <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
                           </div>
                        </div>
                        <!-- End Of Event Box -->

                        <!-- Event Box -->
                        <div class="eventBox">
                           <div class="eventLeft">
                              <div class="ProfileDiv">
                                 <a href="event_single.php">
                                    <div class="proDiv">
                                       <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/front/images/eventsingle.jpg')">
                                       </div>
                                    </div>
                                 </a>
                              </div>
                              <div class="eventText">
                                 <ul class="list-unstyled">
                                    <li> 9:00 am </li> 
                                    <li> --- </li>
                                    <li> 11:00 am </li>
                                    
                                 </ul>
                              </div>
                           </div>
                           <div class="eventRight">
                              <span>Robert Gates</span>
                              <h3> <a href="event_single.php"> Growing Power Politics </a> </h3>
                              <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
                           </div>
                        </div>
                        <!-- End Of Event Box -->
                     </div>
                     <div class="tab-pane fade eventPage" id="tab2default"><div class="no_result">
                     No Past Events</div></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>   
</section>
