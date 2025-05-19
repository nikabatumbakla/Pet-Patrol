<?php 
session_start();
ob_start(); // Start output buffering

require 'db.php';
require 'vendor/autoload.php'; // Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['admin_name'])) {
    die("Unauthorized access");
}

$status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : 'all';
$whereClause = ($status !== 'all') ? "WHERE s.status_name = '$status'" : "";

$sql = "
    SELECT 
        aa.application_id,
        u.username,
        u.email,
        p.name AS pet_name,
        c.category_name,
        aa.location,
        s.status_name,
        aa.submission_date
    FROM adoptionapplications aa
    JOIN users u ON aa.user_id = u.user_id
    JOIN pets p ON aa.pet_id = p.pet_id
    JOIN pet_categories c ON p.category_id = c.category_id
    JOIN application_statuses s ON aa.status_id = s.status_id
    $whereClause
    ORDER BY s.status_name, aa.submission_date DESC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Group data by status
$data = ['Pending' => [], 'Approved' => [], 'Rejected' => []];
while ($row = mysqli_fetch_assoc($result)) {
    $statusKey = ucfirst(trim($row['status_name']));
    if (isset($data[$statusKey])) {
        $data[$statusKey][] = $row;
    }
}

// Load header image
$imagePath = 'images/header.png'; // Adjust path as needed
$base64 = '';
if (file_exists($imagePath)) {
    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
    $imageData = file_get_contents($imagePath);
    $base64 = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
}

$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    h2 { text-align: center; margin-top: 10px; font-size: 18px; }
    .status-title { background:rgb(228, 160, 14); color: white; padding: 8px; margin-top: 25px; font-size: 14px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px 6px; text-align: left; font-size: 11px; }
    th { background-color: #f8f8f8; }
    .header-img { width: 100%; height: auto; margin-bottom: 10px; }
    .no-data { font-style: italic; color: #777; padding: 10px; }
</style>
';

if ($base64 !== '') {
    $html .= '<div style="text-align:center; margin-bottom: 10px;">
        <img src="' . $base64 . '" class="header-img" alt="Header Image" />
    </div>';
    
}

$html .= '<h2>Adoption Applications Report (' . ucfirst($status) . ')</h2>';



foreach ($data as $group => $rows) {
    if (!empty($rows)) {
        $html .= '<div class="status-title">' . $group . ' Applications</div>';
        $html .= '<table>
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Category</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($rows as $row) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($row['pet_name']) . '</td>
                        <td>' . htmlspecialchars($row['category_name']) . '</td>
                        <td>' . htmlspecialchars($row['username']) . '</td>
                        <td>' . htmlspecialchars($row['email']) . '</td>
                        <td>' . htmlspecialchars($row['location']) . '</td>
                        <td>' . htmlspecialchars($row['status_name']) . '</td>
                        <td>' . htmlspecialchars($row['submission_date']) . '</td>
                    </tr>';
        }
        $html .= '</tbody></table>';
    } else {
        $html .= '<p class="no-data">No ' . $group . ' applications found.</p>';
    }
}

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); // Set to portrait
$dompdf->render();

ob_end_clean(); // Clear buffer
$dompdf->stream("adoption_applications_" . $status . ".pdf", ["Attachment" => true]);
exit;
?>
