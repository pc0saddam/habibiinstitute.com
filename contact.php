<?php
// contact.php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(107, 0, 0, 0.9), rgba(139, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1474&q=80'); background-size: cover; background-position: center; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;">Contact Us</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" style="color: #FFFFFF;">Home</a></li>
                <li class="breadcrumb-item active" style="color: #C9A227;" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <div class="contact-card text-center p-4" style="background: #F8F9FA; border-radius: 10px; border-bottom: 3px solid #C9A227; height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-phone-alt fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h4 style="color: #6D0000;">Phone Numbers</h4>
                    <p class="mb-1"><a href="tel:<?php echo $settings['phone_1']; ?>" style="color: #333; text-decoration: none;"><?php echo $settings['phone_1']; ?></a></p>
                    <p class="mb-1"><a href="tel:<?php echo $settings['phone_2']; ?>" style="color: #333; text-decoration: none;"><?php echo $settings['phone_2']; ?></a></p>
                    <p class="mb-1"><a href="tel:<?php echo $settings['phone_3']; ?>" style="color: #333; text-decoration: none;"><?php echo $settings['phone_3']; ?></a></p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-card text-center p-4" style="background: #F8F9FA; border-radius: 10px; border-bottom: 3px solid #C9A227; height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-envelope fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h4 style="color: #6D0000;">Email Address</h4>
                    <p class="mb-1"><a href="mailto:<?php echo $settings['email']; ?>" style="color: #333; text-decoration: none;"><?php echo $settings['email']; ?></a></p>
                    <p class="mb-1"><a href="mailto:admissions@habibiinstitute.edu" style="color: #333; text-decoration: none;">admissions@habibiinstitute.edu</a></p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-card text-center p-4" style="background: #F8F9FA; border-radius: 10px; border-bottom: 3px solid #C9A227; height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fas fa-map-marker-alt fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h4 style="color: #6D0000;">Address</h4>
                    <p><?php echo $settings['address']; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map & Contact Form -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <h2 class="section-title text-start" style="margin-bottom: 30px;">Get in <span>Touch</span></h2>
                
                <div class="map-container mb-4" style="border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                   <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d56050.70083018819!2d77.3029304!3d28.5947124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x390ae39dcc6a521b%3A0x5f9d246713905b77!2sHabibi%20Institute%20of%20Higher%20Education%2C%202%20Km%2C%20Kundarki%20-%20Dingarpur%20Rd%2C%20Gurer%2C%20Uttar%20Pradesh%20244301!3m2!1d28.7109244!2d78.7315823!5e0!3m2!1sen!2sin!4v1771872290352!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                
                <div class="business-hours p-4" style="background: white; border-radius: 10px;">
                    <h4 style="color: #8B0000; margin-bottom: 20px;">Office Hours</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Monday - Friday</span>
                        <span style="color: #6D0000; font-weight: 600;">9:00 AM - 5:00 PM</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Saturday</span>
                        <span style="color: #6D0000; font-weight: 600;">9:00 AM - 2:00 PM</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Sunday</span>
                        <span style="color: #6D0000; font-weight: 600;">Closed</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #8B0000; color: #C9A227; padding: 20px; border-radius: 10px 10px 0 0;">
                        <h3 class="mb-0">Send us a Message</h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="contactForm" action="submit-contact.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Your Name *</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Your Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Your Message *</label>
                                    <textarea name="message" class="form-control" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-gold btn-lg">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Frequently Asked <span>Questions</span></h2>
        
        <div class="row mt-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="accordion" id="faqAccordion1">
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227; border-radius: 5px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background: #F8F9FA; color: #6D0000;">
                                What are the admission requirements?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                Candidates must have passed 10+2 from a recognized board with minimum 45% marks (40% for reserved categories). Specific requirements vary by course.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227; border-radius: 5px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background: #F8F9FA; color: #6D0000;">
                                Is there any scholarship available?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                Yes, we offer merit-based scholarships and financial assistance to deserving students. Government scholarships are also available for SC/ST/OBC categories.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227; border-radius: 5px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background: #F8F9FA; color: #6D0000;">
                                What is the admission procedure?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                Admission is based on merit in the qualifying examination followed by counseling and document verification. Some courses may have entrance tests.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="accordion" id="faqAccordion2">
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227; border-radius: 5px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" style="background: #F8F9FA; color: #6D0000;">
                                Do you provide hostel facilities?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion2">
                            <div class="accordion-body">
                                Yes, we have separate hostels for boys and girls with all modern amenities, 24/7 security, and mess facilities.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227; border-radius: 5px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" style="background: #F8F9FA; color: #6D0000;">
                                What is the placement record?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
                            <div class="accordion-body">
                                Our placement cell has consistently achieved 85%+ placements with top recruiters in various sectors. D.Pharma students have 100% placement record.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227; border-radius: 5px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" style="background: #F8F9FA; color: #6D0000;">
                                Is transportation available?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
                            <div class="accordion-body">
                                Yes, we have a fleet of buses covering major routes in Moradabad and nearby areas for student convenience.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5" style="background: linear-gradient(135deg, #8B0000, #6D0000);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 style="color: #C9A227; margin-bottom: 20px;" data-aos="fade-up">Subscribe to Our Newsletter</h3>
                <p style="color: white; margin-bottom: 30px;" data-aos="fade-up" data-aos-delay="100">Get latest updates about admissions, events, and announcements</p>
                <form class="row g-2 justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Enter your email">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-gold btn-lg">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>