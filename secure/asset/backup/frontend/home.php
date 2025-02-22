<section class="homeBanner">
  <div style="text-align:center" > 
    <video id="video" loop poster="<?=base_url('uploads/front/')?>images/poster.png">
      <source src="<?=base_url('uploads/')?>video/event.mp4" type="video/mp4">
    </video>
  </div>
  <div class="play-pause-btn" data-click="0"><img src="<?=base_url('uploads/front/')?>images/play.png"></div>
</section>

<section id="aboutUs" class="aboutus secPad bgImg">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <h2> About Us </h2>
        <p class="pBold"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s </p>
        <img src="<?=base_url('uploads/front/')?>images/qoute.png" alt="" />
        <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s </p>

        <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
      </div>

      <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <img src="<?=base_url('uploads/front/')?>images/about.png" class="img-responsive center-block" alt="" />
      </div>
    </div>
  </div>  
</section>

<section id="works" class="howWork secPad" style="background-image: url('<?=base_url()?>uploads/front/images/how.jpg')">
  <div class="container">
    <div class="row">
      <div class="col-md-12 headingMain">
        <h2> How it works </h2>
        <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br />Lorem Ipsum has been the industry's standard dummy text ever since the 1500s </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="main-timeline">

            <div class="timeline colorCircle">
              <span class="lineBor"></span>
                <a href="#" class="timeline-content">
                    <span class="icon fa fa-briefcase"></span>
                    <h3 class="title">Find an event</h3>
                    <p class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dum
                    </p>
                </a>
            </div>

            <div class="timeline colorCircle1">
                <a href="#" class="timeline-content">
                    <span class="icon fa fa-rocket"></span>
                    <h3 class="title">Copy your special link</h3>
                    <p class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dum
                    </p>
                </a>
            </div>

            <div class="timeline colorCircle2">

              <span class="lineBor"></span>
                <a href="#" class="timeline-content">
                    <span class="icon fa fa-rocket"></span>
                    <h3 class="title">Get Paid</h3>
                    <p class="description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dum
                    </p>
                </a>
            </div>
        </div>  
      </div>
    </div>
  </div>  
</section>



<section class="aboutus contact secPad bgImg">
  <div class="container">
    <div class="row">
      <div class="col-md-12 headingMain">
        <h2> GET IN TOUCH </h2>
        <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been <br /> the industry's standard dummy text ever since the 1500s </p>
      </div>
    </div>
    <div class="row mar40">
      <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
          <div class="address"> 
            <h5> Contact </h5>
            <ul class="list-unstyled addres">
                <li> 
                  <span> <img src="<?=base_url('uploads/front/')?>images/nav2.png" alt="" /> </span> 
                  <span> 405 Brasddway, New York, NY 10013 </span> 
                </li>
                <li> 
                  <span> <img src="<?=base_url('uploads/front/')?>images/msg.png" alt="" /> </span> 
                  <span> 405 Brasddway, New York, NY 10013 </span> 
                </li>
                <li>
                  <span> <img src="<?=base_url('uploads/front/')?>images/call.png" alt="" /> </span> 
                  <span> 405 Brasddway, New York, NY 10013 </span> 
                </li>
            </ul>
          

            <div class="social">
                <ul class="list-inline list-unstyled">
                    <li class="list-inline-item">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fa fa-google-plus"></i></a>
                    </li>
                </ul>
            </div>
          </div>
      </div>

      <div class="col-md-8 col-lg-8 col-sm-6 col-xs-12">
          <div class="card">
              <div class="card-body">
                   <form>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                <input id="Full Name" name="Full Name" placeholder="First Name" class="form-control" type="text">
                              </div>
                              <div class="form-group col-md-6">
                                <input id="Full Name" name="Full Name" placeholder="Last Name" class="form-control" type="text">
                              </div>
                            </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                              </div>
                              <div class="form-group col-md-6">
                                  <input id="Mobile No." name="" placeholder="Phone Number" class="form-control" required="required" type="text">
                              </div>
                              <div class="form-group col-md-12">
                                  <textarea id="comment" name="comment" cols="40" rows="5" placeholder="Message"class="form-control"></textarea>
                              </div>
                          </div>
                          
                          <div class="form-row">
                            <div class="form-group col-md-12">
                                 <button type="button" class="btn btn-danger btn2">Submit</button>
                            </div>
                          </div>
                      </form>
              </div>
          </div>
      </div>
    </div>
  </div>  
</section>

<section class="howWork secPad50" style="background-image: url('<?=base_url()?>uploads/front/images/latest.jpg')">
  <div class="container">
    <div class="row">
      <div class="col-md-12 headingMain">
        <div class="become">
          <h2> BECOME An <span> Influencer </span> </h2>
          <a href="<?=base_url('signup')?>" class="btn btn-info btn1">JOIN WITH US</a>
        </div>
      </div>
    </div>
  </div>  
</section>



