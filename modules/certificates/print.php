<?php
require_once "../../config/db.php";
require_once "../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: /iBarangayLink/login.php");
    exit();
}

$id = $_GET['id'];

// get certificate and resident
$result = mysqli_query($conn, "
    SELECT c.*, r.firstname, r.lastname, r.middlename, r.address
    FROM certificates c
    LEFT JOIN residents r ON c.resident_id = r.id
    WHERE c.id = $id
    LIMIT 1
");

$cert = mysqli_fetch_assoc($result);

if (!$cert) {
    die("Certificate not found.");
}

// you can hardcode barangay details here
$barangay  = "Barangay Sample";
$municipal = "City of Sample";
$province  = "Province of Sample";

$fullname  = $cert['firstname'] . ' ' . $cert['middlename'] . ' ' . $cert['lastname'];
$address   = $cert['address'];
$purpose   = $cert['purpose'];
$today     = date("F d, Y");
$cert_type = $cert['cert_type'];

// certificate title per type
$title = "BARANGAY CERTIFICATE";
if ($cert_type == "Indigency") {
    $title = "CERTIFICATE OF INDIGENCY";
} elseif ($cert_type == "Barangay Clearance") {
    $title = "BARANGAY CLEARANCE";
} elseif ($cert_type == "Residency") {
    $title = "CERTIFICATE OF RESIDENCY";
}

// build HTML template
$html = '
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; }
        .header { text-align: center; }
        .title { text-align: center; margin-top: 30px; font-size: 20pt; font-weight: bold; text-decoration: underline; }
        .content { margin: 40px 50px; font-size: 12pt; text-align: justify; line-height: 1.6; }
        .footer { margin-top: 60px; text-align: right; margin-right: 70px; }
    </style>
</head>
<body>
    <div class="header">
        <div>Republic of the Philippines</div>
        <div><b>'.$province.'</b></div>
        <div><b>'.$municipal.'</b></div>
        <div><b>'.$barangay.'</b></div>
        <hr>
    </div>

    <div class="title">'.$title.'</div>

    <div class="content">
        <p>
            This is to certify that <b>'.$fullname.'</b>, a resident of <b>'.$address.'</b>, 
            is known to be a bona fide resident of this barangay.
        </p>
';

if (!empty($purpose)) {
    $html .= '
        <p>
            This certification is being issued upon the request of the above-named person 
            for the purpose of <b>'.$purpose.'</b>.
        </p>
    ';
}

$html .= '
        <p>
            Issued this '.$today.' at Barangay '.$barangay.', '.$municipal.', '.$province.', Philippines.
        </p>
    </div>

    <div class="footer">
        <b>Barangay Captain Name</b><br>
        Barangay Captain
    </div>
</body>
</html>
';

// dompdf setup
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

add_log(
    $_SESSION['userid'],
    "Printed Certificate",
    "Certificate ID $id was printed"
);

// stream to browser
$dompdf->stream("certificate_".$id.".pdf", ["Attachment" => false]);
exit();
