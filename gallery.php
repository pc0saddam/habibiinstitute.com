<?php
// gallery.php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch gallery images
$stmt = $pdo->query("SELECT * FROM gallery WHERE status = 1 ORDER BY sort_order, uploaded_at DESC");
$gallery = $stmt->fetchAll();

// Get unique categories
$categories = $pdo->query("SELECT DISTINCT category FROM gallery WHERE status = 1 AND category IS NOT NULL")->fetchAll();
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(rgba(107, 0, 0, 0.9), rgba(139, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="color: #C9A227; font-size: 3rem; margin-bottom: 20px;">Photo Gallery</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" style="color: #FFFFFF;">Home</a></li>
                <li class="breadcrumb-item active" style="color: #C9A227;" aria-current="page">Gallery</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Gallery Filter -->
<section class="py-4" style="background: #F8F9FA;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="gallery-filter btn-group" role="group">
                    <button class="btn btn-maroon active" data-filter="all">All</button>
                    <?php foreach($categories as $cat): ?>
                    <button class="btn btn-maroon" data-filter="<?php echo strtolower($cat['category']); ?>"><?php echo $cat['category']; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-5">
    <div class="container">
        <?php if(empty($gallery)): ?>
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x" style="color: #C9A227; margin-bottom: 20px;"></i>
            <h3>No Images in Gallery Yet</h3>
            <p>Check back soon for updates!</p>
        </div>
        <?php else: ?>
        <div class="row g-4 gallery-container">
            <?php foreach($gallery as $index => $image): ?>
            <div class="col-lg-4 col-md-6 gallery-item" data-category="<?php echo strtolower($image['category'] ?? 'general'); ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 5) * 100; ?>">
                <div class="gallery-card">
                    <img src="<?php echo BASE_URL . $image['image_path']; ?>" alt="<?php echo $image['title']; ?>" class="img-fluid">
                    <div class="gallery-overlay">
                        <h5><?php echo $image['title']; ?></h5>
                        <?php if($image['description']): ?>
                        <p><?php echo $image['description']; ?></p>
                        <?php endif; ?>
                        <button class="btn btn-gold btn-sm view-image" data-image="<?php echo BASE_URL . $image['image_path']; ?>" data-title="<?php echo $image['title']; ?>" data-desc="<?php echo $image['description']; ?>">
                            <i class="fas fa-search-plus"></i> View
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Campus Tour Section -->
<section class="py-5" style="background: linear-gradient(135deg, #8B0000 0%, #6D0000 100%);">
    <div class="container text-center">
        <h2 class="mb-4" style="color: #C9A227;" data-aos="fade-up">Virtual Campus Tour</h2>
        <p class="lead mb-4" style="color: white;" data-aos="fade-up" data-aos-delay="100">Experience our campus from anywhere</p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="#" class="btn btn-gold btn-lg" data-bs-toggle="modal" data-bs-target="#videoTourModal">
                <i class="fas fa-play-circle me-2"></i>Watch Video Tour
            </a>
        </div>
    </div>
</section>

<!-- Image View Modal -->
<div class="modal fade" id="imageViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #8B0000; color: #C9A227;">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" id="modalImage" class="img-fluid w-100" alt="">
                <div class="p-3" id="imageModalDesc" style="background: #F8F9FA;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Video Tour Modal -->
<div class="modal fade" id="videoTourModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #8B0000; color: #C9A227;">
                <h5 class="modal-title">Campus Video Tour</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Campus Tour" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Gallery filter
    $('.gallery-filter button').click(function() {
        var filterValue = $(this).data('filter');
        
        $('.gallery-filter button').removeClass('active');
        $(this).addClass('active');
        
        if(filterValue === 'all') {
            $('.gallery-item').fadeIn();
        } else {
            $('.gallery-item').each(function() {
                if($(this).data('category') === filterValue) {
                    $(this).fadeIn();
                } else {
                    $(this).fadeOut();
                }
            });
        }
    });
    
    // Image view modal
    $('.view-image').click(function() {
        var image = $(this).data('image');
        var title = $(this).data('title');
        var desc = $(this).data('desc');
        
        $('#modalImage').attr('src', image);
        $('#imageModalTitle').text(title);
        $('#imageModalDesc').text(desc);
        $('#imageViewModal').modal('show');
    });
});
</script>

<style>
.gallery-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    cursor: pointer;
    height: 250px;
}

.gallery-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-card:hover img {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    bottom: -100%;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(139, 0, 0, 0.95));
    color: white;
    padding: 20px;
    transition: bottom 0.3s ease;
}

.gallery-card:hover .gallery-overlay {
    bottom: 0;
}

.gallery-overlay h5 {
    color: #C9A227;
    margin-bottom: 5px;
}

.gallery-overlay p {
    font-size: 0.9rem;
    margin-bottom: 10px;
    color: white;
}

.gallery-filter .btn {
    margin: 0 5px;
    border-radius: 30px !important;
    padding: 8px 20px;
}

.gallery-filter .btn.active {
    background: #C9A227;
    color: #6D0000;
    border-color: #C9A227;
}
</style>

<?php require_once 'includes/footer.php'; ?>