<?php
// course-detail.php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Get course slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if(empty($slug)) {
    header('Location: courses.php');
    exit();
}

// Fetch course details
$stmt = $pdo->prepare("SELECT * FROM courses WHERE slug = ? AND status = 1");
$stmt->execute([$slug]);
$course = $stmt->fetch();

if(!$course) {
    // Course not found - redirect to courses page
    header('Location: courses.php');
    exit();
}

// Fetch related courses (optional)
$stmt = $pdo->query("SELECT * FROM courses WHERE status = 1 AND id != {$course['id']} LIMIT 3");
$relatedCourses = $stmt->fetchAll();
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(107, 0, 0, 0.9), rgba(139, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;"><?php echo $course['name']; ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" style="color: #FFFFFF;">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>courses" style="color: #FFFFFF;">Courses</a></li>
                <li class="breadcrumb-item active" style="color: #C9A227;" aria-current="page"><?php echo $course['name']; ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Course Overview -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="section-title text-start" style="margin-bottom: 30px;">Course <span>Overview</span></h2>
                
                <div class="course-meta mb-4">
                    <span class="badge me-2" style="background: #8B0000; color: #C9A227; padding: 10px 20px; font-size: 1rem;">
                        <i class="far fa-clock me-2"></i>Duration: <?php echo $course['duration'] ?: '3 Years'; ?>
                    </span>
                    <span class="badge" style="background: #C9A227; color: #6D0000; padding: 10px 20px; font-size: 1rem;">
                        <i class="fas fa-graduation-cap me-2"></i>Eligibility: <?php echo $course['eligibility'] ?: 'As per university norms'; ?>
                    </span>
                </div>
                
                <div class="course-description mb-5">
                    <h4 style="color: #6D0000; margin-bottom: 20px;">About the Program</h4>
                    <p><?php echo nl2br($course['description'] ?: 'Comprehensive program designed to provide in-depth knowledge and practical skills in the field.'); ?></p>
                </div>
                
                <?php if($course['slug'] == 'ba'): ?>
                <div class="specializations mb-5">
                    <h4 style="color: #6D0000; margin-bottom: 20px;">Specializations Offered</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-book me-2" style="color: #8B0000;"></i> Hindi
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-book me-2" style="color: #8B0000;"></i> English
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-book me-2" style="color: #8B0000;"></i> Urdu
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-globe me-2" style="color: #8B0000;"></i> Sociology
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-mountain me-2" style="color: #8B0000;"></i> Geography
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-home me-2" style="color: #8B0000;"></i> Home Science
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif($course['slug'] == 'bsc'): ?>
                <div class="specializations mb-5">
                    <h4 style="color: #6D0000; margin-bottom: 20px;">Specializations Offered</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-dna me-2" style="color: #8B0000;"></i> Zoology
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-leaf me-2" style="color: #8B0000;"></i> Botany
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-flask me-2" style="color: #8B0000;"></i> Chemistry
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-atom me-2" style="color: #8B0000;"></i> Physics
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-calculator me-2" style="color: #8B0000;"></i> Mathematics
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($course['curriculum']): ?>
                <div class="curriculum mb-5">
                    <h4 style="color: #6D0000; margin-bottom: 20px;">Curriculum</h4>
                    <p><?php echo nl2br($course['curriculum']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($course['career_opportunities']): ?>
                <div class="career mb-5">
                    <h4 style="color: #6D0000; margin-bottom: 20px;">Career Opportunities</h4>
                    <p><?php echo nl2br($course['career_opportunities']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($course['fee_structure']): ?>
                <div class="fee mb-5">
                    <h4 style="color: #6D0000; margin-bottom: 20px;">Fee Structure</h4>
                    <p><?php echo nl2br($course['fee_structure']); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4" data-aos="fade-left">
                <!-- Quick Info Card -->
                <div class="card mb-4" style="border: 1px solid #C9A227; border-radius: 10px;">
                    <div class="card-header" style="background: #8B0000; color: #C9A227;">
                        <h5 class="mb-0">Quick Info</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> <strong>Duration:</strong> <?php echo $course['duration'] ?: '3 Years'; ?></li>
                            <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> <strong>Eligibility:</strong> <?php echo $course['eligibility'] ?: '10+2 or equivalent'; ?></li>
                            <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> <strong>Course Level:</strong> <?php echo strpos($course['name'], 'Diploma') !== false ? 'Diploma' : (strpos($course['name'], 'Bachelor') !== false ? 'Graduation' : 'Post Graduation'); ?></li>
                            <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> <strong>Admission Status:</strong> <span style="color: #8B0000; font-weight: bold;">Open 2026-27</span></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Admission CTA Card -->
                <div class="card mb-4" style="background: linear-gradient(135deg, #8B0000, #6D0000); border-radius: 10px;">
                    <div class="card-body text-center p-4">
                        <h5 style="color: #C9A227; margin-bottom: 20px;">Ready to Apply?</h5>
                        <p style="color: white; margin-bottom: 20px;">Take the first step towards your career in <?php echo $course['name']; ?></p>
                        <a href="#admissionModal" data-bs-toggle="modal" class="btn btn-gold btn-lg w-100 mb-3">Apply Now</a>
                        <a href="<?php echo BASE_URL; ?>admission" class="btn btn-outline-light w-100">View Admission Process</a>
                    </div>
                </div>
                
                <!-- Download Brochure -->
                <div class="card" style="border: 1px dashed #C9A227; border-radius: 10px;">
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x mb-3" style="color: #8B0000;"></i>
                        <h6 style="color: #6D0000;">Course Brochure</h6>
                        <a href="#" class="btn btn-sm" style="background: #C9A227; color: #6D0000;">Download PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose This Course -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Why Choose <span><?php echo $course['name']; ?> at Habibi?</span></h2>
        
        <div class="row mt-5">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="text-center p-4" style="background: white; border-radius: 10px; height: 100%;">
                    <i class="fas fa-chalkboard-teacher fa-3x mb-3" style="color: #8B0000;"></i>
                    <h5 style="color: #6D0000;">Expert Faculty</h5>
                    <p>Learn from experienced professors and industry experts</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center p-4" style="background: white; border-radius: 10px; height: 100%;">
                    <i class="fas fa-flask fa-3x mb-3" style="color: #8B0000;"></i>
                    <h5 style="color: #6D0000;">Modern Labs</h5>
                    <p>Well-equipped laboratories for practical learning</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center p-4" style="background: white; border-radius: 10px; height: 100%;">
                    <i class="fas fa-briefcase fa-3x mb-3" style="color: #8B0000;"></i>
                    <h5 style="color: #6D0000;">Placement Support</h5>
                    <p>Dedicated placement cell with 100% assistance</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Courses -->
<?php if(!empty($relatedCourses)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Related <span>Courses</span></h2>
        
        <div class="row mt-5">
            <?php foreach($relatedCourses as $related): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="course-card">
                    <div class="course-header">
                        <h5><?php echo $related['name']; ?></h5>
                    </div>
                    <div class="course-body">
                        <p class="course-duration"><i class="far fa-clock me-2"></i><?php echo $related['duration'] ?: '3 Years'; ?></p>
                        <a href="<?php echo BASE_URL; ?>course/<?php echo $related['slug']; ?>" class="btn btn-maroon w-100">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ Section for this Course -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Frequently Asked <span>Questions</span></h2>
        
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="courseFAQ">
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227;">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                What is the admission process for <?php echo $course['name']; ?>?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#courseFAQ">
                            <div class="accordion-body">
                                Admission is based on merit in the qualifying examination. Candidates need to fill the application form online or offline and submit required documents.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What are the career prospects after this course?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#courseFAQ">
                            <div class="accordion-body">
                                Graduates can pursue careers in education, research, government sectors, private industries, or higher studies (Masters/PhD).
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item mb-3" style="border: 1px solid #C9A227;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Is there any scholarship available?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#courseFAQ">
                            <div class="accordion-body">
                                Yes, merit-based scholarships and government scholarships are available for eligible students.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, #8B0000, #6D0000);">
    <div class="container text-center">
        <h2 class="mb-4" style="color: #C9A227;" data-aos="fade-up">Start Your Journey in <?php echo $course['name']; ?></h2>
        <p class="lead mb-4" style="color: white;" data-aos="fade-up" data-aos-delay="100">Limited seats available for 2026-27 academic session</p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="#admissionModal" data-bs-toggle="modal" class="btn btn-gold btn-lg me-3">Apply Now</a>
            <a href="<?php echo BASE_URL; ?>contact" class="btn btn-outline-light btn-lg">Contact Counselor</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>