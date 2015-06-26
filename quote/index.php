<?php
// OPTIONS - PLEASE CONFIGURE THESE BEFORE USE!

$fromName = "FaceTracker";
$fromEmail = "info@facetracker.net"; // the email address you wish to receive these mails through
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "name,email,description,category"; // names of the fields you'd like to be required as a minimum, separate each field with a comma

// DO NOT EDIT BELOW HERE
function sendQuote($subject, $email, $name, $category) {
  include 'fees.php';
  global $fromName;
  global $fromEmail;

  if(!array_key_exists($category, $formatCategory)) {
    $category = "Other";
  }

  $message = file_get_contents('email.txt', true);
  $message = str_replace('%category%', $formatCategory[$category], $message);
  $message = str_replace('%fee%', $formatFee[$category], $message);

  date_default_timezone_set('America/New_York');
  $info = array(
    'email' => $email,
    'licensee' => $name,
    'date' => date("F j, Y"),
    'fee' => $formatFee[$category]);
  $hash = base64_encode(json_encode($info));
  $url = 'http://facetracker.net/license/?sendmail=true&id='.$hash;
  $message = str_replace('%url%', $url, $message);

  $headers = "From: \"$fromName\" <$fromEmail>\r\n";
  $headers.= "Reply-To: \"$fromName\" <$fromEmail>\r\n";
  $headers.= "Cc: \"$fromName\" <$fromEmail>\r\n";
  $headers.= "X-Mailer: PHP/".phpversion()."\r\n";
  $headers.= "MIME-Version: 1.0" . "\r\n";
  $headers.= "Content-type: text/plain; charset=utf-8\r\n";

  return mail($email,$subject,$message,$headers);
}

$error_msg = array();
$result = null;

$requiredFields = explode(",", $requiredFields);

