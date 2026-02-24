<?php
// courses.php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch all courses
$stmt = $pdo->query("SELECT * FROM courses WHERE status = 1 ORDER BY name");
$courses = $stmt->fetchAll();

// Group courses by category
$groupedCourses = [
    'undergraduate' => [],
    'diploma' => [],
    'professional' => []
];

foreach($courses as $course) {
    if(strpos($course['name'], 'B.A.') !== false || strpos($course['name'], 'B.Sc.') !== false || strpos($course['name'], 'B.Com') !== false) {
        $groupedCourses['undergraduate'][] = $course;
    } elseif(strpos($course['name'], 'D.Pharma') !== false || strpos($course['name'], 'D.El.Ed') !== false) {
        $groupedCourses['diploma'][] = $course;
    } else {
        $groupedCourses['professional'][] = $course;
    }
}
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(107, 0, 0, 0.9), rgba(139, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center; padding: 120px 0; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="color: #C9A227; font-size: 3.5rem; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Our Programs</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100" style="color: #FFFFFF; font-size: 1.3rem;">Choose from a wide range of undergraduate, diploma, and professional courses</p>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" style="color: #FFFFFF;">Home</a></li>
                <li class="breadcrumb-item active" style="color: #C9A227;" aria-current="page">Courses</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Course Categories -->
<section class="py-5">
    <div class="container">
        <!-- Category Tabs -->
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="btn-group" role="group">
                <button class="btn btn-maroon active" data-filter="all">All Programs</button>
                <button class="btn btn-maroon" data-filter="undergraduate">Undergraduate</button>
                <button class="btn btn-maroon" data-filter="diploma">Diploma</button>
                <button class="btn btn-maroon" data-filter="professional">Professional</button>
            </div>
        </div>

        <!-- Undergraduate Courses -->
        <?php if(!empty($groupedCourses['undergraduate'])): ?>
        <div class="course-category mb-5" data-category="undergraduate">
            <h2 class="section-title" data-aos="fade-up" style="margin-bottom: 40px;">
                <span style="background: #8B0000; color: #C9A227; padding: 10px 25px; border-radius: 50px; display: inline-block;">
                    <i class="fas fa-graduation-cap me-2"></i>Undergraduate Programs
                </span>
            </h2>
            
            <div class="row">
                <?php foreach($groupedCourses['undergraduate'] as $index => $course): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="course-card h-100">
                        <div class="course-header">
                            <h3><?php echo $course['name']; ?></h3>
                        </div>
                        <div class="course-body">
                            <div class="course-duration mb-3">
                                <i class="far fa-clock me-2" style="color: #C9A227;"></i>Duration: <?php echo $course['duration'] ?: '3 Years'; ?>
                            </div>
                            
                            <?php if($course['slug'] == 'ba'): ?>
                            <div class="specializations mb-3">
                                <strong style="color: #8B0000;">Specializations:</strong>
                                <div style="font-size: 0.9rem; margin-top: 8px;">
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Hindi</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">English</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Urdu</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Sociology</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Geography</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Home Science</span>
                                </div>
                            </div>
                            <?php elseif($course['slug'] == 'bsc'): ?>
                            <div class="specializations mb-3">
                                <strong style="color: #8B0000;">Specializations:</strong>
                                <div style="font-size: 0.9rem; margin-top: 8px;">
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Zoology</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Botany</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Chemistry</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Physics</span>
                                    <span class="badge me-1 mb-1" style="background: #F8F9FA; color: #6D0000; border: 1px solid #C9A227;">Mathematics</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <p class="mb-3"><?php echo substr($course['description'], 0, 100); ?>...</p>
                            <?php endif; ?>
                            
                            <div class="eligibility mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #C9A227;"></i>
                                <strong>Eligibility:</strong> <?php echo $course['eligibility'] ?: '10+2 or equivalent'; ?>
                            </div>
                            
                            <a href="<?php echo BASE_URL; ?>course/<?php echo $course['slug']; ?>" class="btn btn-maroon w-100">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Diploma Courses -->
        <?php if(!empty($groupedCourses['diploma'])): ?>
        <div class="course-category mb-5" data-category="diploma">
            <h2 class="section-title" data-aos="fade-up" style="margin-bottom: 40px;">
                <span style="background: #8B0000; color: #C9A227; padding: 10px 25px; border-radius: 50px; display: inline-block;">
                    <i class="fas fa-certificate me-2"></i>Diploma Programs
                </span>
            </h2>
            
            <div class="row">
                <?php foreach($groupedCourses['diploma'] as $index => $course): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="course-card h-100">
                        <div class="course-header">
                            <h3><?php echo $course['name']; ?></h3>
                        </div>
                        <div class="course-body">
                            <div class="course-duration mb-3">
                                <i class="far fa-clock me-2" style="color: #C9A227;"></i>Duration: <?php echo $course['duration'] ?: '2 Years'; ?>
                            </div>
                            
                            <?php if($course['slug'] == 'd-pharma'): ?>
                            <div class="highlight mb-3 p-2" style="background: #F8F9FA; border-left: 3px solid #C9A227;">
                                <i class="fas fa-trophy me-2" style="color: #8B0000;"></i>
                                <strong>Best Pharmacy College</strong>
                            </div>
                            <?php endif; ?>
                            
                            <p class="mb-3"><?php echo substr($course['description'], 0, 100); ?>...</p>
                            
                            <div class="eligibility mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #C9A227;"></i>
                                <strong>Eligibility:</strong> <?php echo $course['eligibility'] ?: 'As per norms'; ?>
                            </div>
                            
                            <a href="<?php echo BASE_URL; ?>course/<?php echo $course['slug']; ?>" class="btn btn-maroon w-100">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Professional Courses -->
        <?php if(!empty($groupedCourses['professional'])): ?>
        <div class="course-category mb-5" data-category="professional">
            <h2 class="section-title" data-aos="fade-up" style="margin-bottom: 40px;">
                <span style="background: #8B0000; color: #C9A227; padding: 10px 25px; border-radius: 50px; display: inline-block;">
                    <i class="fas fa-briefcase me-2"></i>Professional Programs
                </span>
            </h2>
            
            <div class="row">
                <?php foreach($groupedCourses['professional'] as $index => $course): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="course-card h-100">
                        <div class="course-header">
                            <h3><?php echo $course['name']; ?></h3>
                        </div>
                        <div class="course-body">
                            <div class="course-duration mb-3">
                                <i class="far fa-clock me-2" style="color: #C9A227;"></i>Duration: <?php echo $course['duration'] ?: '2-3 Years'; ?>
                            </div>
                            
                            <p class="mb-3"><?php echo substr($course['description'], 0, 100); ?>...</p>
                            
                            <div class="eligibility mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #C9A227;"></i>
                                <strong>Eligibility:</strong> <?php echo $course['eligibility'] ?: 'Graduation'; ?>
                            </div>
                            
                            <a href="<?php echo BASE_URL; ?>course/<?php echo $course['slug']; ?>" class="btn btn-maroon w-100">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Course Features -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Why Our <span>Courses Stand Out</span></h2>
        
        <div class="row mt-5">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                <div class="feature-box text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: 100%;">
                    <div class="feature-icon mb-3" style="width: 70px; height: 70px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-book-open fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h5 style="color: #6D0000;">Updated Curriculum</h5>
                    <p class="small">Industry-aligned syllabus with latest developments</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-box text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: 100%;">
                    <div class="feature-icon mb-3" style="width: 70px; height: 70px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-chalkboard-teacher fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h5 style="color: #6D0000;">Expert Faculty</h5>
                    <p class="small">Learn from experienced professors and industry experts</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: 100%;">
                    <div class="feature-icon mb-3" style="width: 70px; height: 70px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-flask fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h5 style="color: #6D0000;">Practical Training</h5>
                    <p class="small">Hands-on experience in modern laboratories</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-box text-center p-4" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: 100%;">
                    <div class="feature-icon mb-3" style="width: 70px; height: 70px; background: #8B0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-briefcase fa-2x" style="color: #C9A227;"></i>
                    </div>
                    <h5 style="color: #6D0000;">100% Placement</h5>
                    <p class="small">Dedicated placement assistance for all students</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admission Guide -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Admission Guide" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="section-title text-start" style="margin-bottom: 30px;">Admission <span>Guide 2026-27</span></h2>
                <p class="lead" style="color: #8B0000;">Simple steps to begin your journey</p>
                
                <div class="step-item d-flex mb-4">
                    <div class="step-num me-3" style="width: 40px; height: 40px; background: #C9A227; color: #6D0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                    <div>
                        <h5 style="color: #6D0000;">Choose Your Program</h5>
                        <p>Explore our courses and select the one that matches your career goals</p>
                    </div>
                </div>
                
                <div class="step-item d-flex mb-4">
                    <div class="step-num me-3" style="width: 40px; height: 40px; background: #C9A227; color: #6D0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                    <div>
                        <h5 style="color: #6D0000;">Check Eligibility</h5>
                        <p>Verify you meet the eligibility criteria for your chosen course</p>
                    </div>
                </div>
                
                <div class="step-item d-flex mb-4">
                    <div class="step-num me-3" style="width: 40px; height: 40px; background: #C9A227; color: #6D0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                    <div>
                        <h5 style="color: #6D0000;">Submit Application</h5>
                        <p>Fill the online application form with accurate details</p>
                    </div>
                </div>
                
                <div class="step-item d-flex mb-4">
                    <div class="step-num me-3" style="width: 40px; height: 40px; background: #C9A227; color: #6D0000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">4</div>
                    <div>
                        <h5 style="color: #6D0000;">Secure Your Seat</h5>
                        <p>Complete the admission process and pay fees</p>
                    </div>
                </div>
                
                <a href="#admissionModal" data-bs-toggle="modal" class="btn btn-gold btn-lg mt-3">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Course Comparison Table -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Quick Course <span>Comparison</span></h2>
        
        <div class="table-responsive" data-aos="fade-up">
            <table class="table table-bordered table-hover" style="background: white;">
                <thead style="background: #8B0000; color: #C9A227;">
                    <tr>
                        <th>Program</th>
                        <th>Duration</th>
                        <th>Eligibility</th>
                        <th>Career Options</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>B.A.</strong></td>
                        <td>3 Years</td>
                        <td>10+2 (Any Stream)</td>
                        <td>Civil Services, Teaching, Journalism</td>
                    </tr>
                    <tr>
                        <td><strong>B.Sc.</strong></td>
                        <td>3 Years</td>
                        <td>10+2 (Science)</td>
                        <td>Research, Industry, Teaching</td>
                    </tr>
                    <tr>
                        <td><strong>D.Pharma</strong></td>
                        <td>2 Years</td>
                        <td>10+2 (Science)</td>
                        <td>Pharmacist, Medical Representative</td>
                    </tr>
                    <tr>
                        <td><strong>B.Ed</strong></td>
                        <td>2 Years</td>
                        <td>Graduation</td>
                        <td>School Teacher, Educator</td>
                    </tr>
                    <tr>
                        <td><strong>LLB</strong></td>
                        <td>3 Years</td>
                        <td>Graduation</td>
                        <td>Lawyer, Legal Advisor</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, #8B0000, #6D0000);">
    <div class="container text-center">
        <h2 class="mb-4" style="color: #C9A227; font-size: 2.5rem;" data-aos="fade-up">Ready to Start Your Career?</h2>
        <p class="lead mb-4" style="color: white; font-size: 1.3rem;" data-aos="fade-up" data-aos-delay="100">Join Habibi Institute and shape your future with quality education</p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="#admissionModal" data-bs-toggle="modal" class="btn btn-gold btn-lg me-3 px-4">Apply Now</a>
            <a href="<?php echo BASE_URL; ?>contact" class="btn btn-outline-light btn-lg px-4">Contact Counselor</a>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Course category filtering
    $('.btn-group .btn').click(function() {
        var filter = $(this).data('filter');
        
        $('.btn-group .btn').removeClass('active');
        $(this).addClass('active');
        
        if(filter === 'all') {
            $('.course-category').fadeIn();
        } else {
            $('.course-category').each(function() {
                if($(this).data('category') === filter) {
                    $(this).fadeIn();
                } else {
                    $(this).fadeOut();
                }
            });
        }
    });
});
</script>

