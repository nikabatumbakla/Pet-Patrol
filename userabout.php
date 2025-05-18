<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pawfect Home | About</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="stylesheet" href="style.css" />
    <style>
        .about-section h2 {
            font-size: 2.5rem;
            color: var(--dark-yellow);
            margin-bottom: 20px;
            position: relative;
        }

        .image-gallery {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .image-gallery img {
            width: 100%;
            max-width: 350px;
            height: auto;
            border-radius: var(--border-radius-m);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .image-gallery img:hover {
            transform: scale(1.03);
            cursor: pointer;
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

    <section class="about-section">
        <h2>About Us</h2>
        <div class="image-gallery">
            <img src="images/doglovinghuman.jpg" alt="Animal 1">
            <img src="images/petinhealthcare.jpg" alt="Animal 3">
        </div>

        <div class="about-horizontal-wrapper">
            <div class="about-card">
                <h3>Our Mission</h3>
                <p class="about-description">
                    This system is created with love and dedication by three girls from the Bachelor of Science in
                    Information
                    Technology program. Their mission is to build a user-friendly and compassionate platform that makes
                    pet
                    adoption easier, more transparent, and filled with joy.
                    <br><br>
            </div>
            <div class="about-card">
                <h3>Our Vision</h3>
                <p class="about-description">
                    🌿 <b> Every Life Matters: Pet Adoption and Application System</b> 🌿 is designed to connect loving
                    adopters
                    with pets in need. We believe in a world where every pet finds love, and every adopter finds a
                    lifelong
                    companion. 💛🐾
                </p>
            </div>
            <div class="about-card">
                <h3>Organization History</h3>
                <p class="about-description">
                    The idea was born from a deep passion for animals and the desire to create a digital bridge between
                    rescue
                    shelters and caring adopters. From a simple college project to a heart-driven mission, the platform
                    now
                    supports shelters in streamlining adoptions and donations.
                </p>
            </div>
            <div class="about-card">
                <h3>PonyoSosuke Foundation</h3>
                <p class="about-description">
                    Located in <b>Camarines Sur</b>, the <b>PonyoSosuke Adoption Center</b> is a beacon of hope for
                    maltreated,
                    abused, and stray animals. Inspired by the Ghibli animation "Ponyo", our foundation believes in
                    transformation, healing, and love for all beings.
                    <br><br>
            </div>
            <div class="about-card">
                <h3>>></h3>
                <p class="about-description">
                    We rescue, rehabilitate, and rehome animals, giving them a chance at the life they deserve. With
                    compassion as
                    our core, we provide medical care, shelter, and emotional support until they find their forever
                    homes.
                </p>
            </div>
            <div class="about-card">
                <h3>Why Pet Adoption Matters</h3>
                <p class="about-description">
                    These innocent souls may not speak our language, but in their eyes and soft whispers, they express
                    the desire
                    to be loved. Adoption not only saves lives—it brings unmatched companionship into yours.
                    <br><br>
            </div>
            <div class="about-card">
                <h3>>></h3>
                <p class="about-description">
                    We aim to educate future pet owners, help match pets to the right homes, and make adoption more
                    accessible
                    through technology. Together, we create a world where every tail wags with happiness and every paw
                    finds a
                    home.
                </p>
            </div>
        </div>
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

</body>

</html>