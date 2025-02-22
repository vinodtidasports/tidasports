<!DOCTYPE html>
<html lang="en">
   <head>
      <title><?= get_option('site_name'); ?></title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" href="<?=BASE_URL('asset/css/')?>animate.min.css">
      <link rel="stylesheet" href="<?=BASE_URL('asset/css/')?>owl.css">
      <link rel="stylesheet" href="<?=BASE_URL('asset/css/')?>style.css">
      <link rel="stylesheet" href="<?=BASE_URL('asset/css/')?>responsive.css">
   </head>
   <body style="background-color: #ccc">
    
    <style type="text/css">
      .abc {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      body
      {
         position: relative;
      }
      body::before
      {
          position: absolute;
          content: "";
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(30, 36, 68, 0.9);
      }
      .loginbtn {
        padding: 20px 60px;
        background: #23aadf;
        border-radius: 4px;
        margin-top: -60px;
        font-size: 16px;
        border-radius: 35px;
        margin-top: 5px;
      }
    </style>
<div class="container">  
  <div class="col-md-12">
    <div class="abc">
      <div class="img">
        <center><img src="<?=base_url('uploads/')?>logo_white.png" class="img-responsive" alt="Logo" width="350px" /></center>
        <center><a href="<?=base_url('auth/backend/auth/login')?>" class="loginbtn btn btn-info">Click here to login</a></center>
      </div>
    </div>
  </div>
</div>
   </body>
</html>