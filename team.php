<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team - Nyamula Logistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2BC652;
            --primary-dark: #229944;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
            --text-color: #1f2937;
            --text-light: #6b7280;
            --text-lighter: #9ca3af;
            --background: #f8fafc;
            --background-alt: #f1f5f9;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--white);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-image {
            height: 45px;
            width: auto;
            display: block;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.5rem;
        }

        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 0;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            padding: 8rem 0 4rem;
            text-align: center;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="white" stop-opacity="0.1"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .hero p {
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
            line-height: 1.7;
        }

        /* Team Section */
        .team {
            padding: 6rem 0;
            background-color: var(--background);
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
        }

        .team-member {
            background: var(--white);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .team-member-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .team-member:hover .team-member-image {
            transform: scale(1.05);
        }

        .team-member-info {
            padding: 2rem;
            text-align: center;
        }

        .team-member-name {
            font-size: 1.375rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .team-member-title {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .team-social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .team-social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--background);
            color: var(--text-light);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .team-social-link:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .back-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2.25rem;
            }

            .team-grid {
                grid-template-columns: 1fr;
                padding: 0 1rem;
            }

            .nav-links {
                display: none;
            }
        }

        /* Fade in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="./images/logo.png" alt="Nyamula Logistics" class="logo-image">
                </div>
            </a>
            <div class="nav-links">
                <a href="index.php" class="nav-link">HOME</a>
                <a href="index.php#services" class="nav-link">SERVICES</a>
                <a href="index.php#about" class="nav-link">ABOUT</a>
                <a href="index.php#contact" class="nav-link">CONTACT</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="hero-content fade-in">
                <h1>Meet Our Team</h1>
                <p>The passionate professionals driving our success and leading the future of African logistics</p>
            </div>
        </div>
    </section>

    <section class="team">
        <div class="container">
            <a href="index.php" class="back-button fade-in">
                <i class="fas fa-arrow-left"></i>
                Back to Home
            </a>
            <div class="section-header fade-in">
                <h2 class="section-title">Our Leadership Team</h2>
                <p class="section-subtitle">Meet the experts who are revolutionizing logistics across Africa</p>
            </div>
            <div class="team-grid">
                <div class="team-member fade-in">
                    <img src="./images/Faith.jpg" alt="Faith Mukumbiri" class="team-member-image">
                    <div class="team-member-info">
                        <h3 class="team-member-name">Faith Mukumbiri</h3>
                        <p class="team-member-title">Chief Executive Officer</p>
                        <div class="team-social-links">
                            <a href="https://www.linkedin.com/in/faith-mukumbiri-909b01105?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="team-social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.facebook.com/share/15F2CcCCTW/" class="team-social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/faffykue?igsh=dnNldXlzbG05MmI=" class="team-social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-member fade-in">
                    <img src="./images/Muraga.jpg" alt="Stephen Muraga" class="team-member-image">
                    <div class="team-member-info">
                        <h3 class="team-member-name">Stephen Muraga</h3>
                        <p class="team-member-title">Chief Business Development Officer</p>
                        <div class="team-social-links">
                            <a href="#" class="team-social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="team-social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-member fade-in">
                    <img src="./images/Euphemia.jpg" alt="Euphemia Chikungulu" class="team-member-image">
                    <div class="team-member-info">
                        <h3 class="team-member-name">Euphemia Chikungulu</h3>
                        <p class="team-member-title">Chief Technology Officer</p>
                        <div class="team-social-links">
                            <a href="https://www.linkedin.com/in/euphemia-chikungulu-a37745349?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="team-social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://x.com/tech_em_press?t=CVyhvBemik8OnYbhh7zZvA&s=09" class="team-social-link" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/euphemia.willards" class="team-social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/_eu_phemia?igsh=MTBsbG5vMXgyex==" class="team-social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@girl_developer?_r=1&_d=e76fcibdach2c0&sec_uid=MS4wLjABAAAASqg9HuZLzSdn86GtfOVWo3pH7euYLgg4cvcFuDuAusWw8bQyQOu6CvflNAHHw-Sd&share_author_id=6826340459217323013&sharer_language=en&source=h5_m&u_code=dcb9l8ijjj8hhk&timestamp=1738407526&user_id=6826340459217323013&sec_user_id=MS4wLjABAAAASqg9HuZLzSdn86GtfOVWo3pH7euYLgg4cvcFuDuAusWw8bQyQOu6CvflNAHHw-Sd&utm_source=copy&utm_campaign=client_share&utm_medium=android&share_iid=7464515162671646470&share_link_id=5c052ba0-2b5c-44bf-85fa-db8d4c57916d&share_app_id=1233&ugbiz_name=ACCOUNT&ug_btm=b8727%2Cb7360&social_share_type=5&enable_checksum=1" class="team-social-link" title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-member fade-in">
                    <img src="./images/Mtaja.jpg" alt="Faith Mtaja" class="team-member-image">
                    <div class="team-member-info">
                        <h3 class="team-member-name">Faith Mtaja</h3>
                        <p class="team-member-title">Strategic Assistant</p>
                        <div class="team-social-links">
                            <a href="https://www.linkedin.com/in/faith-mtaja-a59968263?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="team-social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://www.facebook.com/share/15yXkPVMr9/?mibextid=qi2Omg" class="team-social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="team-member fade-in">
                    <img src="./images/beatrice.jpg" alt="Beatrice Phiri Ngoma" class="team-member-image">
                    <div class="team-member-info">
                        <h3 class="team-member-name">Beatrice Phiri Ngoma</h3>
                        <p class="team-member-title">Logistics Officer</p>
                        <div class="team-social-links">
                            <a href="#" class="team-social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="team-social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