<style>
/* Courses Page Specific Styles */
.course-card {
    transition: all 0.3s ease;
    border: 1px solid #eee;
}

.course-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(139, 0, 0, 0.15);
    border-color: #C9A227;
}

.course-header {
    background: linear-gradient(135deg, #8B0000, #6D0000);
    padding: 20px;
    text-align: center;
}

.course-header h3 {
    color: #C9A227;
    margin: 0;
    font-size: 1.3rem;
}

.course-body {
    padding: 20px;
}

.course-duration {
    color: #6D0000;
    font-weight: 600;
}

.specializations .badge {
    font-weight: normal;
    padding: 5px 10px;
    transition: all 0.3s ease;
}

.specializations .badge:hover {
    background: #8B0000 !important;
    color: #C9A227 !important;
    border-color: #8B0000 !important;
}

.eligibility {
    font-size: 0.95rem;
    padding: 10px;
    background: #F8F9FA;
    border-radius: 5px;
}

.feature-box {
    transition: all 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(139, 0, 0, 0.1) !important;
}

.feature-box:hover .feature-icon {
    background: #C9A227 !important;
}

.feature-box:hover .feature-icon i {
    color: #8B0000 !important;
}

.feature-icon {
    transition: all 0.3s ease;
}

.btn-group .btn {
    margin: 0 5px;
    border-radius: 30px !important;
    padding: 10px 25px;
    border: 2px solid transparent;
}

.btn-group .btn.active {
    background: #C9A227;
    color: #6D0000;
    border-color: #8B0000;
}

.step-item {
    transition: all 0.3s ease;
    padding: 15px;
    border-radius: 10px;
}

.step-item:hover {
    background: #F8F9FA;
    transform: translateX(10px);
}

.step-num {
    transition: all 0.3s ease;
}

.step-item:hover .step-num {
    background: #8B0000 !important;
    color: #C9A227 !important;
}

.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.table thead th {
    border: none;
    padding: 15px;
}

.table tbody td {
    padding: 15px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #F8F9FA;
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn-group .btn {
        margin: 5px;
        font-size: 0.9rem;
        padding: 8px 15px;
    }
    
    .page-header h1 {
        font-size: 2.5rem !important;
    }
    
    .page-header .lead {
        font-size: 1.1rem !important;
    }
}

@media (max-width: 576px) {
    .section-title span {
        font-size: 1.5rem;
        padding: 8px 15px !important;
    }
    
    .course-header h3 {
        font-size: 1.1rem;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>