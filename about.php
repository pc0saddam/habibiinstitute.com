<?php
// about.php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(107, 0, 0, 0.9), rgba(139, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1486&q=80'); background-size: cover; background-position: center; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;">About Us</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" style="color: #FFFFFF;">Home</a></li>
                <li class="breadcrumb-item active" style="color: #C9A227;" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Institute Overview -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title text-start" style="margin-bottom: 30px;">Welcome to <span>Habibi Institute</span></h2>
                <p class="lead" style="color: #8B0000; font-weight: 600;">Excellence in Higher Education Since 2005</p>
                <p>Habibi Institute of Higher Education stands as a beacon of academic excellence in the Moradabad region. Established with a vision to provide quality education across diverse disciplines, we have grown into one of the most trusted educational institutions affiliated with MJPRU, NCTE, AICTE, JPCI, BCI, and BTE Lucknow.</p>
                <p>Our sprawling campus at Vill. Guiller, Tehsil Bilari, offers a perfect blend of traditional values and modern educational practices. With state-of-the-art infrastructure, experienced faculty, and a student-centric approach, we ensure holistic development of every learner.</p>
                
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="counter-box text-center p-3" style="background: #F8F9FA; border-radius: 10px; border-bottom: 3px solid #C9A227;">
                            <h3 class="counter" style="color: #8B0000; font-size: 2.5rem;">5000</h3>
                            <p>Alumni Network</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="counter-box text-center p-3" style="background: #F8F9FA; border-radius: 10px; border-bottom: 3px solid #C9A227;">
                            <h3 class="counter" style="color: #8B0000; font-size: 2.5rem;">150</h3>
                            <p>Expert Faculty</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="assets/img/abo.jpeg" style="height: 550px;">
            </div>
        </div>
    </div>
</section>

<!-- Our Journey Timeline -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our <span>Journey</span></h2>
        <div class="row mt-5">
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="0">
                <div class="timeline-card text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; font-weight: bold;">2005</div>
                    <h4 style="color: #6D0000;">Foundation</h4>
                    <p>Institute established with B.A. and B.Sc. programs</p>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="timeline-card text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; font-weight: bold;">2010</div>
                    <h4 style="color: #6D0000;">Expansion</h4>
                    <p>D.Pharma and B.Ed programs introduced</p>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="timeline-card text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; font-weight: bold;">2015</div>
                    <h4 style="color: #6D0000;">Recognition</h4>
                    <p>Affiliated to MJPRU and approved by AICTE</p>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="timeline-card text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: 100%;">
                    <div style="width: 80px; height: 80px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; font-weight: bold;">2023</div>
                    <h4 style="color: #6D0000;">Excellence</h4>
                    <p>Recognized as Best Pharmacy College in region</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chairman's Message -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Chairman" class="img-fluid rounded-circle mb-3" style="width: 250px; height: 250px; object-fit: cover; border: 5px solid #C9A227;">
                <h4 style="color: #8B0000;">Zeeshan Habibi</h4>
                <p style="color: #6D0000;">Founder & Chairman</p>
            </div>
            <div class="col-lg-8" data-aos="fade-left">
                <div style="background: #F8F9FA; padding: 40px; border-radius: 10px; border-left: 5px solid #C9A227;">
                    <i class="fas fa-quote-left" style="color: #C9A227; font-size: 2rem; margin-bottom: 20px;"></i>
                    <p class="lead" style="font-style: italic;">"Education is not just about degrees; it's about empowering young minds to become responsible citizens and future leaders. At Habibi Institute, we are committed to providing an environment where every student can discover their potential and excel in their chosen field."</p>
                    <p class="mb-0" style="color: #8B0000; font-weight: 600;">- Zeeshan Habibi</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Vision & Mission -->
