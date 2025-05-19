<?php 
require 'vendor/autoload.php';
require 'db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Helper function to build table HTML
function buildPetTable($title, $status, $conn)
{
    $query = "SELECT name, species, description FROM pets WHERE pet_status = '$status' ORDER BY name";
    $result = mysqli_query($conn, $query);

    $html = "<h3 style='margin-top: 30px;'>$title</h3>";
    $html .= '<table border="1" cellspacing="0" cellpadding="8" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Species</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($row['name']) . '</td>
                        <td>' . htmlspecialchars($row['species']) . '</td>
                        <td>' . htmlspecialchars($row['description']) . '</td>
                      </tr>';
        }
    } else {
        $html .= '<tr><td colspan="3" style="text-align:center;">No pets found.</td></tr>';
    }

    $html .= '</tbody></table><br>';
    return $html;
}

// Load header image if exists
$imagePath = 'images/header.png';
$base64 = '';
if (file_exists($imagePath)) {
    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
    $imageData = file_get_contents($imagePath);
    $base64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
}

// Begin HTML
$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    h2 { text-align: center; font-size: 20px; margin-bottom: 10px; }
    h3 { font-size: 14px; color: #444; margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #aaa; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .header-img { width: 100%; height: auto; margin-bottom: 10px; }
</style>
';

if ($base64 !== '') {
    $html .= '<div style="text-align:center; margin-bottom: 10px;">
        <img src="' . $base64 . '" class="header-img" alt="Header Image" />
    </div>';
}

$html .= '<h2>Pet Report</h2>';
$html .= buildPetTable("Available Pets", "available", $conn);
$html .= buildPetTable("Adopted Pets", "adopted", $conn);
$html .= buildPetTable("Rejected Pets", "rejected", $conn); // NEW SECTION

// Generate PDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("pet_report_" . date("Ymd") . ".pdf", ["Attachment" => false]);
exit;
?>
