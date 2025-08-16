<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nyamula Logistics - Your Trusted Partner for Seamless Shipping and Delivery</title>
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

        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-lg);
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

        .dropdown {
            position: relative;
        }

        .dropbtn {
            background: none;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropbtn::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .dropdown:hover .dropbtn::after {
            transform: rotate(180deg);
        }

        .dropbtn:hover {
            background-color: var(--background);
            color: var(--primary-color);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--white);
            min-width: 200px;
            box-shadow: var(--shadow-xl);
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-content a {
            color: var(--text-color);
            padding: 1rem 1.25rem;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            font-weight: 500;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            transform: translateX(5px);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

       
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            padding: 10rem 0 8rem;
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

        .site-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .site-title .green {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .site-title .black {
            color: rgba(255, 255, 255, 0.95);
        }

        .hero h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            opacity: 0.95;
            letter-spacing: -0.01em;
        }

        .hero p {
            font-size: 1.125rem;
            max-width: 700px;
            margin: 0 auto 3rem;
            opacity: 0.9;
            line-height: 1.7;
        }

        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background-color: var(--white);
            color: var(--primary-color);
            padding: 1.25rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
            background: linear-gradient(135deg, var(--white) 0%, var(--background) 100%);
        }

        
        .features {
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

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .feature-card h3 {
            color: var(--text-color);
            margin-bottom: 1rem;
            font-size: 1.375rem;
            font-weight: 600;
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.6;
        }

       
        .about {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--white) 0%, var(--background-alt) 100%);
        }

        .about .section-header {
            margin-bottom: 3rem;
        }

        .about-content {
            max-width: 900px;
            margin: 0 auto;
            font-size: 1.125rem;
            line-height: 1.8;
            color: var(--text-light);
            text-align: center;
        }

       
        .countries-section {
            padding: 6rem 0;
            background: var(--white);
            overflow: hidden;
        }

        .carousel-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--background) 0%, var(--background-alt) 100%);
            border-radius: 2rem;
            box-shadow: var(--shadow-lg);
            padding: 3rem 0;
            margin-top: 2rem;
        }

        .carousel-track {
            display: flex;
            animation: scroll 40s linear infinite;
            width: calc(220px * 22);
        }

        .carousel-track:hover {
            animation-play-state: paused;
        }

        .country-item {
            flex: 0 0 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0 1rem;
            background: var(--white);
            border-radius: 1.25rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .country-item:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .country-flag {
            width: 90px;
            height: 68px;
            object-fit: cover;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .country-item:hover .country-flag {
            transform: scale(1.1);
        }

        .country-name {
            font-weight: 600;
            color: var(--text-color);
            text-align: center;
            font-size: 1rem;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-220px * 11));
            }
        }

      
        .contact {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--background) 0%, var(--background-alt) 100%);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-top: 3rem;
        }

        .contact-info {
            display: grid;
            gap: 2rem;
        }

        .contact-card {
            padding: 2.5rem;
            background: var(--white);
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .contact-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .contact-card p {
            color: var(--text-light);
            line-height: 1.6;
        }

        .contact-card a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-card a:hover {
            color: var(--primary-dark);
        }

        .contact-form {
            background: var(--white);
            padding: 3rem;
            border-radius: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .contact-form h3 {
            color: var(--text-color);
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--background);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(43, 198, 82, 0.1);
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .submit-button {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: 1.25rem 2.5rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

       
        .footer {
            background: linear-gradient(135deg, var(--text-color) 0%, #111827 100%);
            color: var(--white);
            padding: 5rem 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            color: var(--white);
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .footer-section a {
            color: var(--text-lighter);
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            padding: 0.25rem 0;
        }

        .footer-section a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .footer-section p {
            color: var(--text-lighter);
            margin-bottom: 0.75rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-lighter);
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #374151;
            color: var(--text-lighter);
        }

       
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .nav-toggle:hover {
            background-color: var(--background);
        }

        .nav-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: var(--text-color);
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .nav-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

       
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
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                flex-direction: column;
                padding: 2rem;
                box-shadow: var(--shadow-xl);
                border-top: 1px solid var(--border-color);
            }

            .nav-links.active {
                display: flex;
            }

            .dropdown {
                width: 100%;
            }

            .dropbtn {
                width: 100%;
                text-align: left;
                justify-content: space-between;
                background: var(--background);
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .dropbtn:active,
            .dropbtn:focus {
                background: var(--primary-color);
                color: var(--white);
                outline: none;
            }

            .dropdown-content {
                position: static;
                box-shadow: none;
                display: none;
                background: var(--background);
                border-radius: 0.75rem;
                margin-top: 0.5rem;
                border: 1px solid var(--border-color);
            }

            .dropdown-content.active {
                display: block;
                animation: fadeInUp 0.3s ease;
            }

            .dropdown-content a {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .dropdown-content a:hover {
                background: var(--primary-color);
                color: var(--white);
                transform: none;
            }

            .hero {
                padding: 8rem 0 6rem;
            }

            .site-title {
                font-size: 2.5rem;
            }

            .hero h2 {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 2.25rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .carousel-track {
                width: calc(180px * 22);
            }

            .country-item {
                flex: 0 0 180px;
                margin: 0 0.5rem;
            }

            @keyframes scroll {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-180px * 11));
                }
            }
        }

    
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

      
        .loading {
            opacity: 0;
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body class="loading">
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
                <a href="#services" class="nav-link">SERVICES</a>
                <div class="dropdown">
                    <button class="dropbtn">ABOUT US</button>
                    <div class="dropdown-content">
                        <a href="#about"><i class="fas fa-info-circle"></i> OUR STORY</a>
                        <a href="team.php"><i class="fas fa-users"></i> OUR TEAM</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">CARGO OWNER</button>
                    <div class="dropdown-content">
                        <a href="cargo-signup.php"><i class="fas fa-user-plus"></i> REGISTER</a>
                        <a href="cargo-owner-login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">TRANSPORTER</button>
                    <div class="dropdown-content">
                        <a href="transporter_signup.php"><i class="fas fa-truck"></i> REGISTER</a>
                        <a href="transporter_login.php"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
                    </div>
                </div>
                <a href="job-board.php" class="nav-link">AVAILABLE LOADS</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="hero-content fade-in">
                <h1 class="site-title">
                    <span class="green">Nyamula</span> <span class="black">Logistics</span>
                </h1>
                <h2>Your Trusted Partner for Seamless Shipping and Delivery</h2>
                <p>At Nyamula Logistics, we provide fast, reliable, and cost-effective shipping solutions tailored to your needs. With a network of trusted partners and real-time tracking, we ensure your goods are delivered securely and efficiently, every time.</p>
                <a href="#services" class="cta-button">
                    <i class="fas fa-arrow-right"></i>
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <section class="features" id="services">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Our Services</h2>
                <p class="section-subtitle">Comprehensive logistics solutions designed to meet your business needs</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1580674285054-bed31e145f59?w=800&auto=format&fit=crop&q=60" alt="Fast delivery" class="feature-img">
                    </div>
                    <h3>Fast Delivery</h3>
                    <p>Get your products delivered quickly and reliably with our trusted shipping solutions across Africa.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1512758017271-d7b84c2113f1?w=800&auto=format&fit=crop&q=60" alt="Real-time tracking" class="feature-img">
                    </div>
                    <h3>Real-Time Tracking</h3>
                    <p>Track your shipments in real-time for complete peace of mind and transparency throughout the journey.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1580519542036-c47de6196ba5?w=800&auto=format&fit=crop&q=60" alt="Flexible pricing" class="feature-img">
                    </div>
                    <h3>Flexible Pricing</h3>
                    <p>Choose from a range of competitive pricing plans that suit businesses of all sizes and budgets.</p>
                </div>
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <img src="https://images.unsplash.com/photo-1534536281715-e28d76689b4d?w=800&auto=format&fit=crop&q=60" alt="24/7 support" class="feature-img">
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is available around the clock to assist you with any logistics needs.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">About Us</h2>
                <p class="section-subtitle">Leading the future of African logistics</p>
            </div>
            <div class="about-content fade-in">
                <p>Nyamula Logistics is dedicated to providing seamless, efficient, and reliable logistics services. We provide a technology-driven platform that connects cargo owners with trusted and reliable small-scale truck operators, offering cost-effective and timely logistics solutions for businesses of all sizes.</p>
                <br>
                <p>Nyamula Logistics is a Zambian logistics company tackling the challenge of high carbon emissions in mining and agriculture. We aggregate small-scale truck operators and optimize their routes, while also developing a groundbreaking solution that combines fuel-efficient hardware retrofits for existing trucks with advanced carbon capture technology at mining sites.</p>
                <br>
                <p>This integrated approach empowers small-scale operators to optimize their capacity and helps mining companies reduce their carbon footprint, enhancing operational efficiency and promoting sustainability. By collaborating with a network of technology providers across the globe, we deliver cutting-edge solutions tailored to the African context, driving a greener future for the mining and agricultural sectors.</p>
            </div>
        </div>
    </section>

    <section class="countries-section">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Countries We delivered To</h2>
                <p class="section-subtitle">Trusted logistics solutions across Africa - connecting businesses and communities</p>
            </div>
            <div class="carousel-container">
                <div class="carousel-track">
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

    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header fade-in">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">Ready to streamline your logistics? Contact us today</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-card fade-in">
                        <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                        <p>Oakhill Business Park, 2337 Kabelenga Road, Lusaka, Zambia</p>
                    </div>
                    <div class="contact-card fade-in">
                        <h3><i class="fas fa-clock"></i> Hours</h3>
                        <p>Mon - Fri: 9 AM - 6 PM</p>
                        <p>Sat - Sun: Closed</p>
                    </div>
                    <div class="contact-card fade-in">
                        <h3><i class="fas fa-phone"></i> Contact</h3>
                        <p>Phone: +260975509196</p>
                        <p>Email: <a href="mailto:admin@nyamula.com">admin@nyamula.com</a></p>
                    </div>
                </div>
                <form class="contact-form fade-in" action="https://formspree.io/f/mdkgeqbd" method="POST">
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
                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="#">Home</a>
                    <a href="#services">Services</a>
                    <a href="#about">About Us</a>
                    <a href="#contact">Contact Us</a>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-phone"></i> +260975509196</p>
                    <p><i class="fas fa-envelope"></i> <a href="mailto:admin@nyamula.com">admin@nyamula.com</a></p>
                    <p><i class="fas fa-map-marker-alt"></i> Oak Hill Business Park, 2237 Kabelenga Road, Lusaka, Zambia</p>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="https://www.facebook.com/nyamulalogistics" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/nyamula/" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Nyamula Logistics. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
   
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });

        
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

     
        const navToggle = document.querySelector('.nav-toggle');
        const navLinks = document.querySelector('.nav-links');

        navToggle.addEventListener('click', function() {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

      
        document.querySelectorAll('.dropbtn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    // Close all other dropdowns first
                    document.querySelectorAll('.dropdown-content').forEach(function(content) {
                        if (content !== this.nextElementSibling) {
                            content.classList.remove('active');
                        }
                    }.bind(this));
                    
                    // Toggle the clicked dropdown
                    const dropdownContent = this.nextElementSibling;
                    dropdownContent.classList.toggle('active');
                }
            });
        });

       
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const isClickInsideDropdown = e.target.closest('.dropdown');
                const isClickInsideNavToggle = e.target.closest('.nav-toggle');
                const isClickInsideNavLinks = e.target.closest('.nav-links');
                const isClickOnDropbtn = e.target.closest('.dropbtn');
                
                // If clicking outside navigation area, close everything
                if (!isClickInsideDropdown && !isClickInsideNavToggle && !isClickInsideNavLinks) {
                    navLinks.classList.remove('active');
                    navToggle.classList.remove('active');
                    document.querySelectorAll('.dropdown-content').forEach(function(content) {
                        content.classList.remove('active');
                    });
                }
                // If clicking inside dropdown but not on button, keep dropdown open
                else if (isClickInsideDropdown && !isClickOnDropbtn) {
                    // Do nothing - let dropdown stay open when clicking on links
                }
            }
        });

        // Add touch event support for better mobile experience
        document.querySelectorAll('.dropbtn').forEach(function(btn) {
            btn.addEventListener('touchstart', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    // Close all other dropdowns first
                    document.querySelectorAll('.dropdown-content').forEach(function(content) {
                        if (content !== this.nextElementSibling) {
                            content.classList.remove('active');
                        }
                    }.bind(this));
                    
                    // Toggle the clicked dropdown
                    const dropdownContent = this.nextElementSibling;
                    dropdownContent.classList.toggle('active');
                }
            });
        });

    
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                navLinks.classList.remove('active');
                navToggle.classList.remove('active');
                document.querySelectorAll('.dropdown-content').forEach(function(content) {
                    content.classList.remove('active');
                });
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                   
                    navLinks.classList.remove('active');
                    navToggle.classList.remove('active');
                }
            });
        });

       
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

       
        const contactForm = document.querySelector('.contact-form');
        contactForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('.submit-button');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitButton.disabled = true;
            
        
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 3000);
        });

        
        document.querySelectorAll('.cta-button, .submit-button').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>

