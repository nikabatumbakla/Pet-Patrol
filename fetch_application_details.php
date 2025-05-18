<?php
include "db.php";

if (isset($_GET['application_id'])) {
    $application_id = $_GET['application_id'];

    $sql = "
    SELECT 
        a.application_id,
        a.living_conditions,
        a.pet_experience,
        a.submission_date AS created_at,
        a.location,
        a.home_type,
        a.has_children,
        a.hours_alone,
        a.has_other_pets,
        a.willing_vet_care,

        p.pet_id,
        p.name AS pet_name,
        p.species,
        p.health_condition,
        p.description,
        p.image_path AS pet_photo,
        p.vaccination_history,
        p.behavior_notes,
        p.adoption_requirements,

        u.user_id,
        u.fullname AS user_fullname,
        u.email AS user_email,
        u.phone_num AS user_phone,

        s.status_name AS application_status,

        ap.appointment_date,
        ap.status AS appointment_status

    FROM adoptionapplications a
    JOIN pets p ON a.pet_id = p.pet_id
    JOIN users u ON a.user_id = u.user_id
    JOIN application_statuses s ON a.status_id = s.status_id
    LEFT JOIN appointments ap ON a.application_id = ap.application_id
    WHERE a.application_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $application_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($app = mysqli_fetch_assoc($result)):
        ?>

        <style>
            table.app-details {
                border-collapse: collapse;
                width: 100%;
                margin: 20px 0;
                font-family: Arial, sans-serif;
            }

            table.app-details th,
            table.app-details td {
                border: 1px solid #ccc;
                padding: 8px 12px;
                vertical-align: top;
                text-align: left;
            }

            table.app-details th {
                background-color: #f9f9f9;
                width: 200px;
            }

            h3,
            h4 {
                font-family: Arial, sans-serif;
                color: #333;
                margin-top: 30px;
            }

            img.pet-photo {
                max-width: 250px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
        </style>

        <h3>Application Number: <?= htmlspecialchars($app['application_id']) ?></h3>

        <h4>🐾 Pet Information</h4>
        <table class="app-details">
            <?php if (!empty($app['pet_photo'])): ?>
                <tr>
                    <th>Photo</th>
                    <td><img src="<?= htmlspecialchars($app['pet_photo']) ?>" alt="Pet Photo" class="pet-photo"></td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($app['pet_name']) ?></td>
            </tr>
            <tr>
                <th>Species</th>
                <td><?= htmlspecialchars($app['species']) ?></td>
            </tr>
            <tr>
                <th>Health</th>
                <td><?= htmlspecialchars($app['health_condition']) ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?= nl2br(htmlspecialchars($app['description'])) ?></td>
            </tr>
            <tr>
                <th>Vaccination History</th>
                <td><?= nl2br(htmlspecialchars($app['vaccination_history'])) ?></td>
            </tr>
            <tr>
                <th>Behavior Notes</th>
                <td><?= nl2br(htmlspecialchars($app['behavior_notes'])) ?></td>
            </tr>
            <tr>
                <th>Adoption Requirements</th>
                <td><?= nl2br(htmlspecialchars($app['adoption_requirements'])) ?></td>
            </tr>
        </table>

        <h4>👤 User Information</h4>
        <table class="app-details">
            <tr>
                <th>Full Name</th>
                <td><?= htmlspecialchars($app['user_fullname']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($app['user_email']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= htmlspecialchars($app['user_phone']) ?></td>
            </tr>
        </table>

        <h4>📄 Adoption Application Details</h4>
        <table class="app-details">
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars(ucfirst($app['application_status'])) ?></td>
            </tr>
            <tr>
                <th>Submitted</th>
                <td><?= date("M d, Y H:i", strtotime($app['created_at'])) ?></td>
            </tr>
            <tr>
                <th>Location</th>
                <td><?= htmlspecialchars($app['location']) ?></td>
            </tr>
            <tr>
                <th>Living Conditions</th>
                <td><?= nl2br(htmlspecialchars($app['living_conditions'])) ?></td>
            </tr>
            <tr>
                <th>Pet Experience</th>
                <td><?= nl2br(htmlspecialchars($app['pet_experience'])) ?></td>
            </tr>
            <tr>
                <th>Home Type</th>
                <td><?= htmlspecialchars($app['home_type']) ?></td>
            </tr>
            <tr>
                <th>Has Children</th>
                <td><?= htmlspecialchars($app['has_children']) ?></td>
            </tr>
            <tr>
                <th>Hours Alone</th>
                <td><?= htmlspecialchars($app['hours_alone']) ?></td>
            </tr>
            <tr>
                <th>Has Other Pets</th>
                <td><?= htmlspecialchars($app['has_other_pets']) ?></td>
            </tr>
            <tr>
                <th>Willing to Provide Vet Care</th>
                <td><?= htmlspecialchars($app['willing_vet_care']) ?></td>
            </tr>
        </table>

        <h4>📅 Appointment</h4>
        <?php if (!empty($app['appointment_date'])): ?>
            <table class="app-details">
                <tr>
                    <th>Scheduled For</th>
                    <td><?= date("M d, Y H:i", strtotime($app['appointment_date'])) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= htmlspecialchars($app['appointment_status']) ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p><em>No appointment scheduled.</em></p>
        <?php endif; ?>

        <?php
    else:
        echo "<p>Application not found.</p>";
    endif;

    mysqli_stmt_close($stmt);
}
?>