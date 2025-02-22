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

   .error1
   {
      position: absolute;
      top: -9px;
      padding: 14px;
      width: 100%;
      color: #f00;
      border-radius: 4px;
      line-height: 20px;
   }   

   .login .login_box
   {
      position: relative;
   }
</style>

<section class="userProfile login secPad50">
    <div class="container">
      <div class="row">
         <div class="box-sha1">
          <div class="col-lg-4 hidden-sm hidden-xs hidden-md" style="padding: 0">
            <div class="login-img">
                <h1> <a href="index.php"><img src="<?=base_url('uploads/front/')?>images/loginlogo.png" width="150" class="img-responsive" alt=""></a> </h1>     
            </div>      
           </div>
           <div class="col-lg-8 col-md-12 col-xs-12">
            <div class="login_box">
              
              <div class="error1">
                <?php echo validation_errors('<div class="error">', '</div>'); ?>
                
                  <p class="error"><?=$usrmsg;?></p>
                  <div class="alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
              </div>

              <div class="log-left login_text">
              <?php 
                $attributes = array('class' => 'login100-form validate-form flex-sb flex-w', 'id' => 'myform');
                echo form_open('authentication/login', $attributes); ?>
                    <h2> Login in whereyou.ng </h2>
                    <h3> We wil need... </h3>
                    <div class="center-log">
                      <div class="input-container">
                        <i class="fa icon"><img src="<?=base_url('uploads/front/')?>images/message.png" alt=""></i>
                        <input class="input-field" type="text" placeholder="Your Email" name="useremail" required="">
                      </div>
                      <div class="input-container">
                        <i class="fa icon"><img src="<?=base_url('uploads/front/')?>images/lock.png" alt=""></i>
                        <input class="input-field" type="password" placeholder="Password" name="password" required="">
                      </div>
                      <button type="submit" class="btn btn-info">Login</button>
                      <p> Not have account? <a href="<?=base_url('signup')?>">Signup</a> </p>
                    </div>
                <?= form_close(); ?>
              </div>
              <span class="line-bor"></span>
              <div class="log-right login_text">
                
                 <h3> Don’t have account? <a href="<?=base_url('signup')?>">Sign Up!</a> </h3>
                  
                  <a href="#" class="fblogin">
                    <i class="fa fa-facebook" id="fbname"></i>
                    <span id="fbtext">Sign In with Facebook</span>
                  </a>

                  <a href="#" class="fblogin g-plus">
                    <i class="fa fa-google-plus" id="fbname"></i>
                    <span id="fbtext">Sign In with Facebook</span>
                  </a>
              </div>

            </div>
            <p class="sing-p text-center"> By signing up, you agree to goingmyway’s <a href="#"> Terms of Service </a> and <a href="#"> Privacy Policy</a>. </p>
          </div>
        </div>
      </div>
   </div>   
</section>



<script>
   function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
       }
   }
   $("#imageUpload").change(function() {
       readURL(this);
   });
</script>