
<style type="text/css">
.invoice td:first-child {
  font-weight: bold;
  text-align: right;
}
.invoice {
  border: solid 1px LightGray;
  padding: 0 2em;
  border-radius: 1em;
  box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
}
h4 {
  margin: 0;
}
</style>

<div class="invoice">

<h2>Invoice</h2>

<p>This is a request to be paid for the product as described below. Payment may be made via PayPal. A <a href="<?= $doneUrl ?>">link to this page</a> will be emailed to you for your records.</p>

<table class="table">
<tbody>
  <tr><td>From</td><td><h4><?= $info['licensee'] ?></h4><p><?= nl2br($info['address']) ?></p></td></tr>
  <tr><td>To</td><td><h4>FaceTracker</h4><p>33 Flatbush Avenue, 7th Floor<br/>Brooklyn, NY 11217<br/>United States of America</p></td></tr>
  <tr><td>For</td><td>FaceTracker commercial license.</td></tr>
  <tr><td>Submitted On</td><td><?= $info['date'] ?></td></tr>
  <tr><td>Terms</td><td>30 days</td></tr>
  <tr><td>In the amount of</td><td><p>$<?= number_format($info['fee'], 2) ?> USD</p>
<?
if($done && $pass['sendmail']) {
?>

<form name="_xclick" action="https://www.paypal.com/us/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="billing@facetracker.net">
<input type="hidden" name="item_name" value="FaceTracker commercial license">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="amount" value="<?= number_format($info['fee'], 2) ?>">
<input type="hidden" name="return" value="<?= $doneUrl ?>">
<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>

<?
}
?>

  </td></tr>
</tbody>
</table>
</div>

<hr/>