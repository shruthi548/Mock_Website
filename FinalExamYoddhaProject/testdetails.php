<?php
include 'config.php';
$admin = new Admin();

// Get exam ID from URL
$exam_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch exam details with related information
$exam_query = $admin->ret("SELECT e.*, t.test_name, t.subject, t.description as test_description, 
                          ed.name as educator_name, ed.qualification, ed.specialization, ed.experience 
                          FROM exams e 
                          LEFT JOIN test_names t ON e.test_name_id = t.id 
                          LEFT JOIN educators ed ON e.educator_id = ed.id 
                          WHERE e.id = '$exam_id'");
$exam = $exam_query->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    echo "<script>alert('Test not found!'); window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($exam['exam_name']); ?> - ExamYoddha</title>
    
    <!-- Include your existing CSS files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    
    <!-- Rangeslider CSS -->
    <link rel="stylesheet" href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
    
    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="assets/plugins/feather/feather.css">
    
    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.css">
    
    <!-- Iconsax CSS -->
    <link rel="stylesheet" href="assets/css/iconsax.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <div class="main-wrapper">
        <!-- Include header -->
        <?php include 'includes/header.php'; ?>
        
        <!-- Breadcrumb -->
        <div class="breadcrumb-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-list">
                            <nav aria-label="breadcrumb" class="page-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item">Test Details</li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($exam['exam_name']); ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Details -->
        <section class="course-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-primary mb-4"><?php echo htmlspecialchars($exam['exam_name']); ?></h2>
                                
                                <!-- Test Image - Enhanced width and styling -->
                                <?php if($exam['image']): ?>
                                <div class="test-image mb-4">
                                    <div class="position-relative">
                                        <img src="admin/controller/uploads/exams/<?php echo $exam['image']; ?>" 
                                             alt="<?php echo htmlspecialchars($exam['exam_name']); ?>" 
                                             class="img-fluid rounded w-100 shadow"
                                             style="max-height: 400px; object-fit: cover;">
                                        <div class="overlay-gradient"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Enhanced Test Description -->
                                <div class="test-description mb-4 p-4 bg-light rounded">
                                    <h4 class="text-primary">
                                        <i class="fas fa-info-circle me-2"></i>Description
                                    </h4>
                                    <p class="lead mb-0"><?php echo nl2br(htmlspecialchars($exam['description'])); ?></p>
                                </div>
                                
                                <!-- Enhanced Test Instructions -->
                                <div class="test-instructions mb-4">
                                    <h4 class="text-primary mb-3">
                                        <i class="fas fa-clipboard-list me-2"></i>Instructions
                                    </h4>
                                    <div class="alert alert-info border-0 shadow-sm">
                                        <div class="instruction-content">
                                            <?php echo nl2br(htmlspecialchars($exam['instructions'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Test Information Sidebar -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Test Information
                                </h4>
                                <div class="test-info">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-hashtag me-2"></i>Exam Code:</span>
                                            <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($exam['exam_code']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-clock me-2"></i>Duration:</span>
                                            <span class="badge bg-info rounded-pill"><?php echo $exam['duration']; ?> minutes</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-star me-2"></i>Total Marks:</span>
                                            <span class="badge bg-success rounded-pill"><?php echo $exam['total_marks']; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-check-circle me-2"></i>Pass Marks:</span>
                                            <span class="badge bg-warning rounded-pill"><?php echo $exam['pass_marks']; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-book me-2"></i>Subject:</span>
                                            <span class="badge bg-secondary rounded-pill"><?php echo htmlspecialchars($exam['subject']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-tasks me-2"></i>Exam Type:</span>
                                            <span class="badge bg-dark rounded-pill"><?php echo htmlspecialchars($exam['exam_type']); ?></span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- Enhanced Educator Information -->
                                <div class="educator-info mt-4">
                                    <h4 class="card-title text-primary">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Educator Details
                                    </h4>
                                    <div class="educator-card p-3 bg-light rounded">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="educator-name">
                                                <h5 class="mb-1"><?php echo htmlspecialchars($exam['educator_name']); ?></h5>
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-graduation-cap me-2"></i>
                                                    <?php echo htmlspecialchars($exam['specialization']); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="educator-details">
                                            <p class="mb-2">
                                                <i class="fas fa-award me-2"></i>
                                                <strong>Qualification:</strong> 
                                                <?php echo htmlspecialchars($exam['qualification']); ?>
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-history me-2"></i>
                                                <strong>Experience:</strong> 
                                                <?php echo $exam['experience']; ?> years
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Enhanced Take Test Button -->
                                <div class="take-test-btn mt-4">
                                    <?php
                                    $question_count = $admin->ret("SELECT COUNT(*) as count FROM questions WHERE exam_id = '$exam_id'")->fetch(PDO::FETCH_ASSOC);
                                    
                                    if($question_count['count'] > 0) {
                                    ?>
                                        <a href="take-test.php?id=<?php echo $exam_id; ?>" 
                                           class="btn btn-primary btn-lg w-100 shadow-sm">
                                            <i class="fas fa-pencil-alt me-2"></i>Start Test Now
                                        </a>
                                    <?php } else { ?>
                                        <div class="alert alert-warning mb-0 shadow-sm">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Notice:</strong> Test questions are not available yet.
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- Include your existing JS files -->
    <script src="assets/js/jquery-3.7.1.min.js" type="0ff19c3ff50b41735776bc45-text/javascript"></script>
    <script src="assets/js/bootstrap.bundle.min.js" type="0ff19c3ff50b41735776bc45-text/javascript"></script>
    <script src="assets/plugins/select2/js/select2.min.js" type="0ff19c3ff50b41735776bc45-text/javascript"></script>
    <script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js" type="0ff19c3ff50b41735776bc45-text/javascript"></script>
    <script src="assets/plugins/ion-rangeslider/js/custom-rangeslider.js" type="0ff19c3ff50b41735776bc45-text/javascript"></script>
    <script src="assets/js/script.js" type="0ff19c3ff50b41735776bc45-text/javascript"></script>
    
</body>
</html>
<style>
	/* Test Details Page Enhancements */
.test-image {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

.overlay-gradient {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0.1));
}

.instruction-content {
    font-size: 1.1rem;
    line-height: 1.6;
}

.educator-card {
    transition: all 0.3s ease;
}

.educator-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.9rem;
    padding: 8px 12px;
}

.test-description, .test-instructions {
    transition: all 0.3s ease;
}

.test-description:hover, .test-instructions:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
</style>