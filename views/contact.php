<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - HESTIA Property Rentals</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: linear-gradient(90deg, #1A0F1E 0%, #5A2E55 100%) !important;
            box-shadow: 0 4px 15px rgba(26, 15, 30, 0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #fff !important;
            font-size: 1.8rem;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            transition: all 0.3s ease;
            margin: 0 5px;
            font-weight: 500;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: #fff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        main {
            flex-grow: 1;
        }

        .page-header {
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 30%, #8C3E2C 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }

        .page-header p {
            font-size: 1.15rem;
            font-weight: 300;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-info-box {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
            border-left: 5px solid #C44536;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            gap: 20px;
        }

        .contact-info-item:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #C44536 0%, #E67E51 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .contact-text h3 {
            color: #1A0F1E;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .contact-text p {
            color: #666;
            margin: 0;
            line-height: 1.6;
        }

        .contact-text a {
            color: #C44536;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .contact-text a:hover {
            color: #E67E51;
        }

        .form-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
        }

        .form-section h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1A0F1E;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .form-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #1A0F1E;
            margin-bottom: 10px;
            display: block;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #C44536;
            box-shadow: 0 0 0 0.2rem rgba(196, 69, 54, 0.15);
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border: none;
            padding: 14px 40px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(198, 69, 54, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #E67E51 0%, #C44536 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(198, 69, 54, 0.6);
            color: white;
        }

        .map-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
        }

        .map-section h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1A0F1E;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .map-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .map-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            height: 450px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .faq-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 50px;
        }

        .faq-section h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #1A0F1E;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .faq-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .faq-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #C44536;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 4px 15px rgba(196, 69, 54, 0.2);
        }

        .faq-item h4 {
            color: #1A0F1E;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-item h4 i {
            color: #C44536;
            transition: transform 0.3s ease;
        }

        .faq-item.active h4 i {
            transform: rotate(180deg);
        }

        .faq-answer {
            color: #666;
            line-height: 1.7;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
        }

        .footer {
            background: linear-gradient(90deg, #1A0F1E 0%, #2C1B2E 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        }

        .footer p {
            margin: 0;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #C44536 0%, #E67E51 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(196, 69, 54, 0.4);
        }

        .response-time {
            background: linear-gradient(135deg, #fce4ec 0%, #f3e5f5 100%);
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid #C44536;
            margin-top: 20px;
            color: #666;
            font-size: 0.95rem;
        }

        .response-time i {
            color: #C44536;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .form-section {
                padding: 25px;
            }

            .contact-info-item {
                flex-direction: column;
                gap: 10px;
            }

            .map-container {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="properties.php">Browse Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Login / Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-envelope me-2"></i>Get In Touch</h1>
            <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container py-5">
        <!-- Contact Information -->
        <div class="contact-info-box">
            <div class="row">
                <div class="col-md-4 p-3">
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Phone</h3>
                            <p><a href="tel:+234811217760">+234 811 2177 604</a></p>
                            <p style="font-size: 0.9rem; color: #999;">Mon-Sat, 9 AM to 6 PM</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-3">
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Email</h3>
                            <p><a href="mailto:info@hestia.com">info@hestia.com</a></p>
                            <p><a href="mailto:support@hestia.com">support@hestia.com</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-3">
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Address</h3>
                            <p>123 Premium Street<br>City Center, Lagos<br>Nigeria</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="form-section">
                    <h2><i class="fas fa-comments me-2"></i>Send us a Message</h2>
                    
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required placeholder="Your full name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required placeholder="your@email.com">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+234 (0) 123 456 7890">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <select id="subject" name="subject" required>
                                <option value="">-- Select Subject --</option>
                                <option value="rental">Rental Inquiry</option>
                                <option value="property">Property Question</option>
                                <option value="support">Technical Support</option>
                                <option value="feedback">Feedback</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required placeholder="Tell us more about your inquiry..."></textarea>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i>Send Message
                        </button>

                        <div class="response-time">
                            <i class="fas fa-clock"></i>
                            We typically respond within 2-4 hours during business hours
                        </div>
                    </form>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="col-lg-6">
                <div class="form-section">
                    <h2><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</h2>
                    
                    <div class="faq-item active">
                        <h4>
                            How long does it take to book a property?
                            <i class="fas fa-chevron-down"></i>
                        </h4>
                        <div class="faq-answer">
                            <p>Once you've selected a property and submitted your application, our verification process typically takes 2-3 business days. Approved applications can be finalized within hours.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <h4>
                            What documents do I need to rent a property?
                            <i class="fas fa-chevron-down"></i>
                        </h4>
                        <div class="faq-answer">
                            <p>You'll need a valid ID, proof of income, and employment verification. We may also request bank statements and references from previous landlords for verification purposes.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <h4>
                            Are all properties verified on HESTIA?
                            <i class="fas fa-chevron-down"></i>
                        </h4>
                        <div class="faq-answer">
                            <p>Yes! Every property on our platform undergoes rigorous verification. Our team personally inspects each property to ensure quality, authenticity, and adherence to our standards.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <h4>
                            What is your cancellation policy?
                            <i class="fas fa-chevron-down"></i>
                        </h4>
                        <div class="faq-answer">
                            <p>Cancellations made more than 7 days before the booking start date receive a full refund. Cancellations within 7 days may be subject to a small fee. Please check your specific booking terms.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <h4>
                            How do I add a property to my wishlist?
                            <i class="fas fa-chevron-down"></i>
                        </h4>
                        <div class="faq-answer">
                            <p>Simply click the heart icon on any property listing to add it to your wishlist. You can view all saved properties in your Wishlist section. No account required to browse!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <h2><i class="fas fa-map me-2"></i>Visit Our Office</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.8293635885577!2d3.8367437!3d6.5243801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf53280e7745d%3A0x4d8f8f8f8f8f8f8f!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1704091234567" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <p style="margin-top: 20px; color: #666; line-height: 1.8;">
                <strong>123 Premium Street, City Center, Lagos, Nigeria</strong><br>
                Open Monday to Saturday, 9 AM to 6 PM (WAT)<br>
                Sunday: Closed
            </p>
        </div>

        <!-- Additional Contact Methods -->
        <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); text-align: center;">
            <h2 style="color: #1A0F1E; font-weight: 800; margin-bottom: 30px;">Connect With Us</h2>
            <p style="color: #666; margin-bottom: 25px; font-size: 1.05rem;">Follow our social media channels for updates, property listings, and exclusive offers</p>
            <div class="social-links" style="justify-content: center;">
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2026 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FAQ Toggle Script -->
    <script>
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', function() {
                // Close other items
                document.querySelectorAll('.faq-item').forEach(other => {
                    if (other !== this) {
                        other.classList.remove('active');
                    }
                });
                // Toggle current item
                this.classList.toggle('active');
            });
        });

        // Contact Form Handler
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // You can add form submission logic here
            alert('Thank you for your message! We will get back to you shortly.');
            this.reset();
        });
    </script>
</body>
</html>
