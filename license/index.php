<?

// arguments start empty
$info = array(
 'date' => '',
 'email' => '',
 'licensee' => '',
 'address' => '',
 'fee' => '');

// load arguments from url into $info
foreach ($info as $key => $value) {
 if(!empty($_GET[$key])) {
 $info[$key] = $_GET[$key];
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
if(count($_GET) > 1) {
 $hashed = base64_encode(json_encode($info));
 $url = strtok($_SERVER["REQUEST_URI"],'?');
 header("Location: " . $url . "?id=" . $hashed);
 exit;
}

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
?>

<?
// start page
$page = "license";
include "../header.php"; ?>

<?

// create initial license
if(!$done && count($_GET) > 0) {
?>

 <form class="form-quote" method="get">

 <!-- <h2 class="form-quote-heading">Information</h2> -->

 <p>Please fill out the following fields to complete your FaceTracker commercial license purchase.</p>
 <input name="date" type="hidden" value="<?= "May 29, 2014" ?>">
 <input name="email" type="email" class="form-control top" placeholder="Email address" value="<?= $info['email'] ?>">
 <input name="licensee" type="text" class="form-control middle" placeholder="Licensee" value="<?= $info['licensee'] ?>">
 <textarea name="address" class="form-control middle" rows="6" placeholder="Address" value="<?= $info['address'] ?>"></textarea>
 <input name="fee" type="<?= empty($info['fee']) ? "number" : "hidden" ?>" class="form-control middle" placeholder="Fee" value="<?= $info['fee'] ?>">
 <button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>
 </form>

<?
}

// date, licensee, fee, email, address
// don't want everything to be visible to avoid the feeling that it is hacked together
// especially the fee should be hidden
// but we need them to submit address and possibly name & email also
// so:

// process
// 1. first page hashes fee
// 2. you fill out form with email & name
// 3. creates new hash which is sent to licensee
// 4. licensee fills out remaining details
// 5. remaining info is added to hash and displayed

// development
// 1. make entire page (design invoice)
// 2. make form fields
// 3. make hashing work correctly
// 4. automatically email license after it's filled out

// 0. no arguments yield license preview
// 1. ?create show form field
// 2. fill it out, hashes filled out info
// 3. makes invoice
// 4. has paypal form

?>

<h2>Commercial License<?= $done ? "" : " (Preview)" ?></h2>

<h3>Summary</h3>

<ul>
 <li>License does not expire.</li>
 <li>Can be distributed in binary or object form only.</li>
 <li>Can modify source-code but cannot distribute modifications (derivative works).</li>
</ul>

<h3>Additional terms</h3>
<ul>
 <li>License is only offered on a per-product basis. For example, the License may be applied to a single product, application, advertising campaign, or artwork. Future products require a separate license.</li>
 <li>All users of FaceTracker may submit issues and feature requests on GitHub, which the developers will respond to at their leisure. Any additional support for FaceTracker must be arranged through a separate agreement.</li>
</ul>

<h3>Terms and conditions</h3>
<ol>
<li>
 <p><strong>Preamble:</strong> This Agreement, signed on <?= empty($info['date']) ? "[Date]" : $info['date'] ?>, governs the relationship between <?= empty($info['licensee']) ? "[Licensee]" : $info['licensee'] ?>, a Business Entity, (hereinafter: Licensee) and FaceTracker, a Partnership under the laws of NY, United States whose principal place of business is 33 Flatbush, 7th Floor, Brooklyn, NY, United States (Hereinafter: Licensor). This Agreement sets the terms, rights, restrictions and obligations on using FaceTracker (hereinafter: The Software) created and owned by Licensor, as detailed herein</p>
</li>
<li>
 <p><strong>License Grant:</strong> Licensor hereby grants Licensee a Sublicensable, Non-assignable & non-transferable, Commercial, Royalty free, Including the rights to create but not distribute derivative works, Non-exclusive license, all with accordance with the terms set forth and other legal restrictions set forth in 3rd party software used while running Software.</p>
 <ol>
 <li>
 <p><strong>Limited:</strong> Licensee may use Software for the purpose of:</p>
 <ol>
 <li>Running Software on Licensee&rsquo;s Website[s] and Server[s];</li>
 <li>Allowing 3rd Parties to run Software on Licensee&rsquo;s Website[s] and Server[s];</li>
 <li>Publishing Software&rsquo;s output to Licensee and 3rd Parties;</li>
 <li>Distribute verbatim copies of Software&rsquo;s output (including compiled binaries);</li>
 <li>Modify Software to suit Licensee&rsquo;s needs and specifications.</li>
 </ol>
 </li>
 <li><b>Binary Restricted:</b> Licensee may sublicense Software as a part of a larger work containing more than Software, distributed solely in Object or Binary form under a personal, non-sublicensable, limited license. Such redistribution shall be limited to unlimited codebases.</li>
 <li>
 <p><strong>Non Assignable &amp; Non-Transferable:</strong> Licensee may not assign or transfer his rights and duties under this license.</p>
 </li>
 <li>
 <p><strong>Commercial, Royalty Free: </strong>Licensee may use Software for any purpose, including paid-services, without any royalties</p>
 </li>
 <li>
 <p><strong>Including the Right to Create Derivative Works: </strong>Licensee may create derivative works based on Software, including amending Software&rsquo;s source code, modifying it, integrating it into a larger work or removing portions of Software, as long as no distribution of the derivative works is made</p>
 </li>
 </ol>
</li>
<li>
 <strong>Term &amp; Termination:</strong> The Term of this license shall be until terminated. Licensor may terminate this Agreement, including Licensee&rsquo;s license in the case where Licensee : 
 <ol>
 <li>
 <p>became insolvent or otherwise entered into any liquidation process; or</p>
 </li>
 <li>
 <p>exported The Software to any jurisdiction where licensor may not enforce his rights under this agreements in; or</p>
 </li>
 <li>
 <p>Licensee was in breach of any of this license's terms and conditions and such breach was not cured, immediately upon notification; or</p>
 </li>
 <li>
 <p>Licensee in breach of any of the terms of clause 2 to this license; or</p>
 </li>
 <li>
 <p>Licensee otherwise entered into any arrangement which caused Licensor to be unable to enforce his rights under this License.</p>
 </li>
 </ol>
</li>
<li><strong>Payment:</strong> In consideration of the License granted under clause 2, Licensee shall pay Licensor a fee of <?= empty($info['fee']) ? "[Fee]" : $info['fee'] ?> via PayPal. Failure to perform payment shall construe as material breach of this Agreement. </li>
<li>
 <p><strong>Upgrades, Updates and Fixes:</strong> Licensor may provide Licensee, from time to time, with Upgrades, Updates or Fixes, as detailed herein and according to his sole discretion. Licensee hereby warrants to keep The Software up-to-date and install all relevant updates and fixes, and may, at his sole discretion, purchase upgrades, according to the rates set by Licensor. Licensor shall provide any update or Fix free of charge; however, nothing in this Agreement shall require Licensor to provide Updates or Fixes.</p>
 <ol>
 <li>
 <p><strong>Upgrades:</strong> for the purpose of this license, an Upgrade shall be a material amendment in The Software, which contains new features and or major performance improvements and shall be marked as a new version number. For example, should Licensee purchase The Software under version 1.X.X, an upgrade shall commence under number 2.0.0.</p>
 </li>
 <li>
 <p><strong>Updates: </strong> for the purpose of this license, an update shall be a minor amendment in The Software, which may contain new features or minor improvements and shall be marked as a new sub-version number. For example, should Licensee purchase The Software under version 1.1.X, an upgrade shall commence under number 1.2.0.</p>
 </li>
 <li>
 <p><strong>Fix:</strong> for the purpose of this license, a fix shall be a minor amendment in The Software, intended to remove bugs or alter minor features which impair the The Software's functionality. A fix shall be marked as a new sub-sub-version number. For example, should Licensee purchase Software under version 1.1.1, an upgrade shall commence under number 1.1.2.</p>
 </li>
 </ol>
</li>
<li>
 <p><strong>Support:</strong> Software is provided under an AS-IS basis and without any support, updates or maintenance. Nothing in this Agreement shall require Licensor to provide Licensee with support or fixes to any bug, failure, mis-performance or other defect in The Software.</p>
 <ol>
 <li>
 <p><strong>Bug Notification: </strong> Licensee may provide Licensor of details regarding any bug, defect or failure in The Software promptly and with no delay from such event; Licensee shall comply with Licensor's request for information regarding bugs, defects or failures and furnish him with information, screenshots and try to reproduce such bugs, defects or failures.</p>
 </li>
 <li>
 <p><strong>Feature Request: </strong> Licensee may request additional features in Software, provided, however, that (i) Licensee shall waive any claim or right in such feature should feature be developed by Licensor; (ii) Licensee shall be prohibited from developing the feature, or disclose such feature request, or feature, to any 3rd party directly competing with Licensor or any 3rd party which may be, following the development of such feature, in direct competition with Licensor; (iii) Licensee warrants that feature does not infringe any 3rd party patent, trademark, trade-secret or any other intellectual property right; and (iv) Licensee developed, envisioned or created the feature solely by himself.</p>
 </li>
 </ol>
</li>
<li>
 <p><strong>Liability: </strong>&nbsp;To the extent permitted under Law, The Software is provided under an AS-IS basis. Licensor shall never, and without any limit, be liable for any damage, cost, expense or any other payment incurred by Licensee as a result of Software&rsquo;s actions, failure, bugs and/or any other interaction between The Software &nbsp;and Licensee&rsquo;s end-equipment, computers, other software or any 3rd party, end-equipment, computer or services. &nbsp;Moreover, Licensor shall never be liable for any defect in source code written by Licensee when relying on The Software or using The Software&rsquo;s source code.</p>
</li>
<li>
 <p><strong>Warranty: &nbsp;</strong></p>
 <ol>
 <li>
 <p><strong>Intellectual Property: </strong>Licensor hereby warrants that The Software does not violate or infringe any 3rd party claims in regards to intellectual property, patents and/or trademarks and that to the best of its knowledge no legal action has been taken against it for any infringement or violation of any 3rd party intellectual property rights.</p>
 </li>
 <li>
 <p><strong>No-Warranty:</strong> The Software is provided without any warranty; Licensor hereby disclaims any warranty that The Software shall be error free, without defects or code which may cause damage to Licensee&rsquo;s computers or to Licensee, and that Software shall be functional. Licensee shall be solely liable to any damage, defect or loss incurred as a result of operating software and undertake the risks contained in running The Software on License&rsquo;s Server[s] and Website[s].</p>
 </li>
 <li>
 <p><strong>Prior Inspection: </strong> Licensee hereby states that he inspected The Software thoroughly and found it satisfactory and adequate to his needs, that it does not interfere with his regular operation and that it does meet the standards and scope of his computer systems and architecture. Licensee found that The Software interacts with his development, website and server environment and that it does not infringe any of End User License Agreement of any software Licensee may use in performing his services. Licensee hereby waives any claims regarding The Software's incompatibility, performance, results and features, and warrants that he inspected the The Software.</p>
 </li>
 </ol>
</li>
<li>
 <p><strong>No Refunds:</strong> Licensee warrants that he inspected The Software according to clause 7(c) and that it is adequate to his needs. Accordingly, as The Software is intangible goods, Licensee shall not be, ever, entitled to any refund, rebate, compensation or restitution for any reason whatsoever, even if The Software contains material flaws.</p>
</li>
<li>
 <p><strong>Indemnification:</strong> Licensee hereby warrants to hold Licensor harmless and indemnify Licensor for any lawsuit brought against it in regards to Licensee&rsquo;s use of The Software in means that violate, breach or otherwise circumvent this license, Licensor's intellectual property rights or Licensor's title in The Software. Licensor shall promptly notify Licensee in case of such legal action and request Licensee&rsquo;s consent prior to any settlement in relation to such lawsuit or claim.</p>
</li>
<li>
 <p><strong>Governing Law, Jurisdiction: </strong>Licensee hereby agrees not to initiate class-action lawsuits against Licensor in relation to this license and to compensate Licensor for any legal fees, cost or attorney fees should any claim brought by Licensee against Licensor be denied, in part or in full.</p>
</li>
</ol>


<? include "../footer.php"; ?>
