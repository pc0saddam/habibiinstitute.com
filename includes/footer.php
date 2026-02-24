<?php
/**
 * Footer Template - Habibi Institute of Higher Education
 * Fully Responsive with Brand Image
 */
?>
<!-- Footer Section -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <!-- Logo & About Column -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-widget text-center text-md-start">
                    <a href="<?php echo BASE_URL; ?>" class="footer-logo d-inline-block mb-3">
                        <img src="<?php echo BASE_URL; ?>assets/img/logo.png" 
                             alt="<?php echo SITE_NAME; ?>" 
                             class="img-fluid"
                             onerror="this.src='https://via.placeholder.com/180x60/8B0000/FFFFFF?text=HABIBI'">
                        
                        <!-- ✅ Brand Name Added Here -->
                        <div class="footer-brand-text">
                            <span class="footer-brand-main">HABIBI INSTITUTE</span>
                            <span class="footer-brand-sub">OF HIGHER EDUCATION</span>
                        </div>
                    </a>
                    <p class="footer-about">
                        <?php echo SITE_NAME; ?> is committed to providing quality higher education 
                        with state-of-the-art facilities and experienced faculty.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links Column -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-widget">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>"><i class="fas fa-chevron-right"></i>Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about"><i class="fas fa-chevron-right"></i>About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>courses"><i class="fas fa-chevron-right"></i>Courses</a></li>
                        <li><a href="<?php echo BASE_URL; ?>admission"><i class="fas fa-chevron-right"></i>Admission</a></li>
                        <li><a href="<?php echo BASE_URL; ?>gallery"><i class="fas fa-chevron-right"></i>Gallery</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact"><i class="fas fa-chevron-right"></i>Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Courses Column -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h4 class="footer-title">Our Programs</h4>
                    <ul class="footer-links">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT slug, name FROM courses WHERE status = 1 ORDER BY name LIMIT 6");
                            while($course = $stmt->fetch()) {
                                echo '<li><a href="'.BASE_URL.'course/'.$course['slug'].'">'.
                                     '<i class="fas fa-chevron-right"></i>'.htmlspecialchars($course['name']).'</a></li>';
                            }
                        } catch (PDOException $e) {
                            // Fallback if no courses in database
                            echo '<li><a href="#"><i class="fas fa-chevron-right"></i>B.A.</a></li>';
                            echo '<li><a href="#"><i class="fas fa-chevron-right"></i>B.Sc.</a></li>';
                            echo '<li><a href="#"><i class="fas fa-chevron-right"></i>D.Pharma</a></li>';
                            echo '<li><a href="#"><i class="fas fa-chevron-right"></i>B.Ed</a></li>';
                            echo '<li><a href="#"><i class="fas fa-chevron-right"></i>LLB</a></li>';
                            echo '<li><a href="#"><i class="fas fa-chevron-right"></i>D.El.Ed</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            
            <!-- Contact Info Column -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h4 class="footer-title">Contact Info</h4>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Address:</strong><br>
                                <?php echo $settings['address'] ?? '2 km Dingerpur Kundarki Road, Vill. Guiller, Tehsil Bilari, Distt. Moradabad-244301 (U.P.)'; ?>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <div>
                                <strong>Phone:</strong><br>
                                <a href="tel:<?php echo $settings['phone_1'] ?? '9720229697'; ?>"><?php echo $settings['phone_1'] ?? '9720229697'; ?></a><br>
                                <a href="tel:<?php echo $settings['phone_2'] ?? '9410066786'; ?>"><?php echo $settings['phone_2'] ?? '9410066786'; ?></a>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email:</strong><br>
                                <a href="mailto:<?php echo $settings['email'] ?? 'institute.habibi@gmail.com'; ?>">
                                    <?php echo $settings['email'] ?? 'institute.habibi@gmail.com'; ?>
                                </a>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Office Hours:</strong><br>
                                Mon - Sat: 9:00 AM - 5:00 PM
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="copyright mb-0">
                        &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="footer-bottom-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/<?php echo $settings['whatsapp_number'] ?? '9720229697'; ?>" 
   class="whatsapp-float" 
   target="_blank"
   aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top" aria-label="Back to Top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Admission Modal -->
<div class="modal fade" id="admissionModal" tabindex="-1" aria-labelledby="admissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="admissionModalLabel">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Apply for Admission 2026-27
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="admissionForm" action="<?php echo BASE_URL; ?>submit-admission.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student Name *</label>
                            <input type="text" name="student_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Father's Name *</label>
                            <input type="text" name="father_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number *</label>
                            <input type="tel" name="mobile" class="form-control" pattern="[0-9]{10}" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Course *</label>
                            <select name="course_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT id, name FROM courses WHERE status = 1 ORDER BY name");
                                    while($course = $stmt->fetch()) {
                                        echo '<option value="'.$course['id'].'">'.htmlspecialchars($course['name']).'</option>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<option value="1">B.A.</option>';
                                    echo '<option value="2">B.Sc.</option>';
                                    echo '<option value="3">D.Pharma</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Qualification *</label>
                            <input type="text" name="qualification" class="form-control" placeholder="e.g., 12th Pass with 65%" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I confirm that the information provided is true and correct.
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>

<!-- Initialize AOS -->
<script>
    AOS.init({
        duration: 1000,
        once: true,
        disable: function() {
            return window.innerWidth < 768;
        }
    });
</script>

<!-- Back to Top Button Script -->
<script>
    const backToTop = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });
    
    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

