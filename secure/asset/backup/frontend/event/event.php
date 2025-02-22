</div>
<section class="fixBanner" style="background-image: url('<?=base_url()?>uploads/front/images/eventbg.jpg')">
  <div class="container">
    <div class="row">
      <div class="col-md-12 headingBanner">
         <h2> Events happening <span> around the world </span> </h2>
         <p> Explore and Share greatest concerts, meetups, conferences and <br /> much more </p>

         <div class="search-form">
                 <div class="inputWithIcon br-right">
                   <input type="text" class="form-control" placeholder="Search events or categories ">
                   
                 </div>
                 <div class="inputWithIcon">
                    <select class="form-control" id="sel1">
                      <option value="">Category</option>
                      <?php foreach ($event_category as $singlecategory) { ?>
                        <option <?=$singlecategory->id; ?>><?=$singlecategory->category_name; ?></option>  
                      <?php }
                      ?>                      
                    </select>
                 </div>
                 <div class="inputWithIcon">
                    <select class="form-control" id="sel1">
                      <option>Any Date</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                    </select>
                 </div>
                 <div class="inputWithIcon">
                    <select class="form-control" id="sel1">
                      <option>Any Price</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                    </select>
                 </div>
                 <button type="" class="btn btn-info"><img src="<?=base_url()?>uploads/front/images/searchicon.png" alt="" /></button>
              </div>
      </div>
    </div>
  </div>
</section>

<section class="aboutus eventPage secPad">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2> It's <?=count($events);?> events here. </h2>
        
        <!-- Event -->
        <?php foreach ($events as $singleevent) { ?>
          <div class="eventBox">
          <div class="eventLeft">
            <div class="ProfileDiv">
              <a href="<?=base_url('event/singleevent/').$singleevent->id;?>">
                <div class="proDiv">
                  <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/wy_event/<?=$singleevent->event_image?>')">
                  </div>
                </div>
              </a>
            </div>
            <div class="eventText">
              <ul class="list-unstyled">
                <li><?=   date("h:i A", strtotime($singleevent->event_start_time));?> </li> 
                <li> --- </li>
                <li><?=   date("h:i A", strtotime($singleevent->event_end_time));?> </li> 
                <li> $<?=$singleevent->ticket_price?> </li>
              </ul>
            </div>
          </div>
          <div class="eventRight">
            <span><?=$singleevent->organiser_name?></span>
            <h3> <a href="<?=base_url('event/singleevent/').$singleevent->id;?>"> <?=$singleevent->event_name?> </a> </h3>
            <p> <?=$singleevent->description?> </p>
          </div>
        </div>
        <?php } ?>
        <!-- <div class="eventBox">
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
                <li> $300 </li>
              </ul>
            </div>
          </div>
          <div class="eventRight">
            <span>Robert Gates</span>
            <h3> <a href="event_single.php"> Growing Power Politics </a> </h3>
            <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
          </div>
        </div> -->
        <!-- End  -->

        
      </div>
    </div>
  </div>  
</section>

</div>