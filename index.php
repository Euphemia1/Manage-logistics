<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nyamula Logistics - Your Trusted Partner for Seamless Shipping and Delivery</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset and base styles */
        :root {
            --primary-color:rgb(43, 198, 82);
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --light-text: #6b7280;
            --background: #f3f4f6;
            --white: #ffffff;
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
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        /* Navigation */
        .navbar {
            background-color: var(--white);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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
        }
        .logo-image {
            height: 40px; /* Reduced from 50px */
            width: auto;
            display: block;
            object-fit: contain; /* Ensures the image maintains its aspect ratio */
        }
        @media (max-width: 768px) {
            .logo-image {
                height: 30px; /* Reduced from 40px for mobile devices */
            }
        }
        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        @media (max-width: 768px) {
            .logo-image {
                height: 30px;
            }
            .logo-text {
                font-size: 1.2rem;
            }
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--primary-color);
        }
        .dropdown {
            position: relative;
        }
        .dropbtn {
            background: none;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
            padding: 0.5rem 1rem;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--white);
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .dropdown-content a {
            color: var(--text-color);
            padding: 0.75rem 1rem;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
        }
        .dropdown-content a:hover {
            background-color: var(--background);
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            padding: 8rem 0 6rem;
            text-align: center;
            margin-top: 80px;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        .hero p {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        .cta-button {
            display: inline-block;
            background-color: var(--white);
            color: var(--primary-color);
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        /* Features Section */
        .features {
            padding: 5rem 0;
            background-color: var(--white);
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 1rem;
            background-color: var(--white);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
        }
        .feature-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .feature-card h3 {
            color: var(--text-color);
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
        .feature-card p {
            color: var(--light-text);
        }
        /* About Section */
        .about {
            padding: 5rem 0;
            background-color: var(--background);
            text-align: center;
        }
        .about h2 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: var(--text-color);
        }
        .about p {
            max-width: 800px;
            margin: 0 auto;
            color: var(--light-text);
        }
        /* Team Section */
        .team {
            padding: 5rem 0;
            background-color: var(--white);
        }
        .team h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: var(--text-color);
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            margin-top: 2rem;
        }
        .team-member {
            text-align: center;
            background-color: var(--white);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .team-member:hover {
            transform: translateY(-5px);
        }
        .team-member-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        .team-member-info {
            padding: 1.5rem;
        }
        .team-member-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }
        .team-member-title {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .team-social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        .team-social-link {
            color: var(--light-text);
            transition: color 0.3s ease;
        }
        .team-social-link:hover {
            color: var(--primary-color);
        }
        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: 1fr;
                padding: 0 1rem;
            }
        }

        /* Countries Carousel Section */
        .countries-section {
            padding: 5rem 0;
            background-color: var(--background);
            overflow: hidden;
        }
        .countries-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--text-color);
        }
        .countries-section p {
            text-align: center;
            color: var(--light-text);
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }
        .carousel-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
        }
        .carousel-track {
            display: flex;
            animation: scroll 30s linear infinite;
            width: calc(200px * 22); /* 11 countries * 2 for seamless loop */
        }
        .carousel-track:hover {
            animation-play-state: paused;
        }
        .country-item {
            flex: 0 0 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0 1rem;
            background: var(--white);
            border-radius: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .country-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        .country-flag {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .country-name {
            font-weight: 600;
            color: var(--text-color);
            text-align: center;
            font-size: 0.9rem;
        }
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-200px * 11));
            }
        }
        @media (max-width: 768px) {
            .countries-section h2 {
                font-size: 2rem;
            }
            .country-item {
                flex: 0 0 150px;
                margin: 0 0.5rem;
            }
            .carousel-track {
                width: calc(150px * 22);
            }
            @keyframes scroll {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-150px * 11));
                }
            }
        }

        /* Contact Section */
        .contact {
            padding: 5rem 0;
            background-color: var(--white);
        }
        .contact h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }
        .contact-info {
            display: grid;
            gap: 2rem;
        }
        .contact-card {
            padding: 2rem;
            background-color: var(--background);
            border-radius: 1rem;
        }
        .contact-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .contact-form {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-family: 'Inter', sans-serif;
        }
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
        .submit-button {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: var(--secondary-color);
        }
        /* Footer */
        .footer {
            background-color: var(--text-color);
            color: var(--white);
            padding: 4rem 0 2rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        .footer-section h3 {
            color: var(--white);
            margin-bottom: 1.5rem;
        }
        .footer-section a {
            color: #9ca3af;
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: color 0.3s ease;
        }
        .footer-section a:hover {
            color: var(--white);
        }
        .social-links {
            display: flex;
            gap: 1rem;
        }
        .social-link {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .social-link:hover {
            color: var(--white);
        }
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #374151;
        }
        /* Mobile Navigation */
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
        }
        .nav-toggle span {
            display: block;
            width: 25px;
            height: 2px;
            background-color: var(--text-color);
            transition: 0.3s;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-toggle {
                display: flex;
            }
            .nav-links {
                display: none;
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                background-color: var(--white);
                flex-direction: column;
                padding: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .nav-links.active {
                display: flex;
            }
            .dropdown-content {
                position: static;
                box-shadow: none;
                display: none;
            }
            .dropdown:hover .dropdown-content {
                display: none;
            }
            .hero {
                padding: 6rem 0 4rem;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .social-links {
                justify-content: center;
            }
        }
        .site-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .site-title .green {
            color: var(--primary-color);
        }
        .site-title .black {
            color: #000;
        }
        .hero h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        @media (max-width: 768px) {
            .site-title {
                font-size: 2.5rem;
            }
            .hero h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="./images/logo.png" alt="Nyamula Logistics" class="logo-image">
                              
                </div>
            </a>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="nav-links">
                <a href="#" class="nav-link">HOME</a>
                <a href="#" class="nav-link">SERVICES</a>
                <div class="dropdown">
                    <button class="dropbtn">CARGO OWNER</button>
                    <div class="dropdown-content">
                        <a href="cargo-signup.php">REGISTER</a>
                        <a href="cargo-owner-login.php">LOGIN</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">TRANSPORTER</button>
                    <div class="dropdown-content">
                        <a href="transporter_signup.php">REGISTER</a>
                        <a href="transporter_login.php">LOGIN</a>
                    </div>
                    </div>
                <a href="job-post.php" class="nav-link">AVAILABLE LOADS</a>
            </div>
                <!-- </div>
                <a href="#contact" class="nav-link">CONTACT US</a>
            </div> -->
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="site-title">
                <span class="green">Nyamula</span> <span class="black">Logistics</span>
            </h1>
            <h2>Your Trusted Partner for Seamless Shipping and Delivery</h2>
            <p>At Nyamula Logistics, we provide fast, reliable, and cost-effective shipping solutions tailored to your needs. With a network of trusted partners and real-time tracking, we ensure your goods are delivered securely and efficiently, every time.
                            </p>
            <a href="#services" class="cta-button">Learn More</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1580674285054-bed31e145f59?w=800&auto=format&fit=crop&q=60" alt="Fast delivery" class="feature-img">
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Get your products delivered quickly and reliably with our trusted shipping solutions.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1512758017271-d7b84c2113f1?w=800&auto=format&fit=crop&q=60" alt="Real-time tracking" class="feature-img">
                    </div>
                    <h3>Real-Time Tracking</h3>
                    <p>Track your shipments in real-time for complete peace of mind and transparency.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1580519542036-c47de6196ba5?w=800&auto=format&fit=crop&q=60" alt="Flexible pricing" class="feature-img">
                    </div>
                    <h3>Flexible Pricing</h3>
                    <p>Choose from a range of pricing plans that suit businesses of all sizes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1534536281715-e28d76689b4d?w=800&auto=format&fit=crop&q=60" alt="24/7 support" class="feature-img">
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is available around the clock to assist you.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <h2>About Us</h2>
            <p>Nyamula Logistics is dedicated to providing seamless, efficient, and reliable logistics services. 
                We provide a technology-driven platform that connects cargo owners with trusted and reliable small-scale truck operators, offering cost-effective and timely logistics solutions for businesses of all sizes.
                Nyamula Logistics is a Zambian logistics company tackling the challenge of high carbon emissions in mining and agriculture. 
                We aggregate small-scale truck operators and optimize their routes, while also developing a groundbreaking solution that combines fuel-efficient hardware retrofits for existing trucks with advanced carbon capture technology at mining sites. 
                This integrated approach empowers small-scale operators to optimize their capacity and helps mining companies reduce their carbon footprint, enhancing operational efficiency and promoting sustainability.  
                By collaborating with a network of technology providers across the globe, we deliver cutting-edge solutions tailored to the African context, driving a greener future for the mining and agricultural sectors.</p>
        </div>
    </section>

    <!-- Team Section -->
<section class="team" id="team">
    <div class="container">
        <h2>Meet Our Team</h2>
        <div class="team-grid">
            <div class="team-member">
                <img src="./images/Faith.jpg" alt="" class="team-member-image">
                <div class="team-member-info">
                    <h3 class="team-member-name">Faith Mukumbiri</h3>
                    <p class="team-member-title">Chief Executive Officer</p>
                    <div class="team-social-links">
                        <a href="https://www.linkedin.com/in/faith-mukumbiri-909b01105?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="team-social-link" title="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="10"></rect><circle cx="4" cy="4" r="2"></circle></svg></a>
                        <!-- <a href="#" class="team-social-link" title="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg></a> -->
                        <a href="https://www.facebook.com/share/15F2CcCCTW/" class="team-social-link" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                         <a href="https://www.instagram.com/faffykue?igsh=dnNldXlzbG05MmI=" class="team-social-link" title="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                         <!-- <a href="#" class="team-social-link" title="TikTok"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5Z"></path><path d="M12 12a5 5 0 0 1 5 5"></path><path d="M12 12v9"></path><path d="M12 12a5 5 0 0 0 5-5"></path></svg></a>  -->
                    </div>
                </div>
            </div>
            <div class="team-member">
                <img src="./images/Muraga.jpg" alt="" class="team-member-image">
                <div class="team-member-info">
                    <h3 class="team-member-name">Stephen Muraga</h3>
                    <p class="team-member-title">Chief Business Development Officer</p>
                    <div class="team-social-links">
                        <a href="#" class="team-social-link" title="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg></a>
                        <!-- <a href="#" class="team-social-link" title="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 94-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg></a> -->
                        <a href="#" class="team-social-link" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                        <!-- <a href="#" class="team-social-link" title="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                        <a href="#" class="team-social-link" title="TikTok"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5Z"></path><path d="M12 12a5 5 0 0 1 5 5"></path><path d="M12 12v9"></path><path d="M12 12a5 5 0 0 0 5-5"></path></svg></a> -->
                    </div>
                </div>
            </div>
            <div class="team-member">
                <img src="./images/Euphemia.jpg" alt="" class="team-member-image">
                <div class="team-member-info">
                    <h3 class="team-member-name">Euphemia Chikungulu</h3>
                    <p class="team-member-title">Chief Technology Officer</p>
                    <div class="team-social-links">
                        <a href="https://www.linkedin.com/in/euphemia-chikungulu-a37745349?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="team-social-link" title="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg></a>
                        <a href="https://x.com/tech_em_press?t=CVyhvBemik8OnYbhh7zZvA&s=09" class="team-social-link" title="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-1811.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 94.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg></a>
                        <a href="https://www.facebook.com/euphemia.willards" class="team-social-link" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                        <a href="https://www.instagram.com/_eu_phemia?igsh=MTBsbG5vMXgyex==" class="team-social-link" title="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                        <a href="https://www.tiktok.com/@girl_developer?_r=1&_d=e76fcibdach2c0&sec_uid=MS4wLjABAAAASqg9HuZLzSdn86GtfOVWo3pH7euYLgg4cvcFuDuAusWw8bQyQOu6CvflNAHHw-Sd&share_author_id=6826340459217323013&sharer_language=en&source=h5_m&u_code=dcb9l8ijjj8hhk&timestamp=1738407526&user_id=6826340459217323013&sec_user_id=MS4wLjABAAAASqg9HuZLzSdn86GtfOVWo3pH7euYLgg4cvcFuDuAusWw8bQyQOu6CvflNAHHw-Sd&utm_source=copy&utm_campaign=client_share&utm_medium=android&share_iid=7464515162671646470&share_link_id=5c052ba0-2b5c-44bf-85fa-db8d4c57916d&share_app_id=1233&ugbiz_name=ACCOUNT&ug_btm=b8727%2Cb7360&social_share_type=5&enable_checksum=1" class="team-social-link" title="TikTok"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5Z"></path><path d="M12 12a5 5 0 0 1 5 5"></path><path d="M12 12v9"></path><path d="M12 12a5 5 0 0 0 5-5"></path></svg></a>
                    </div>
                </div>
            </div>
            <div class="team-member">
                <img src="./images/Mtaja.jpg" alt="" class="team-member-image">
                <div class="team-member-info">
                    <h3 class="team-member-name">Faith Mtaja</h3>
                    <p class="team-member-title">Strategic Assistant</p>
                    <div class="team-social-links">
                        <a href="https://www.linkedin.com/in/faith-mtaja-a59968263?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" class="team-social-link" title="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg></a>
                        <!-- <a href="#" class="team-social-link" title="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg></a> -->
                        <a href="https://www.facebook.com/share/15yXkPVMr9/?mibextid=qi2Omg" class="team-social-link" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                        <!-- <a href="#" class="team-social-link" title="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                        <a href="#" class="team-social-link" title="TikTok"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5Z"></path><path d="M12 12a5 5 0 0 1 5 5"></path><path d="M12 12v9"></path><path d="M12 12a5 5 0 0 0 5-5"></path></svg></a> -->
                    </div>
                </div>
            </div>
            <div class="team-member">
                <img src="./images/beatrice.jpg" alt="" class="team-member-image">
                <div class="team-member-info">
                    <h3 class="team-member-name">Beatrice Phiri Ngoma</h3>
                    <p class="team-member-title">Logistics Officer</p>
                    <div class="team-social-links">
                        <a href="#" class="team-social-link" title="LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg></a>
                        <!-- <a href="#" class="team-social-link" title="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg></a> -->
                        <a href="#" class="team-social-link" title="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Countries We Deliver To Section -->
    <section class="countries-section">
        <div class="container">
            <h2>Countries We delivered To</h2>
            <p>Trusted logistics solutions across Africa - connecting businesses and communities</p>
            <div class="carousel-container">
                <div class="carousel-track">
                    <!-- First set of countries -->
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/bw.png" alt="Botswana Flag" class="country-flag">
                        <div class="country-name">Botswana</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/cd.png" alt="Congo Flag" class="country-flag">
                        <div class="country-name">Congo</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/ke.png" alt="Kenya Flag" class="country-flag">
                        <div class="country-name">Kenya</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/mw.png" alt="Malawi Flag" class="country-flag">
                        <div class="country-name">Malawi</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/mz.png" alt="Mozambique Flag" class="country-flag">
                        <div class="country-name">Mozambique</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/na.png" alt="Namibia Flag" class="country-flag">
                        <div class="country-name">Namibia</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/rw.png" alt="Rwanda Flag" class="country-flag">
                        <div class="country-name">Rwanda</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/za.png" alt="South Africa Flag" class="country-flag">
                        <div class="country-name">South Africa</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/tz.png" alt="Tanzania Flag" class="country-flag">
                        <div class="country-name">Tanzania</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/zm.png" alt="Zambia Flag" class="country-flag">
                        <div class="country-name">Zambia</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/zw.png" alt="Zimbabwe Flag" class="country-flag">
                        <div class="country-name">Zimbabwe</div>
                    </div>
                    <!-- Duplicate set for seamless loop -->
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/bw.png" alt="Botswana Flag" class="country-flag">
                        <div class="country-name">Botswana</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/cd.png" alt="Congo Flag" class="country-flag">
                        <div class="country-name">Congo</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/ke.png" alt="Kenya Flag" class="country-flag">
                        <div class="country-name">Kenya</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/mw.png" alt="Malawi Flag" class="country-flag">
                        <div class="country-name">Malawi</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/mz.png" alt="Mozambique Flag" class="country-flag">
                        <div class="country-name">Mozambique</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/na.png" alt="Namibia Flag" class="country-flag">
                        <div class="country-name">Namibia</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/rw.png" alt="Rwanda Flag" class="country-flag">
                        <div class="country-name">Rwanda</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/za.png" alt="South Africa Flag" class="country-flag">
                        <div class="country-name">South Africa</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/tz.png" alt="Tanzania Flag" class="country-flag">
                        <div class="country-name">Tanzania</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/zm.png" alt="Zambia Flag" class="country-flag">
                        <div class="country-name">Zambia</div>
                    </div>
                    <div class="country-item">
                        <img src="https://flagcdn.com/w320/zw.png" alt="Zimbabwe Flag" class="country-flag">
                        <div class="country-name">Zimbabwe</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <h2>Get In Touch</h2>
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-card">
                        <h3>Location</h3>
                        <p>Oakhill Business Park, 2337 Kabelenga Road, Lusaka, Zambia</p>
                    </div>
                    <div class="contact-card">
                        <h3>Hours</h3>
                        <p>Mon - Fri: 9 AM - 6 PM</p>
                        <p>Sat - Sun: Closed</p>
                    </div>
                    <div class="contact-card">
                        <h3>Contact</h3>
                        <p>Phone: +260975509196</p>
                        <p>Email: <a href="mailto:admin@nyamula.com">admin@nyamula.com</a></p>
                    </div>
                </div>
                <form class="contact-form" action="https://formspree.io/f/mdkgeqbd" method="POST">
    <input type="hidden" name="_format" value="plain">
    <h3>Send us a message</h3>
    <div class="form-group">
        <input type="text" name="name" placeholder="Your Name" required>
    </div>
    <div class="form-group">
        <input type="email" name="email" placeholder="Your Email" required>
    </div>
    <div class="form-group">
        <textarea name="message" placeholder="Your Message" required></textarea>
    </div>
    <button type="submit" class="submit-button">Send Message</button>
</form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="#">Home</a>
                    <a href="#">Services</a>
                    <a href="#">Pricing</a>
                    <a href="#">Contact Us</a>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>+260975509196</p>
                    <p><a href="mailto:admin@nyamula.com">admin@nyamula.com</a></p>
                    <p>Oak Hill Business Park, 2237 Kabelenga Road, Lusaka, Zambia</p>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="https://www.facebook.com/nyamulalogistics" class="social-link">Facebook</a>
                        <a href="https://www.linkedin.com/company/nyamula/" class="social-link">LinkedIn</a>
                                           
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Nyamula Logistics. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.nav-toggle').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });

        // Dropdown functionality for mobile
        document.querySelectorAll('.dropbtn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.nextElementSibling.classList.toggle('active');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.dropbtn')) {
                document.querySelectorAll('.dropdown-content').forEach(function(content) {
                    content.classList.remove('active');
                });
            }
        });

        // Resize handler to reset mobile menu state
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.nav-links').classList.remove('active');
                document.querySelectorAll('.dropdown-content').forEach(function(content) {
                    content.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>