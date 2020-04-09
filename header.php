<?
date_default_timezone_set("America/New_York");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FaceTracker is a C++ library for real-time non-rigid face tracking using OpenCV 2. Available under the MIT license.">
    <meta name="author" content="Jason Saragih">

    <title>FaceTracker</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="http://facetracker.net/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://facetracker.net/js/html5shiv.js"></script>
      <script src="http://facetracker.net/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <div class="header">
<?
if(!(isset($hideNavigation))) {
?>
        <ul class="nav nav-pills pull-right">
          <li <?php if(strcmp($page, "main") == 0) {echo 'class="active"';}?>><a href="http://facetracker.net/">Home</a></li>
          <!--<li <?php if(strcmp($page, "gallery") == 0) {echo 'class="active"';}?>><a href="http://facetracker.net/gallery/">Gallery</a></li>-->
          <!--<li <?php if(strcmp($page, "quote") == 0) {echo 'class="active"';}?>><a href="http://facetracker.net/quote/">Request a Quote</a></li>-->
        </ul>
<?
}
?>
        <h3 class="text-muted">FaceTracker</h3>
      </div>