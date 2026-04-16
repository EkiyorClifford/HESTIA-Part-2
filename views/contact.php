<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - HESTIA Property Rentals</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/contact.css">
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/nav.php'; ?>

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
                <a href="#" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://wa.me/2348112177604?text=Hello%20%20Hestia%2C%20I%20would%20like%20to%20make%20an%20inquiry" target="_blank" rel="noopener noreferrer" style="background:#25D366;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../partials/hestia-easter-scripts.php'; ?>

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
