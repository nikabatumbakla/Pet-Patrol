<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
  die("You must be logged in to view this page.");
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT user_id FROM Users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
  die("User not found.");
}

$user_id = $user['user_id'];

$check = $conn->prepare("SELECT agreed FROM TermsAndConditions WHERE user_id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$checkResult = $check->get_result();
$existingAgreement = $checkResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agree'])) {
  if ($existingAgreement) {
    $update = $conn->prepare("UPDATE TermsAndConditions SET agreed = 1, agreed_at = NOW() WHERE user_id = ?");
    $update->bind_param("i", $user_id);
    $update->execute();
  } else {
    $insert = $conn->prepare("INSERT INTO TermsAndConditions (user_id, agreed, agreed_at) VALUES (?, 1, NOW())");
    $insert->bind_param("i", $user_id);
    $insert->execute();
  }

  $pet_id = isset($_GET['pet_id']) ? intval($_GET['pet_id']) : 0;
  header("Location: apply.php?pet_id=$pet_id");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Terms and Conditions - Pet Patrol</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    :root {
      --primary-color: #e1a800;
      --secondary-color: #ffe094;
      --highlight-color: #f6e10d;
      --dark-yellow: #c88f0a;
    }

    body {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      background: var(--dark-yellow);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .terms-container {
      max-width: 600px;
      width: 90%;
      background: linear-gradient(135deg, #fdfcfa, #fff);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 2rem;
      border-radius: 12px;
    }

    .terms-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: var(--dark-yellow);
    }

    .terms-box {
      max-height: 250px;
      overflow-y: auto;
      padding: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #fff8e1;
      margin-bottom: 1.5rem;
    }

    .terms-box ul {
      padding-left: 1.2rem;
    }

    .terms-box li {
      margin-bottom: 10px;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    label {
      display: flex;
      align-items: center;
      font-size: 0.95rem;
      color: #444;
      margin-bottom: 1rem;
    }

    input[type="checkbox"] {
      margin-right: 10px;
      transform: scale(1.2);
    }

    button {
      padding: 10px 25px;
      background-color: var(--dark-yellow);
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #faa914;
    }

    /* Scrollbar styling (optional) */
    .terms-box::-webkit-scrollbar {
      width: 8px;
    }

    .terms-box::-webkit-scrollbar-thumb {
      background-color: #c8a10a;
      border-radius: 10px;
    }
  </style>
</head>

<body>

  <div class="terms-container">
    <h2>Terms and Conditions</h2>

    <div class="terms-box">
      <p>Please read the following carefully before proceeding:</p>
      <ul>
        <li>You will provide a safe, loving, and clean environment for the pet.</li>
        <li>You understand and accept the long-term commitment of being a pet owner.</li>
        <li>You will provide proper food, water, shelter, and medical care as needed.</li>
        <li>You agree not to use the pet for illegal, harmful, or experimental activities.</li>
        <li>You will ensure your pet gets proper socialization and mental stimulation.</li>
        <li>You will not abandon or neglect the pet under any circumstances.</li>
        <li>You agree to return the pet to the shelter if you are no longer able to care for it.</li>
        <li>You understand that adoption may involve follow-up checks or communication from the organization.</li>
        <li>You acknowledge that all information provided during the application is true and accurate.</li>
        <li>Violation of these terms may result in retrieval of the pet by the organization.</li>
      </ul>
    </div>

    <form method="POST" onsubmit="return validateAgreement()">
      <label>
        <input type="checkbox" id="agreeCheckbox" name="agree" value="1">
        I have read and agree to the terms and conditions.
      </label>
      <button type="submit">I Agree</button>
    </form>
  </div>

  <script>
    function validateAgreement() {
      const checkbox = document.getElementById('agreeCheckbox');
      if (!checkbox.checked) {
        alert('Please agree to the terms and conditions before proceeding.');
        return false;
      }
      return true;
    }
  </script>

</body>

</html>