function clean($data) {
  $data = trim(stripslashes(strip_tags($data)));
  return $data;
}
function isBot() {
  $bots = array("Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "PycURL", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "Teoma", "alexa", "froogle", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz");

  foreach ($bots as $bot)
    if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
      return true;

  if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == " ")
    return true;
  
  return false;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isBot() !== false)
    $error_msg[] = "No bots please! UA reported as: ".$_SERVER['HTTP_USER_AGENT'];
    
  // lets check a few things - not enough to trigger an error on their own, but worth assigning a spam score.. 
  // score quickly adds up therefore allowing genuine users with 'accidental' score through but cutting out real spam :)
  $points = (int)0;
  
  $badwords = array("adult", "beastial", "bestial", "blowjob", "clit", "cum", "cunilingus", "cunillingus", "cunnilingus", "cunt", "ejaculate", "fag", "felatio", "fellatio", "fuck", "fuk", "fuks", "gangbang", "gangbanged", "gangbangs", "hotsex", "hardcode", "jism", "jiz", "orgasim", "orgasims", "orgasm", "orgasms", "phonesex", "phuk", "phuq", "pussies", "pussy", "spunk", "xxx", "viagra", "phentermine", "tramadol", "adipex", "advai", "alprazolam", "ambien", "ambian", "amoxicillin", "antivert", "blackjack", "backgammon", "texas", "holdem", "poker", "carisoprodol", "ciara", "ciprofloxacin", "debt", "dating", "porn", "link=", "voyeur", "content-type", "bcc:", "cc:", "document.cookie", "onclick", "onload", "javascript");

  foreach ($badwords as $word)
    if (
      strpos(strtolower($_POST['description']), $word) !== false || 
      strpos(strtolower($_POST['name']), $word) !== false
    )
      $points += 2;
  
  if (strpos($_POST['description'], "http://") !== false || strpos($_POST['description'], "www.") !== false)
    $points += 2;
  if (isset($_POST['nojs']))
    $points += 1;
  if (preg_match("/(<.*>)/i", $_POST['description']))
    $points += 2;
  if (strlen($_POST['name']) < 3)
    $points += 1;
  if (strlen($_POST['description']) < 15 || strlen($_POST['description'] > 1500))
    $points += 2;
  if (preg_match("/[bcdfghjklmnpqrstvwxyz]{7,}/i", $_POST['description']))
    $points += 1;
  // end score assignments

  foreach($requiredFields as $field) {
    trim($_POST[$field]);
    
    if (!isset($_POST[$field]) || empty($_POST[$field]) && array_pop($error_msg) != "Please fill in all the required fields and submit again.\r\n")
      $error_msg[] = "Please fill in all the required fields and submit again.";
  }

  if (!empty($_POST['name']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['name'])))
    $error_msg[] = "The name field must not contain special characters.\r\n";
  if (!empty($_POST['email']) && !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($_POST['email'])))
    $error_msg[] = "That is not a valid e-mail address.\r\n";
  if (!empty($_POST['url']) && !preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i', $_POST['url']))
    $error_msg[] = "Invalid website url.\r\n";
  
  if ($error_msg == NULL && $points <= $maxPoints) {
    $subject = "Quote request from ".$_POST['name']." (".$_POST['category'].")";
    
    $message = "";
    foreach ($_POST as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $subval) {
          $message .= ucwords($key) . ": " . clean($subval) . "\r\n";
        }
      } else {
        $message .= ucwords($key) . ": " . clean($val) . "\r\n";
      }
    }
    $message .= "\r\n";
    $message .= 'IP: '.$_SERVER['REMOTE_ADDR']."\r\n";
    $message .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\r\n";
    $message .= 'Points: '.$points;

    $headers = "From: \"$fromName\" <$fromEmail>\r\n";
    $headers.= "Reply-To: \"$fromName\" <$fromEmail>\r\n";

    if (mail($fromEmail,$subject,$message,$headers)) {
      $result = 'Thank you for your request, we will contact you with a quote.';
      $disable = true;
      $name = $_POST['name'];
      $email = $_POST['email'];
      $category = $_POST['category'];
      sendQuote($subject, $email, $name, $category);
    } else {
      $error_msg[] = 'Your request could not be made at this time. ['.$points.']';
    }
  } else {
    if (empty($error_msg))
      $error_msg[] = 'Your request looks too much like spam, and could not be made at this time. ['.$points.']';
  }
}
function get_data($var) {
  if (isset($_POST[$var]))
    echo htmlspecialchars($_POST[$var]);
}
?>

<!--
  Free PHP Mail Form v2.4.3 - Secure single-page PHP mail form for your website
  Copyright (c) Jem Turner 2007, 2008, 2010, 2011, 2012
  http://jemsmailform.com/

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  To read the GNU General Public License, see http://www.gnu.org/licenses/.
-->

<?php
$page = "quote";
include "../header.php"; ?>

      <form class="form-quote" method="post">
        
        <noscript>
            <p><input type="hidden" name="nojs" id="nojs" /></p>
        </noscript>

        <h2 class="form-quote-heading">Request a quote</h2>

        <?php
        if (!empty($error_msg)) {
          echo '<p>Error: '. implode("<br />", $error_msg) . "</p>";
        }
        if ($result != NULL) {
          echo '<p>'. $result . "</p>";
        } else {
        ?>
        <p>Please tell us about your project, and we'll get back to you immediately with a quote for a commercial license of FaceTracker.</p>
        <p>You do not need a license for non-commercial work or for testing, you can <a href="https://github.com/kylemcdonald/FaceTracker/archive/master.zip">download FaceTracker now</a>.</p>
        <input name="email" type="email" class="form-control top" placeholder="Email address" autofocus>
        <input name="name" type="text" class="form-control middle" placeholder="Name">
        <textarea name="description" class="form-control middle" rows="6" placeholder="Project description"></textarea>
        <h4>Project category:</h4>
        <select name="category" class="form-control">
          <option>Advertising campaign</option>
          <option>Commercial software/app</option>
          <option>Independent artwork</option>
          <option>Other</option>
        </select>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>
        <?php
        }
        ?>
      </form>

<?php include "../footer.php"; ?>
