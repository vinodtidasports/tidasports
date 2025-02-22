<?php include('inc/header.php'); ?>  

<?php 
  $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
?>
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

<section class="userProfile login signUp secPad50">
    <div class="container">
      <div class="row">
         <div class="box-sha1">
          <div class="col-lg-4 hidden-sm hidden-xs hidden-md" style="padding: 0">
            <div class="login-img">
                <h1> <a href="index.php"><img src="images/loginlogo.png" width="150" class="img-responsive" alt=""></a> </h1>     
            </div>      
           </div>
           <div class="col-lg-8 col-md-12 col-xs-12">
            <div class="login_box">
              <div class="log-left login_text">
                <form method="post">
                    <h2> Signup in whereyou.ng </h2>
                    <h3> We wil need... </h3>
                    <div class="center-log">
                      <div class="input-container">
                        <i class="fa icon"><img src="images/message.png" alt=""></i>
                        <input class="input-field" type="text" placeholder="Your Email" name="email">
                      </div>
                      <div class="input-container">
                        <i class="fa icon"><img src="images/lock.png" alt=""></i>
                        <input class="input-field" type="text" placeholder="Your Name" name="name">
                      </div>
                      <div class="input-container">
                        <i class="fa icon"><img src="images/lock.png" alt=""></i>
                        <input class="input-field" type="password" placeholder="Password" name="password">
                      </div>
                      <div class="input-container">
                        <i class="fa icon"><img src="images/lock.png" alt=""></i>
                        <input class="input-field" type="password" placeholder="Confirm Password" name="confirmpwd">
                      </div>
                      <div class="input-container">
                        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                      </div>

                      <div class="checkbox text-left">
                        <label><input type="checkbox"> Signup as Influencer </label>
                      </div>
                      <button type="submit" class="btn btn-info">Sign Up</button>
                      <p> Already have an  account? <a href="login.php">SignIn</a> </p>
                    </div>
                </form>
              </div>
              <span class="line-bor"></span>
              <div class="log-right login_text">
                
                 <h3> Already have an  account? <a href="login.php">SignIn!</a> </h3>
                  
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
            <p class="sing-p text-center"> By signing up, you agree to goingmyway’s <a href="terms.php"> Terms of Service </a> and <a href="privacy.php"> Privacy Policy</a>. </p>
          </div>
        </div>
      </div>
   </div>   
</section>

<?php include('inc/footer.php'); ?>

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