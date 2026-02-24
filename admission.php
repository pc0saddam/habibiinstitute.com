<?php
// admission.php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch courses for dropdown
$courses = $pdo->query("SELECT * FROM courses WHERE status = 1 ORDER BY name")->fetchAll();
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(107, 0, 0, 0.9), rgba(139, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;">Admission 2026-27</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" style="color: #FFFFFF;">Home</a></li>
                <li class="breadcrumb-item active" style="color: #C9A227;" aria-current="page">Admission</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Admission Process -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title text-start" style="margin-bottom: 30px;">Admission <span>Process</span></h2>
                <p class="lead" style="color: #8B0000;">Simple & Transparent Admission Procedure</p>
                
                <div class="process-steps mt-4">
                    <div class="step-item d-flex mb-4">
                        <div class="step-number" style="width: 50px; height: 50px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-right: 20px;">1</div>
                        <div>
                            <h4 style="color: #6D0000;">Fill Application Form</h4>
                            <p>Complete the online application form with accurate personal and academic details.</p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex mb-4">
                        <div class="step-number" style="width: 50px; height: 50px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-right: 20px;">2</div>
                        <div>
                            <h4 style="color: #6D0000;">Document Verification</h4>
                            <p>Submit required documents for verification (Original + Self-attested copies).</p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex mb-4">
                        <div class="step-number" style="width: 50px; height: 50px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-right: 20px;">3</div>
                        <div>
                            <h4 style="color: #6D0000;">Merit List & Interview</h4>
                            <p>Shortlisted candidates will be called for counseling and interview.</p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex mb-4">
                        <div class="step-number" style="width: 50px; height: 50px; background: #8B0000; color: #C9A227; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; margin-right: 20px;">4</div>
                        <div>
                            <h4 style="color: #6D0000;">Fee Payment</h4>
                            <p>Pay the admission fees to confirm your seat in the desired program.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-4" style="background: #F8F9FA; border-left: 4px solid #C9A227; border-radius: 5px;">
                    <h5 style="color: #8B0000;">Important Dates</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-calendar-alt me-2" style="color: #C9A227;"></i> Application Start: January 1, 2026</li>
                        <li class="mb-2"><i class="fas fa-calendar-alt me-2" style="color: #C9A227;"></i> Last Date: March 31, 2026</li>
                        <li class="mb-2"><i class="fas fa-calendar-alt me-2" style="color: #C9A227;"></i> Merit List: April 15, 2026</li>
                        <li class="mb-2"><i class="fas fa-calendar-alt me-2" style="color: #C9A227;"></i> Classes Begin: July 1, 2026</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="card-header" style="background: #8B0000; color: #C9A227; padding: 20px; border-radius: 10px 10px 0 0;">
                        <h3 class="mb-0">Apply for Admission 2026-27</h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="admissionFullForm" action="submit-admission.php" method="POST">
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
                                    <label class="form-label">Mobile *</label>
                                    <input type="tel" name="mobile" class="form-control" pattern="[0-9]{10}" required>
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
                                        <?php foreach($courses as $course): ?>
                                        <option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
                                        <?php endforeach; ?>
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
                            <div class="text-center">
                                <button type="submit" class="btn btn-gold btn-lg">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Eligibility Criteria -->
<section class="py-5" style="background: #F8F9FA;">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Eligibility <span>Criteria</span></h2>
        <div class="row mt-5">
            <?php foreach($courses as $course): ?>
            <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
                <div class="card h-100" style="border: 1px solid #C9A227;">
                    <div class="card-header" style="background: #8B0000; color: #C9A227;">
                        <h5 class="mb-0"><?php echo $course['name']; ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Duration:</strong> <?php echo $course['duration']; ?></p>
                        <p><strong>Eligibility:</strong> <?php echo $course['eligibility'] ?: 'Check with admission office'; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Required Documents -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title text-start" style="margin-bottom: 30px;">Required <span>Documents</span></h2>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> 10th Marksheet & Certificate</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> 12th Marksheet & Certificate</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Graduation Marksheets (for PG courses)</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Transfer Certificate (TC)</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Character Certificate</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Caste Certificate (if applicable)</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Passport Size Photographs (6 copies)</li>
                    <li class="list-group-item"><i class="fas fa-check-circle me-2" style="color: #C9A227;"></i> Aadhar Card</li>
                </ul>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Documents" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>