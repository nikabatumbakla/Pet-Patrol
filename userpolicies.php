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

        .user-nav {
            background-color: #ffffff;
            border-bottom: 5px solidrgb(255, 225, 0);
            padding: 1rem 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .user-nav ul {
            list-style: none;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .user-nav ul li {
            position: relative;
        }

        /* Navigation Links */
        .user-nav ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .user-nav ul li a:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        /* Dropdown Toggle Button */
        .menu-toggle {
            border: 1px solid white;
            font-weight: 550;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 130%;
            right: 0;
            background-color: #fff;
            min-width: 250px;
            list-style: none;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.25s ease, transform 0.25s ease;
            z-index: 1000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dropdown-menu li {
            border-bottom: 1px solid #f2f2f2;
        }

        .dropdown-menu li:last-child {
            border-bottom: none;
        }

        .dropdown-menu li a {
            display: block;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dropdown-menu li a:hover {
            background-color: rgb(255, 255, 255);
            color: #000;
        }

        /* Show dropdown when active */
        .dropdown-nav.active .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-menu {
            transition: opacity 0.3s ease, transform 0.3s ease;
            animation: fadeSlideDown 0.35s ease forwards;
        }

        @keyframes fadeSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="navbar">
            <div class="logo">
                <img src="images/logo.png" alt="Pet Patrol Logo" class="logo-img">
                <div class="user-greeting">
                    <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</span>
                </div>
            </div>

            <nav class="user-nav">
                <ul>
                    <li><a href="petlist.php">Browse Pets</a></li>
                    <li><a href="applications.php">My Applications</a></li>
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="donations.php">Donations</a></li>

                    <li class="dropdown-nav">
                        <button class="menu-toggle">
                            <i class="fas fa-bars"></i> Menu
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="home.php">Home</a></li>
                            <li><a href="userabout.php">About Us</a></li>
                            <li><a href="userpolicies.php">Adoption Policies</a></li>
                            <li><a href="userservices.php">Services</a></li>
                            <li><a href="logout.php" class="logout">Logout</a></li>
                        </ul>
                    </li>
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