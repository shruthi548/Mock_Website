<?php
include 'config.php';
$admin = new Admin();

// Get exam ID from URL
$exam_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch exam details
$exam_query = $admin->ret("SELECT e.*, t.test_name, t.subject 
                          FROM exams e 
                          LEFT JOIN test_names t ON e.test_name_id = t.id 
                          WHERE e.id = '$exam_id'");
$exam = $exam_query->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    echo "<script>alert('Test not found!'); window.location.href='index.php';</script>";
    exit;
}

// Check if difficulty level is selected
$selected_difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Test - <?php echo htmlspecialchars($exam['exam_name']); ?></title>
    
    <!-- Include your CSS files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Enhanced Custom Styling -->
    <style>
        /* General Styles */
        body {
            background-color: #f8f9fa;
        }
        
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Breadcrumb Enhancement */
        .breadcrumb-bar {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .breadcrumb-bar .breadcrumb {
            margin: 0;
            background: transparent;
        }
        
        .breadcrumb-item a, .breadcrumb-item.active {
            color: #fff;
            text-decoration: none;
        }
        
        .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Difficulty Selection Cards */
        .difficulty-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .difficulty-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .difficulty-card .card-body {
            padding: 2rem;
        }
        
        /* Question Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .question-header {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: -1rem -1rem 1rem -1rem;
        }
        
        .question-text {
            font-size: 1.1rem;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        /* Options Enhancement */
        .options-list {
            padding: 0.5rem;
        }
        
        .form-check {
            padding: 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .form-check:hover {
            background-color: #f8f9fa;
        }
        
        .form-check-input {
            cursor: pointer;
        }
        
        .form-check-label {
            cursor: pointer;
            padding-left: 0.5rem;
            color: #2c3e50;
        }
        
        /* Timer Section */
        .timer-section {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 2rem;
        }
        
        #timer {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc3545;
            margin: 1rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Submit Button */
        .btn-primary {
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }
        
        /* Test Information Card */
        .list-group-item {
            border: none;
            padding: 1rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .list-group-item:not(:last-child) {
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Badge Enhancement */
        .badge {
            padding: 0.6rem 1rem;
            font-weight: 500;
            border-radius: 8px;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .difficulty-card {
                margin-bottom: 1rem;
            }
            
            .timer-section {
                margin-top: 1rem;
            }
            
            #timer {
                font-size: 2rem;
            }
        }
    </style>
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
                                    <li class="breadcrumb-item"><a href="testdetails.php?id=<?php echo $exam_id; ?>">Test Details</a></li>
                                    <li class="breadcrumb-item active">Take Test</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Section -->
        <section class="course-content">
            <div class="container">
                <?php if(empty($selected_difficulty)) { ?>
                    <!-- Difficulty Selection -->
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-center mb-4">Select Difficulty Level</h3>
                                    <form method="POST" action="">
                                        <div class="difficulty-options">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="card difficulty-card">
                                                        <div class="card-body text-center">
                                                            <i class="fas fa-star-half-alt fa-3x text-success mb-3"></i>
                                                            <h5>Easy</h5>
                                                            <p class="text-muted">Basic level questions</p>
                                                            <button type="submit" name="difficulty" value="Easy" class="btn btn-outline-success w-100">Select</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card difficulty-card">
                                                        <div class="card-body text-center">
                                                            <i class="fas fa-star fa-3x text-warning mb-3"></i>
                                                            <h5>Intermediate</h5>
                                                            <p class="text-muted">Moderate complexity</p>
                                                            <button type="submit" name="difficulty" value="Intermediate" class="btn btn-outline-warning w-100">Select</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card difficulty-card">
                                                        <div class="card-body text-center">
                                                            <i class="fas fa-star-half-alt fa-3x text-danger mb-3"></i>
                                                            <h5>Hard</h5>
                                                            <p class="text-muted">Advanced level</p>
                                                            <button type="submit" name="difficulty" value="Hard" class="btn btn-outline-danger w-100">Select</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <!-- Test Questions -->
                    <div class="row">
                        <div class="col-lg-9">
                            <form id="testForm" method="POST" action="submit-test.php">
                                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                <input type="hidden" name="difficulty" value="<?php echo $selected_difficulty; ?>">
                                <input type="hidden" name="start_time" value="<?php echo time(); ?>">
                                
                                <?php
                                // Fetch questions for selected difficulty
                                $questions = $admin->ret("SELECT * FROM questions 
                                                        WHERE exam_id = '$exam_id' 
                                                        AND difficulty = '$selected_difficulty' 
                                                        ORDER BY question_number ASC");
                                
                                $question_count = 1;
                                while($question = $questions->fetch(PDO::FETCH_ASSOC)) {
                                    $options = json_decode($question['options'], true);
                                ?>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="question-header d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0">Question <?php echo $question_count; ?></h5>
                                            <span class="badge bg-primary">Marks: <?php echo $question['marks']; ?></span>
                                        </div>
                                        <p class="question-text mb-4"><?php echo htmlspecialchars($question['question_text']); ?></p>
                                        
                                        <div class="options-list">
                                            <?php if($question['question_type'] == 'Multiple Choice') { 
                                                foreach($options as $index => $option) { ?>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" 
                                                           name="answer[<?php echo $question['id']; ?>]" 
                                                           id="q<?php echo $question['id']; ?>_<?php echo $index; ?>" 
                                                           value="<?php echo htmlspecialchars($option); ?>">
                                                    <label class="form-check-label" for="q<?php echo $question['id']; ?>_<?php echo $index; ?>">
                                                        <?php echo htmlspecialchars($option); ?>
                                                    </label>
                                                </div>
                                            <?php } 
                                            } else if($question['question_type'] == 'True/False') { ?>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" 
                                                           name="answer[<?php echo $question['id']; ?>]" 
                                                           id="q<?php echo $question['id']; ?>_true" 
                                                           value="True">
                                                    <label class="form-check-label" for="q<?php echo $question['id']; ?>_true">True</label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" 
                                                           name="answer[<?php echo $question['id']; ?>]" 
                                                           id="q<?php echo $question['id']; ?>_false" 
                                                           value="False">
                                                    <label class="form-check-label" for="q<?php echo $question['id']; ?>_false">False</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    $question_count++;
                                } 
                                
                                if($questions->rowCount() == 0) { ?>
                                    <div class="alert alert-info">
                                        <p class="mb-0">No questions available for the selected difficulty level.</p>
                                    </div>
                                <?php } else { ?>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Test
                                        </button>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                        
                        <!-- Test Information Sidebar -->
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Test Information</h4>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Exam:</span>
                                            <span class="fw-bold"><?php echo htmlspecialchars($exam['exam_name']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Duration:</span>
                                            <span class="fw-bold"><?php echo $exam['duration']; ?> minutes</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Total Marks:</span>
                                            <span class="fw-bold"><?php echo $exam['total_marks']; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Difficulty:</span>
                                            <span class="fw-bold"><?php echo $selected_difficulty; ?></span>
                                        </li>
                                    </ul>
                                    
                                    <div class="timer-section mt-4">
                                        <h5>Time Remaining</h5>
                                        <div id="timer" class="h3 text-center text-danger">
                                            <!-- Timer will be updated via JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <?php if(!empty($selected_difficulty)) { ?>
    <script>
        // Timer functionality
        let timeLeft = <?php echo $exam['duration'] * 60; ?>; // Convert minutes to seconds
        
        // Enhanced timer animation
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timerElement = document.getElementById('timer');
            
            // Add warning class when less than 5 minutes remaining
            if (timeLeft <= 300) {
                timerElement.classList.add('text-danger');
                timerElement.style.animation = 'pulse 1s infinite';
            }
            
            timerElement.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                document.getElementById('testForm').submit();
            } else {
                timeLeft--;
            }
        }
        
        // Add pulse animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
        
        // Update timer every second
        updateTimer();
        setInterval(updateTimer, 1000);
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <?php } ?>
</body>
</html>