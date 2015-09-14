<html>
  <head>
    <title>ProjectUploader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:url"           content="localhost/practUpload/<?php $rows['category']?>/path/<?php $rows['name']?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="ProjectUploader" />
    <meta property="og:description"   content="Your description" />
    <meta property="og:image"         content="localhost/practUpload/<?php $rows['category']?>/path/<?php $rows['name']?>" />
    <link rel="stylesheet" type="text/css" href="assets/CSS/reset.css">
    <link rel="stylesheet" type="text/css" href="assets/CSS/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/CSS/main3.css">
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
  </head>
  <body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>



  <div id='header' class="navbar navbar-inverse">
    <div class="container">
      <button class="navbar-toggle pull-left" data-toggle = "collapse" data-target =
              ".navbar-brand">
        <a href="index.php"><span id = 'first'>I</span><span id ='last'>U</span></a>

      </button>
      <div class="collapse navbar-collapse navbar-brand pull-left hide">
        <a href="index.php" class="navbar-brand navLink"><span id='first'>Image</span><span id='last'>Uploader</span></a>
      </div>
      <button id='menu-button' class="navbar-toggle" data-toggle = "collapse" data-target =
      ".navHeaderCollapse">
      <span class = "icon-bar"></span>
      <span class = "icon-bar"></span>
      <span class = "icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse navHeaderCollapse ">
        <ul class="nav navbar-nav navbar-right">
          <li ><a class="navLink" href="index.php">Home</a> </li>
          <li class="dropdown">

            <a href="#" id='drop' class="dropdown-toggle navLink" data-toggle = "dropdown">Gallery<b class =
            "caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="category1.php">Category1</a></li>
                <li><a href="category2.php">Category2</a></li>
                <li><a href="category3.php">Category3</a></li>
                <li><a href="category4.php">Category4</a></li>
              </ul>

          </li>
          <li><a class='navLink' href="login.php">Sign In</a> </li>

        </ul>
      </div>
    </div>

  </div>

<div id="top"></div>