<section class="py-5" style="background: linear-gradient(135deg, #8B0000 0%, #6D0000 100%);">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4" data-aos="fade-right">
                <div style="background: white; padding: 40px; border-radius: 10px; height: 100%;">
                    <i class="fas fa-eye" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;"></i>
                    <h3 style="color: #8B0000; margin-bottom: 20px;">Our Vision</h3>
                    <p>To be recognized as a premier institution of higher education that nurtures academic excellence, promotes research innovation, and develops socially responsible professionals who contribute to nation-building.</p>
                    <ul class="mt-3" style="list-style: none; padding: 0;">
                        <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Academic Excellence</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Research Innovation</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Social Responsibility</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-left">
                <div style="background: white; padding: 40px; border-radius: 10px; height: 100%;">
                    <i class="fas fa-bullseye" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;"></i>
                    <h3 style="color: #8B0000; margin-bottom: 20px;">Our Mission</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li class="mb-3"><i class="fas fa-arrow-right me-2" style="color: #C9A227;"></i> To deliver high-quality education across diverse fields including Arts, Science, Pharmacy, Law, and Education.</li>
                        <li class="mb-3"><i class="fas fa-arrow-right me-2" style="color: #C9A227;"></i> To create a supportive and inspiring environment for holistic student growth.</li>
                        <li class="mb-3"><i class="fas fa-arrow-right me-2" style="color: #C9A227;"></i> To ensure our graduates excel academically, socially and professionally.</li>
                        <li class="mb-3"><i class="fas fa-arrow-right me-2" style="color: #C9A227;"></i> To foster research and innovation that addresses societal challenges.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Infrastructure Highlights -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our <span>Infrastructure</span></h2>
        <div class="row mt-5">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in">
                <div class="infra-card text-center p-4" style="background: #F8F9FA; border-radius: 10px; transition: all 0.3s;">
                    <i class="fas fa-book fa-3x mb-3" style="color: #8B0000;"></i>
                    <h4 style="color: #6D0000;">Central Library</h4>
                    <p>30,000+ books, journals, and digital resources</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="infra-card text-center p-4" style="background: #F8F9FA; border-radius: 10px;">
                    <i class="fas fa-flask fa-3x mb-3" style="color: #8B0000;"></i>
                    <h4 style="color: #6D0000;">Science Labs</h4>
                    <p>Fully equipped Physics, Chemistry, and Biology labs</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="infra-card text-center p-4" style="background: #F8F9FA; border-radius: 10px;">
                    <i class="fas fa-capsules fa-3x mb-3" style="color: #8B0000;"></i>
                    <h4 style="color: #6D0000;">Pharmacy Lab</h4>
                    <p>Modern pharmaceutical laboratory with latest equipment</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="infra-card text-center p-4" style="background: #F8F9FA; border-radius: 10px;">
                    <i class="fas fa-laptop fa-3x mb-3" style="color: #8B0000;"></i>
                    <h4 style="color: #6D0000;">Computer Center</h4>
                    <p>100+ computers with high-speed internet</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accreditations & Affiliations -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our <span>Affiliations</span></h2>
        <div class="row mt-5">
            <div class="col-md-3 col-6 mb-4" data-aos="fade-up">
                <div class="text-center">
                    <img src="https://via.placeholder.com/150x150/8B0000/FFFFFF?text=MJPRU" alt="MJPRU" class="img-fluid mb-3" style="max-width: 100px;">
                    <h6 style="color: #6D0000;">MJPRU</h6>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <img src="https://via.placeholder.com/150x150/8B0000/FFFFFF?text=NCTE" alt="NCTE" class="img-fluid mb-3" style="max-width: 100px;">
                    <h6 style="color: #6D0000;">NCTE</h6>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <img src="https://via.placeholder.com/150x150/8B0000/FFFFFF?text=AICTE" alt="AICTE" class="img-fluid mb-3" style="max-width: 100px;">
                    <h6 style="color: #6D0000;">AICTE</h6>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <img src="https://via.placeholder.com/150x150/8B0000/FFFFFF?text=BCI" alt="BCI" class="img-fluid mb-3" style="max-width: 100px;">
                    <h6 style="color: #6D0000;">BCI</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our <span>Leadership</span></h2>
        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <div class="team-card text-center">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=688&q=80" alt="Principal" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #C9A227;">
                    <h4 style="color: #8B0000;">Dr Sunil Kumar</h4>
                    <p style="color: #6D0000;">Principal</p>
                    <p class="small">Ph.D. in Education, 25 years experience</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="team-card text-center">
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=761&q=80" alt="Dean" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #C9A227;">
                    <h4 style="color: #8B0000;">Dr shahroz alam</h4>
                    <p style="color: #6D0000;">Dean of Academics</p>
                    <p class="small">Ph.D. in Chemistry, 18 years experience</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="team-card text-center">
                    <img src="https://images.unsplash.com/photo-1566492031773-4f4e44671857?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Registrar" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #C9A227;">
                    <h4 style="color: #8B0000;">Dr. Mohd. Ali</h4>
                    <p style="color: #6D0000;">Registrar</p>
                    <p class="small">Ph.D. in Law, 15 years experience</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, #8B0000, #6D0000);">
    <div class="container text-center">
        <h2 class="mb-4" style="color: #C9A227;" data-aos="fade-up">Join Habibi Institute Today</h2>
        <p class="lead mb-4" style="color: white;" data-aos="fade-up" data-aos-delay="100">Be part of an institution that shapes futures and builds careers</p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="#admissionModal" data-bs-toggle="modal" class="btn btn-gold btn-lg me-3">Apply Now</a>
            <a href="<?php echo BASE_URL; ?>contact" class="btn btn-outline-light btn-lg">Contact Us</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>