</div>

<style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>

<section class="fixBanner eventSingle" style="background-image: url('<?=base_url()?>uploads/wy_event/<?=$event->event_image?>')">
  <div class="container">
    <div class="row eventDetails">
      <div class="col-md-12">
         <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-7 col-xs-12">
            <div class="eventDetails1">
              <span><?=$event->category_name.' | '.$event->age_group; ?></span>
              <h3> <?=$event->event_name; ?> </h3>
              <h4>$<?=$event->ticket_price; ?></h4>
              </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-5 col-xs-12">
              <div class="eventDetails1">
              <div><img src="<?=base_url()?>uploads/front/images/calender.png" /> <?=$event->category_name.' | '.$event->age_group; ?></div>

              <span><img src="<?=base_url()?>uploads/front/images/navigation.png" /> <?=$event->event_location;?></span>
              </div>  
            </div>
         </div>
      </div>
      
      <div class="col-md-12">
        <div class="eventbtn">
          <a href="event_single_ongoing.php" class="btn btn-info btn2">Buy Ticket</a>
          <a data-toggle="modal" data-target="#shareModal" class="btn btn-info btn1">Share</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="eventSingle1 secPad1">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-sm-12 col-md-7">
        <h2> Information </h2>
        <p><?=$event->description?></p>

        <!-- <p> Founded in the summer of 2017, the women of Curium piano trio have gained reputations as performers that radiate dynamism and presence. Specializing in performing the music of female composers, they are committed to bringing creative and diverse musical programming to their audiences. The Curium trio highlights the works of women composers and performers alongside traditional piano trio repertoire, and have brought together a community of people with their representation of diversity and women. </p> -->

        <hr> 

        <h2> Location </h2>



        <div class="map">
          <div id="map"></div>
          
        </div>

        <hr>
        
        <div class="eventOrganizer">
          <h2> Organizer </h2>
          <div class="eventLeft">
            <div class="ProfileDiv">
              <a href="event_single.php">
                <div class="proDiv">
                  <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/wy_event/<?=$event->organizer_image?>')">
                  </div>
                </div>
              </a>
            </div>
            <div class="eventText">
              <h3> <?=$event->organiser_name; ?> </h3>
              <p> <br></p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-sm-12 col-md-5">
        <h3> Realted Events </h3>
        <br>
        <!-- Realted Events-->
        <?php if($relatedevents)
        {
          $relatedcount = 0;
          foreach ($relatedevents as $singleevent) { 
            if ($relatedcount<6) {
               ?>
              <div class="post_box right_box">
                   <div class="memberProfileDiv">
                      <div class="proDiv">
                         <div class="profileImg" style="background-image: url('<?=base_url()?>uploads/wy_event/<?=$singleevent->event_image;?>')">
                         </div>
                      </div>
                   </div>
                   <div class="post_text">
                      <h4> <a href="<?=base_url('event/singleevent/').$singleevent->id;?>"> <?=$singleevent->event_name;?> </a> </h4>
                      <h5> <img src="<?=base_url()?>uploads/front/images/navi.png" /> <?=$singleevent->event_location;?> </h5>
                      <div class="post_coment">
                         <span><?= date("d M", strtotime($singleevent->event_start_date));?></span>
                         <span><a href="<?=base_url('event/singleevent/').$singleevent->id;?>" class="btn btn-info btn1">View More</a></span>
                      </div>
                   </div>
              </div>            
          <?php $relatedcount++; } 
          }   //end foreach loop

        } else{ ?>
          <div class="eveb text-center">
            No Event Found.
          </div>
        <?php } ?>
        
        <!-- End -->

       

        <!-- Realted Events-->
        <?php if (count($relatedevents) > 6) {?>
          <div class="eveb text-center">
          <a href="#" class="btn btn-info btn2">See All</a>
        </div>
        <?php } ?>        
        <!-- End -->
      </div>
    </div>
  </div>  
</section>


<!-- Modal -->
<div id="shareModal" class="modal fade shareModal" >
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body text-center">
        <a data-dismiss="modal" class="cros"><img src="<?=base_url()?>uploads/front/images/cross.png" alt="" /></a>

 
        <h2> Copy & Share URL </h2>

        <div class="form-group promocopy-outer">
          <input type="text" readonly id="promoCopy" class="form-control" value="www.whereyou.ng/events/music/#012354" id="usr">
          
          <button class="btn btn-info btn2"><span class="tersalin">Copy</span></button>
        </div>

        <div class="social">
            <ul class="list-inline list-unstyled">
                <li class="list-inline-item">
                    <a href="#"><img src="<?=base_url()?>uploads/front/images/whatsapp.png" alt="" /></a>
                </li>
                <li class="list-inline-item">
                    <a href="#"><img src="<?=base_url()?>uploads/front/images/facebook1.png" alt="" /></a>
                </li>
                <li class="list-inline-item">
                    <a href="#"><img src="<?=base_url()?>uploads/front/images/insta1.png" alt="" /></a>
                </li>
                <li class="list-inline-item">
                    <a href="#"><img src="<?=base_url()?>uploads/front/images/twitter1.png" alt="" /></a>
                </li>
                <li class="list-inline-item">
                    <a href="#"><img src="<?=base_url()?>uploads/front/images/gmail.png" alt="" /></a>
                </li>
            </ul>
        </div>        
      </div>
    </div>
  </div>
</div>
<div>




<script>
  $(function() {
    $('.promocopy-outer button').click(function() {
    $(this).prev("input").focus();
    $(this).prev("input").select();
   
    document.execCommand('copy');
    // $(".copied").text("Copied to clipboard").show().fadeOut(1200);
      });
  });

// Initialize and add the map
function initMap() {
  // The location of Uluru
  var uluru = {lat: <?=$singleevent->latitude?>, lng: <?=$singleevent->longitude?>};
  //var uluru = {lat: -25.344, lng: 131.036};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 8, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
}
    </script>
    <!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBg6pzYxtVQ7JvpRzh39mNFhu6ssWksH_A&callback=initMap">
    </script>