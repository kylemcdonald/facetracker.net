<?

// arguments start empty
$info = array(
 'date' => '',
 'email' => '',
 'licensee' => '',
 'address' => '',
 'fee' => 0);

$ignore = array(
  'sendmail');

// load arguments from url into $info
$redirect = false;
foreach ($info as $key => $value) {
 if(!empty($_GET[$key])) {
   $info[$key] = $_GET[$key];
   $redirect = true;
 }
}

// load extra arguments into $pass
$pass = array();
foreach ($ignore as $item) {
  if(!empty($_GET[$item])) {
    $pass[$item] = $_GET[$item];
  }
}

// load arguments from hashed data
if(!empty($_GET['id'])) {
 $cur = json_decode(base64_decode($_GET['id']), true);
 foreach ($info as $key => $value) {
   if(!empty($cur[$key])) {
     $info[$key] = $cur[$key];
   }
 }
}

// hash extra arguments and redirect
$hashed = base64_encode(json_encode($info));
$baseUrl = strtok($_SERVER["REQUEST_URI"],'?');
if($redirect) {
  $url = $baseUrl . "?id=" . $hashed;
  if(!empty($pass)) {
    $url .= "&" . http_build_query($pass);
  }
  header("Location: " . $url);
  exit;
}
$doneUrl = "http://facetracker.net";
$doneUrl .= $baseUrl . "?id=" . $hashed;

// check if the info is complete
$done = true;
foreach ($info as $key => $value) {
 if(empty($value)) {
   $done = false;
 }
}
// or create is defined
if(isset($_GET['create'])) {
 $done = false;
}

// send email
if($done && $pass['sendmail']) {
  $fromName = "FaceTracker";
  $fromEmail = "info@facetracker.net";

  $to = $info['email'];
  $subject = 'FaceTracker commercial license';
  $headers = "From: \"$fromName\" <$fromEmail>\r\n" .
    "Reply-To: \"$fromName\" <$fromEmail>\r\n" .
    "CC: $fromEmail\r\n" .
    "MIME-Version: 1.0\r\n" .
    "Content-Type: text/html; charset=utf-8\r\n" .
    "X-Mailer: PHP/" . phpversion();

  ob_start();
  include "invoice.php";
  include "license.php";
  $message .= ob_get_clean();

  mail($to, $subject, $message, $headers);
}

?>

<?
// start page
$page = "license";
include "../header.php"; ?>

<?

// create initial license
$preview = empty($_GET);
if(!$preview) {
  if(!$done) {
?>

<form class="form-quote" method="get">

<p>Please fill out the following fields to complete your FaceTracker commercial license purchase.</p>
<input name="sendmail" type="hidden" value="true">
<input name="date" type="hidden" value="<?= date("F j, Y") ?>">
<input name="email" type="email" class="form-control top" placeholder="Email address" value="<?= $info['email'] ?>">
<input name="licensee" type="text" class="form-control middle" placeholder="Licensee" value="<?= $info['licensee'] ?>">
<textarea name="address" class="form-control middle" rows="6" placeholder="Address" value="<?= $info['address'] ?>"></textarea>
<input name="fee" type="<?= empty($info['fee']) ? "number" : "hidden" ?>" class="form-control middle" placeholder="Fee" value="<?= $info['fee'] ?>">
<button class="btn btn-lg btn-primary btn-block" type="submit">Complete</button>
</form>

<hr/>

<?
  }
?>

<? include "invoice.php"; ?>

<?
}
?>

<? include "license.php"; ?>

<? include "../footer.php"; ?>