<!-- Footer Styles -->
<style>
    .footer {
        background: #6D0000;
        color: #FFFFFF;
        padding: 60px 0 0;
        position: relative;
        overflow: hidden;
    }
    
    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #C9A227, transparent);
    }
    
    .footer-widget {
        margin-bottom: 30px;
    }
    
    .footer-logo {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
    }
    
    .footer-logo img {
        max-height: 60px;
        width: auto;
        transition: transform 0.3s ease;
    }
    
    .footer-logo:hover img {
        transform: scale(1.05);
    }
    
    .footer-brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    
    .footer-brand-main {
        color: #C9A227;
        font-weight: 700;
        font-size: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .footer-brand-sub {
        color: #FFFFFF;
        font-weight: 400;
        font-size: 0.8rem;
        letter-spacing: 0.3px;
    }
    
    .footer-about {
        color: #E6C55C;
        line-height: 1.8;
        margin: 20px 0;
        font-size: 0.95rem;
    }
    
    .footer-title {
        color: #C9A227;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }
    
    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 2px;
        background: #C9A227;
    }
    
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links li {
        margin-bottom: 12px;
    }
    
    .footer-links a {
        color: #FFFFFF;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .footer-links a i {
        font-size: 0.8rem;
        margin-right: 8px;
        color: #C9A227;
        transition: transform 0.3s ease;
    }
    
    .footer-links a:hover {
        color: #C9A227;
        transform: translateX(5px);
    }
    
    .footer-links a:hover i {
        transform: translateX(3px);
    }
    
    .social-links {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(201, 162, 39, 0.2);
        color: #C9A227;
        border-radius: 50%;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .social-link:hover {
        background: #C9A227;
        color: #6D0000;
        transform: translateY(-3px);
    }
    
    .contact-info {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .contact-info li {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .contact-info li i {
        font-size: 1.2rem;
        color: #C9A227;
        margin-top: 5px;
    }
    
    .contact-info li div {
        flex: 1;
    }
    
    .contact-info li strong {
        color: #C9A227;
        font-size: 0.9rem;
    }
    
    .contact-info a {
        color: #FFFFFF;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .contact-info a:hover {
        color: #C9A227;
    }
    
    .footer-bottom {
        margin-top: 40px;
        padding: 20px 0;
        border-top: 1px solid rgba(201, 162, 39, 0.3);
    }
    
    .copyright {
        color: #E6C55C;
        font-size: 0.9rem;
    }
    
    .footer-bottom-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 20px;
        justify-content: flex-end;
    }
    
    .footer-bottom-links a {
        color: #FFFFFF;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }
    
    .footer-bottom-links a:hover {
        color: #C9A227;
    }
    
    /* WhatsApp Float */
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: #C9A227;
        color: #6D0000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
        z-index: 999;
        text-decoration: none;
    }
    
    .whatsapp-float:hover {
        background: #E6C55C;
        color: #8B0000;
        transform: scale(1.1) translateY(-5px);
    }
    
    /* Back to Top */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        left: 30px;
        width: 50px;
        height: 50px;
        background: #6D0000;
        color: #C9A227;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }
    
    .back-to-top:hover {
        background: #8B0000;
        color: #E6C55C;
        transform: translateY(-5px);
    }
    
    /* Modal Styles */
    .modal-header {
        background: #8B0000;
        color: #C9A227;
        border: none;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    
    .modal-title {
        font-weight: 600;
    }
    
    .modal .form-control:focus,
    .modal .form-select:focus {
        border-color: #C9A227;
        box-shadow: 0 0 0 0.2rem rgba(201, 162, 39, 0.25);
    }
    
    .modal .btn-primary {
        background: #8B0000;
        border-color: #8B0000;
        color: #C9A227;
        padding: 10px 30px;
    }
    
    .modal .btn-primary:hover {
        background: #6D0000;
        border-color: #6D0000;
    }
    
    /* Responsive Footer */
    @media (max-width: 991px) {
        .footer {
            padding: 40px 0 0;
        }
        
        .footer-title {
            margin-bottom: 20px;
        }
        
        .footer-bottom-links {
            justify-content: center;
            margin-top: 15px;
        }
        
        .whatsapp-float {
            width: 50px;
            height: 50px;
            font-size: 25px;
            bottom: 20px;
            right: 20px;
        }
        
        .back-to-top {
            width: 40px;
            height: 40px;
            font-size: 16px;
            bottom: 20px;
            left: 20px;
        }
        
        .footer-logo {
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .footer-widget {
            text-align: center;
        }
        
        .footer-title::after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .social-links {
            justify-content: center;
        }
        
        .contact-info li {
            justify-content: center;
        }
        
        .contact-info li i {
            margin-top: 0;
        }
        
        .footer-bottom-links {
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .footer-brand-main {
            font-size: 1rem;
        }
        
        .footer-brand-sub {
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 576px) {
        .footer {
            padding: 30px 0 0;
        }
        
        .footer-logo img {
            max-height: 45px;
        }
        
        .footer-brand-main {
            font-size: 0.9rem;
        }
        
        .footer-brand-sub {
            font-size: 0.65rem;
        }
        
        .footer-about {
            font-size: 0.9rem;
        }
        
        .contact-info li {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 5px;
        }
        
        .footer-bottom-links {
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        
        .whatsapp-float {
            width: 45px;
            height: 45px;
            font-size: 22px;
        }
        
        .back-to-top {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
    }
    
    @media (max-width: 400px) {
        .footer-brand-text {
            display: none; /* Hide text on very small devices */
        }
    }
    
    /* Landscape Mode */
    @media (max-height: 500px) and (orientation: landscape) {
        .whatsapp-float,
        .back-to-top {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
    }
    
    /* Print Styles */
    @media print {
        .footer,
        .whatsapp-float,
        .back-to-top,
        .modal {
            display: none !important;
        }
    }
</style>
</body>
</html>