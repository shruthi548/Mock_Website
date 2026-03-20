<?php
include 'config.php';
$admin=new Admin();
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Exam Portal - Online Examination System">
    <meta name="keywords" content="Exam portal, online exam, test, assessment">
    <meta name="author" content="ExamPortal">
    <meta name="robots" content="index, follow">
    
    <title>Exam Portal | Online Assessment System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.png"> 
    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">

    <!-- Theme Settings Js -->
    <script src="assets/js/theme-script.js"></script>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    
    <!-- Rangeslider CSS -->
    <link rel="stylesheet" href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="assets/plugins/feather/feather.css">

    <!-- Iconsax CSS -->
    <link rel="stylesheet" href="assets/css/iconsax.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
		<?php include 'includes/header.php';?>
        <!-- /Header -->

        <!-- Breadcrumb -->
        <div class="breadcrumb-bar text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <h2 class="breadcrumb-title mb-2">Available Exams</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Exam Grid</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Breadcrumb -->
        
        <!-- Search Section -->
        <div class="search-section py-4 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="search-box">
                            <div class="input-group">
                                <input type="text" id="searchExam" class="form-control form-control-lg" placeholder="Search exams by name, subject or educator...">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Exams -->
        <section class="course-content py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row" id="examGrid">
                            <?php 
                            $exams = $admin->ret("SELECT e.*, t.subject, ed.name as educator_name, ed.image as educator_image 
                                               FROM exams e 
                                               LEFT JOIN test_names t ON e.test_name_id = t.id
                                               LEFT JOIN educators ed ON e.educator_id = ed.id
                                               WHERE e.status = 1 
                                               ORDER BY e.created_at DESC");
                            
                            while($exam = $exams->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div class="col-xl-4 col-lg-6 col-md-6 mb-4 exam-card">
                                <div class="course-item-two course-item mx-0 h-100 shadow-sm rounded overflow-hidden">
                                    <div class="course-img position-relative">
                                        <a href="testdetails.php?id=<?php echo $exam['id']; ?>">
                                            <?php if(!empty($exam['image'])): ?>
                                                <img src="admin/controller/uploads/exams/<?php echo $exam['image']; ?>" alt="<?php echo $exam['exam_name']; ?>" class="img-fluid w-100">
                                            <?php else: ?>
                                                <img src="assets/img/course/course-01.jpg" alt="Default" class="img-fluid w-100">
                                            <?php endif; ?>
                                        </a>
                                        <div class="exam-type-badge position-absolute top-0 start-0 m-3">
                                            <?php if($exam['exam_type'] == 'Practice'): ?>
                                                <span class="badge bg-success px-3 py-2">Practice</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary px-3 py-2"><?php echo $exam['exam_type']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="course-content p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                                <?php echo $exam['subject']; ?>
                                            </span>
                                            <span class="text-primary fw-medium">
                                                <?php echo $exam['educator_name'];?>
                                            </span>
                                        </div>
                                        <h5 class="card-title mb-3">
                                            <a href="testdetails.php?id=<?php echo $exam['id']; ?>" class="text-dark">
                                                <?php echo $exam['exam_name']; ?>
                                            </a>
                                        </h5>
                                        <div class="exam-info mb-3">
                                            <div class="d-flex align-items-center text-muted mb-2">
                                                <i class="fas fa-clock me-2"></i>
                                                <span><?php echo $exam['duration']; ?> minutes</span>
                                            </div>
                                            <div class="d-flex align-items-center text-muted mb-2">
                                                <i class="fas fa-star me-2"></i>
                                                <span>Total Marks: <?php echo $exam['total_marks']; ?></span>
                                            </div>
                                            <div class="d-flex align-items-center text-muted">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <span>Pass Marks: <?php echo $exam['pass_marks']; ?></span>
                                            </div>
                                        </div>
                                        <a href="testdetails.php?id=<?php echo $exam['id']; ?>" 
                                           class="btn btn-primary w-100 py-2">
                                            Start Exam <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if($exams->rowCount() == 0): ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center py-4">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <p class="mb-0">No exams found. Please check back later.</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <!-- Rangeslider JS -->
    <script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="assets/plugins/ion-rangeslider/js/custom-rangeslider.js"></script>

    
    <!-- Sticky Sidebar JS -->
    <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
    <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>

    <!-- Search functionality -->
    <script>
    $(document).ready(function() {
        $("#searchExam").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".exam-card").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });

            // Show/hide no results message
            var visibleCards = $(".exam-card:visible").length;
            if (visibleCards === 0) {
                if ($("#noResults").length === 0) {
                    $("#examGrid").append(`
                        <div id="noResults" class="col-12">
                            <div class="alert alert-info text-center py-4">
                                <i class="fas fa-search fa-2x mb-3"></i>
                                <p class="mb-0">No exams found matching your search.</p>
                            </div>
                        </div>
                    `);
                }
            } else {
                $("#noResults").remove();
            }
        });
    });
    </script>
</body>
</html>
