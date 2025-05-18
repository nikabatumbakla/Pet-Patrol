<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pawfect Home | Policies</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="stylesheet" href="style.css" />
    <style>
        .faq-section {
            max-width: 2000px;
            margin: 0px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            font-family: 'Segoe UI', sans-serif;
        }

        .faq-section h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
            color: #333;
        }

        .faq {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 14px;
            background: rgb(237, 183, 96);
            transition: all 0.3s ease;
        }

        .faq:hover {
            background: rgb(255, 255, 255);
        }

        .faq img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 12px;
            flex-shrink: 0;
        }

        .faq-content {
            flex: 1;
        }

        .faq-content h3 {
            font-size: 20px;
            margin: 0 0 10px;
            color: #f58220;
            cursor: pointer;
        }

        .faq-content p {
            margin: 0;
            color: #444;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <header>
        <div class="navbar">
            <div class="logo">
                <img src="images/logo.png" alt="Pet Patrol Logo" class="logo-img">
                🐾 Pet Patrol <span>| Where every pet finds a family</span>
            </div>

            <nav class="dropdown-nav">
                <button class="menu-toggle">☰ Menu</button>
                <ul class="dropdown-menu">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="policies.php">Adoption Policies</a></li>
                    <li><a href="petlisting.php">Available Pets</a></li>
                    <li><a href="developer.php">Developer</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
    </header>

    <section class="adoption-policies">
        <h2 class="section-heading">Adoption Policies</h2>
        <p>‎ </p>
        <p>‎ </p>
        <div class="policy-container">
            <div class="policy-box">
                <h3>Eligibility</h3>
                <p>Applicants must be at least 18 years old and must reside in areas where our adoption services are
                    available.</p>
            </div>
            <div class="policy-box">
                <h3>Requirements</h3>
                <p>We may conduct home checks to ensure a safe environment. Adoption fees may apply depending on the
                    animal's needs.</p>
            </div>
            <div class="policy-box">
                <h3>Terms & Conditions</h3>
                <p>All adopters must agree to our terms and conditions, ensuring responsible care and lifelong
                    commitment to the adopted pet.</p>
            </div>
        </div>
    </section>


    <!-- FAQ Section -->
    <section class="faq-section">
        <h2>Frequently Asked Questions</h2>

        <?php
        $faqs = [
            [
                "question" => "Can a pet have multiple adopters?",
                "answer" => "No, a pet can only have one adopter at a time. Once a pet is adopted, it is no longer available for adoption.",
                "image" => "images/oneperson.png"
            ],
            [
                "question" => "Can I adopt multiple pets?",
                "answer" => "Yes, but each pet requires a separate application to ensure the best match for both pet and adopter.",
                "image" => "images/PetsTogether.png"
            ],
            [
                "question" => "Do I need to register before applying for adoption?",
                "answer" => "Yes, only registered users can submit adoption applications and schedule appointments.",
                "image" => "images/Register.png"
            ],
            [
                "question" => "Can I make a donation?",
                "answer" => "Yes, any registered user can make a donation. However, donations are non-refundable.",
                "image" => "images/DonationBox.png"
            ],
            [
                "question" => "How are pets categorized?",
                "answer" => "Each pet is assigned a unique ID and categorized by species, breed, age, and health condition.",
                "image" => "images/PetTags.png"
            ],
            [
                "question" => "How is adoption status updated?",
                "answer" => "Adoption statuses (available, pending, adopted) are updated in real-time by the shelter staff.",
                "image" => "images/StatusUpdate.png"
            ],
            [
                "question" => "Who can manage pet listings and applications?",
                "answer" => "Shelter staff have administrative access to manage pet listings, review applications, and schedule adoption appointments.",
                "image" => "images/AdminAccess.png"
            ],
            [
                "question" => "Are terms and conditions required for adoption?",
                "answer" => "Yes, all users must agree to the terms and conditions before submitting an adoption application.",
                "image" => "images/Agreement.png"
            ],
            [
                "question" => "Can my application be rejected?",
                "answer" => "Yes, the shelter staff can reject an application if the adopter does not meet the adoption requirements.",
                "image" => "images/Rejected.png"
            ],
            [
                "question" => "Who can update pet profiles and process donations?",
                "answer" => "Only authorized shelter staff can update pet profiles, manage applications, and process donations.",
                "image" => "images/admin.png"
            ]
        ];

        foreach ($faqs as $faq): ?>
            <div class="faq">
                <img src="<?= $faq['image'] ?>" alt="FAQ Image">
                <div class="faq-content">
                    <h3 onclick="toggleFaq(this)"><?= htmlspecialchars($faq['question']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <footer class="site-footer">
        <div class="footer-content">
            <p>Reach us at:
                <a href="mailto:ponyososukesheltercenter@gmail.com">ponyososukesheltercenter@gmail.com</a>
            </p>
            <p>&copy; 2025 ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.querySelector('.menu-toggle');
            const dropdownNav = document.querySelector('.dropdown-nav');

            // Toggle menu
            toggleButton.addEventListener('click', function (event) {
                event.stopPropagation();
                dropdownNav.classList.toggle('active');
            });

            // Click outside to close
            document.addEventListener('click', function (event) {
                if (!dropdownNav.contains(event.target)) {
                    dropdownNav.classList.remove('active');
                }
            });

            // Close on scroll
            window.addEventListener('scroll', function () {
                dropdownNav.classList.remove('active');
            });
        });
    </script>

    <script>
        function toggleFaq(element) {
            var faq = element.parentElement.parentElement;
            faq.classList.toggle('open');
        }
    </script>

</body>

</html>