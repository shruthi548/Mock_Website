<?php
include 'config.php';
$admin = new Admin();
?>
		<!DOCTYPE html> 
		<html lang="en">
		<head>
		    <!-- Meta Tags -->
		    <meta charset="utf-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1">
		    <meta name="description" content="ExamYoddha - Your Ultimate Online Mock Test and Practice Platform. Prepare for competitive exams, academic tests, and professional certifications with our comprehensive practice tests and mock exams.">
		    <meta name="keywords" content="mock tests, practice exams, online testing, exam preparation, competitive exams, test series, ExamYoddha">
		    <meta name="author" content="ExamYoddha">
		    <meta name="robots" content="index, follow">
		    
		    <title>ExamYoddha - Mock Tests & Practice Platform</title>

		    <!-- Favicon -->
		    <link rel="shortcut icon" href="assets/img/favicon.png"> 
		    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">

		    <!-- Theme Settings Js -->
		    <script src="assets/js/theme-script.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		    <!-- Bootstrap CSS -->
		    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

		        <!-- Swiper CSS -->
		        <link rel="stylesheet" href="assets/plugins/swiper/css/swiper-bundle.min.css">
		    
		        <!-- Fontawesome CSS -->
		        <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
		        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

		        <!-- Select2 CSS -->
		        <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
		    
		        <!-- Slick CSS -->
		        <link rel="stylesheet" href="assets/plugins/slick/slick.css">
		        <link rel="stylesheet" href="assets/plugins/slick/slick-theme.css">
		    
		        <!-- Feathericon CSS -->
		        <link rel="stylesheet" href="assets/plugins/feather/feather.css">

		        <!-- Aos CSS -->
		        <link rel="stylesheet" href="assets/plugins/aos/aos.css">

		        <!-- Tabler Icon CSS -->
		        <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.css">

		        <!-- Iconsax CSS -->
		        <link rel="stylesheet" href="assets/css/iconsax.css">

		        <!-- Fancybox CSS -->
		        <link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">

		        <!-- Main CSS -->
		        <link rel="stylesheet" href="assets/css/style.css">
		    </head>
		    <body>
		        <!-- Main Wrapper -->
		        <div class="main-wrapper">
		            
		            <!-- Header -->
		            <?php include 'includes/header.php';?>
		            <!-- /Header -->
		    
		            <!-- banner -->
		            <section class="banner-section">
		                <img class="img-fluid d-none d-lg-flex banner-bg1" src="assets/img/bg/bg-15.png" alt="img">
		                <img class="img-fluid d-none d-lg-flex banner-bg2" src="assets/img/bg/bg-16.png" alt="img">
		                <img class="img-fluid d-none d-lg-flex banner-bg3" src="assets/img/bg/bg-17.png" alt="img">
		                <img class="img-fluid d-none d-lg-flex banner-bg4" src="assets/img/bg/bg-18.png" alt="img">
		                <div class="container">
		                    <div class="row align-items-center justify-content-between">
		                        <div class="col-xl-7 col-lg-7">
		                            <div class="banner-content pe-xxl-5">
		                                <span class="hero-title">Your Gateway to Exam Success</span>
		                                <h1 class="mb-4 text-white">Practice Makes Perfect with <span>ExamYoddha's</span> Mock Test Series</h1>
		                                <p class="fs-lg text-center text-md-start pb-2 pb-md-3 mb-4">Enhance your exam preparation with our comprehensive mock tests and practice papers designed by subject experts.</p>
		
		                                <div class="d-flex align-items-center gap-4 justify-content-lg-between justify-content-center flex-wrap">
		                                    <?php
		                                    // Fetch statistics
		                                    $total_tests = $admin->ret("SELECT COUNT(*) as count FROM test_names WHERE status = 1")->fetch(PDO::FETCH_ASSOC)['count'];
		                                    $total_subjects = $admin->ret("SELECT COUNT(DISTINCT subject) as count FROM test_names WHERE status = 1")->fetch(PDO::FETCH_ASSOC)['count'];
		                                    $total_categories = $admin->ret("SELECT COUNT(DISTINCT category) as count FROM test_names WHERE status = 1")->fetch(PDO::FETCH_ASSOC)['count'];
		                                    ?>
		                                    <div class="counter-item">
		                                        <div class="counter-icon flex-shrink-0">
		                                            <img src="assets/img/icons/icon-32.svg" alt="img">
		                                        </div>
		                                        <div class="count-content">
		                                            <h5 class="text-purple"><span class="count-digit"><?php echo $total_tests; ?></span>+</h5>
		                                            <p>Practice Tests</p>
		                                        </div>    
		                                    </div> 
		                                    <div class="counter-item">
		                                        <div class="counter-icon flex-shrink-0">
		                                            <img src="assets/img/icons/icon-33.svg" alt="img">
		                                        </div>
		                                        <div class="count-content">
		                                            <h5 class="text-skyblue"><span class="count-digit"><?php echo $total_subjects; ?></span>+</h5>
		                                            <p>Subjects</p>
		                                        </div>    
		                                    </div> 
		                                    <div class="counter-item">
		                                        <div class="counter-icon flex-shrink-0">
		                                            <img src="assets/img/icons/icon-34.svg" alt="img">
		                                        </div>
		                                        <div class="count-content">
		                                            <h5 class="text-success"><span class="count-digit"><?php echo $total_categories; ?></span>+</h5>
		                                            <p>Test Categories</p>
		                                        </div>    
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="col-xl-4 col-lg-5">
		                            <div class="banner-image">
		                                <div class="swiper swiper-slider-banner">
		                                    <div class="swiper-wrapper">
		                                        <?php
		                                        // Fetch featured tests
		                                        $featured_tests = $admin->ret("SELECT * FROM test_names WHERE status = 1 ORDER BY created_at DESC LIMIT 3");
		                                        while($test = $featured_tests->fetch(PDO::FETCH_ASSOC)) {
		                                        ?>
		                                        <div class="swiper-slide">
		                                            <div class="course-item-two course-item mb-0">
		                                                <div class="course-content">
		                                                    <div class="d-flex justify-content-between mb-2">
		                                                        <span class="badge badge-light rounded-pill bg-light d-inline-flex align-items-center fs-13 fw-medium">
		                                                            <?php echo $test['category']; ?>
		                                                        </span>
		                                                    </div>
		                                                    <h6 class="mb-2"><a href="test-details.php?id=<?php echo $test['id']; ?>"><?php echo $test['test_name']; ?></a></h6>
		                                                    <p class="mb-3"><?php echo substr($test['description'], 0, 100) . '...'; ?></p>
		                                                    <div class="d-flex align-items-center justify-content-between">
		                                                        <span class="text-secondary fs-14"><?php echo $test['subject']; ?></span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                        </div>
		                                        <?php } ?>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </section>
		            <!-- /banner -->
		            
		            <!-- benefits -->
		            <section class="benefit-section">
		                <div class="container">
		                    <div class="section-header text-center">
		                        <span class="fw-medium text-secondary text-decoration-underline mb-2 d-inline-block">Our Features</span>
		                        <h2>Why Choose ExamYoddha?</h2>
		                        <p>Comprehensive mock tests and practice papers to boost your exam preparation</p>
		                    </div>
		                    <div class="row">
		                        <div class="col-lg-4 col-md-6">
		                            <div class="card shadow-sm">
		                                <div class="card-body p-4">
		                                    <div class="p-4 rounded-pill bg-primary-transparent d-inline-flex">
		                                        <i class="isax isax-book-1 fs-24"></i>
		                                    </div>
		                                    <h5 class="mt-3 mb-1">Real Exam Experience</h5>
		                                    <p>Practice with exam-like interface and time constraints to simulate actual test conditions</p>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="col-lg-4 col-md-6">
		                            <div class="card shadow-sm">
		                                <div class="card-body p-4">
		                                    <div class="p-4 rounded-pill bg-secondary-transparent d-inline-flex">
		                                        <i class="isax isax-chart-26 fs-24"></i>
		                                    </div>
		                                    <h5 class="mt-3 mb-1">Detailed Analysis</h5>
		                                    <p>Get comprehensive performance reports and identify areas for improvement</p>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="col-lg-4 col-md-6">
		                            <div class="card shadow-sm">
		                                <div class="card-body p-4">
		                                    <div class="p-4 rounded-pill bg-skyblue-transparent d-inline-flex">
		                                        <i class="isax isax-teacher fs-24"></i>
		                                    </div>
		                                    <h5 class="mt-3 mb-1">Expert-Curated Tests</h5>
		                                    <p>Questions designed by subject matter experts following latest exam patterns</p>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </section>
		            <!-- /benefits -->
		
		            <!-- top categories -->
					<section class="top-courses-sec">
    <div class="container">
        <div class="section-header text-center">
            <span class="fw-medium text-secondary text-decoration-underline mb-2 d-inline-block">Test Series</span>
            <h2>Popular Test Series</h2>
            <p>Choose from our wide range of mock tests and practice papers</p>
        </div>
        <div class="row">
            <?php
            // Fetch popular exams
            $exams = $admin->ret("SELECT e.*, tn.test_name, tn.subject 
                                   FROM exams e 
                                   LEFT JOIN test_names tn ON e.test_name_id = tn.id 
                                   WHERE e.status = 1 
                                   ORDER BY e.created_at DESC 
                                   LIMIT 3");
            while($exam = $exams->fetch(PDO::FETCH_ASSOC)) {
                $image_path = !empty($exam['image']) ? $exam['image'] : 'assets/img/default-exam.jpg';
            ?>
            <div class="col-md-4 mb-4">
                <div class="categories-item categories-item-three h-100">
                    <div class="exam-image mb-3">
                        <img src="admin/controller/uploads/exams/<?php echo $image_path; ?>" 
                             alt="<?php echo htmlspecialchars($exam['exam_name']); ?>" 
                             class="img-fluid rounded w-100"
                             style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="exam-details">
                        <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($exam['exam_type']); ?></span>
                        <h6 class="title">
                            <a href="exam-details.php?id=<?php echo $exam['id']; ?>">
                                <?php echo htmlspecialchars($exam['exam_name']); ?>
                            </a>
                        </h6>
                        <div class="exam-meta">
                            <p class="text-muted mb-2">
                                <i class="fas fa-clock me-1"></i> Duration: <?php echo $exam['duration']; ?> mins
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-star me-1"></i> Total Marks: <?php echo $exam['total_marks']; ?>
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-check-circle me-1"></i> Pass Marks: <?php echo $exam['pass_marks']; ?>
                            </p>
                            <p class="text-muted">
                                <i class="fas fa-book me-1"></i> <?php echo htmlspecialchars($exam['test_name']); ?>
                            </p>
                        </div>
                        <p class="description mt-2"><?php echo substr(htmlspecialchars($exam['description']), 0, 100) . '...'; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
			<div class="col-md-12">
                <div class="text-center mt-4">
                    <a href="tests.php" class="btn btn-primary">View All Test Series</a>
                </div>
            </div>
        </div>
    </div>
</section>

		            <!-- /top categories -->
		
		            <!-- Footer -->
		            <?php include 'includes/footer.php';?>
		            <!-- /Footer -->    
		        </div>
		        <!-- /Main Wrapper -->
		
		        <!-- jQuery -->
		        <script src="assets/js/jquery-3.7.1.min.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		        <!-- Bootstrap Core JS -->
		        <script src="assets/js/bootstrap.bundle.min.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		        <!-- Select2 JS -->
		      	<script src="assets/plugins/select2/js/select2.min.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		        <!-- Slick Slider -->
		        <script src="assets/plugins/slick/slick.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		        
		        <!-- Swiper Slider -->
		        <script src="assets/plugins/swiper/js/swiper-bundle.min.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		        <!-- counterup JS -->
		        <script src="assets/js/counter.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		        <!-- Aos -->
		        <script src="assets/plugins/aos/aos.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		    
		        <!-- Fancybox JS -->
		        <script src="assets/plugins/fancybox/jquery.fancybox.min.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>	
		        <!-- Custom JS -->
		        <script src="assets/js/script.js" type="a987c1340ec837b06e49d3d7-text/javascript"></script>
		        
		    <script src="../../cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="a987c1340ec837b06e49d3d7-|49" defer></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"942a3c95ab9540e7","version":"2025.4.0-1-g37f21b1","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}' crossorigin="anonymous"></script>
		</body>

		<!-- Mirrored from dreamslms.dreamstechnologies.com/html/template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:50:30 GMT -->
		</html>