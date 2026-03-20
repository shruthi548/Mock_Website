<?php
include 'config.php';
$admin = new Admin();

// Check if form is submitted
if (!isset($_POST['exam_id']) || !isset($_POST['difficulty'])) {
    header("location:index.php");
    exit;
}

$exam_id = intval($_POST['exam_id']);
$difficulty = $_POST['difficulty'];
$user_answers = isset($_POST['answer']) ? $_POST['answer'] : array();

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

// Calculate results
$total_marks = 0;
$obtained_marks = 0;
$correct_answers = 0;
$incorrect_answers = 0;
$unattempted = 0;

// Fetch all questions and calculate results
$questions = $admin->ret("SELECT * FROM questions 
                         WHERE exam_id = '$exam_id' 
                         AND difficulty = '$difficulty' 
                         ORDER BY rand() ASC");

// Process all questions first
while($question = $questions->fetch(PDO::FETCH_ASSOC)) {
    $total_marks += $question['marks'];
    $user_answer = isset($user_answers[$question['id']]) ? $user_answers[$question['id']] : '';
    $options = json_decode($question['options'], true);
$correct_index = $question['correct_answer'];
$correct_value = $options[$correct_index];

$is_correct = ($user_answer == $correct_value);
    
    if(empty($user_answer)) {
        $unattempted++;
    } else if($is_correct) {
        $correct_answers++;
        $obtained_marks += $question['marks'];
    } else {
        $incorrect_answers++;
    }
}

// Calculate percentage and pass status after processing all questions
$completion_time = date('Y-m-d H:i:s');
$percentage = $total_marks > 0 ? round(($obtained_marks/$total_marks) * 100, 2) : 0;
$pass_status = ($obtained_marks >= $exam['pass_marks']) ? 1 : 0;

// Calculate time taken
$start_time = isset($_POST['start_time']) ? intval($_POST['start_time']) : 0;
$end_time = time();
$time_taken = $end_time - $start_time; // Time taken in seconds

// Format completion time as HH:MM:SS
$hours = floor($time_taken / 3600);
$minutes = floor(($time_taken % 3600) / 60);
$seconds = $time_taken % 60;
$completion_time = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

// Store test results with completion time
$result_query = $admin->Rcud("INSERT INTO test_results 
    (exam_id, user_id, difficulty, completion_time, total_marks, obtained_marks, 
     percentage, correct_answers, incorrect_answers, unattempted, pass_status) 
    VALUES ('$exam_id', '".$_SESSION['u_id']."', '$difficulty', '$completion_time',
    '$total_marks', '$obtained_marks', '$percentage', '$correct_answers', 
    '$incorrect_answers', '$unattempted', '$pass_status')", 
    "Inserted");
$result_id = $result_query;

// Reset the questions pointer and store individual answers
$questions = $admin->ret("SELECT * FROM questions 
                         WHERE exam_id = '$exam_id' 
                         AND difficulty = '$difficulty' 
                         ORDER BY rand() ASC");

while($question = $questions->fetch(PDO::FETCH_ASSOC)) {
    $user_answer = isset($user_answers[$question['id']]) ? $user_answers[$question['id']] : '';
    $options = json_decode($question['options'], true);
$correct_index = $question['correct_answer'];
$correct_value = $options[$correct_index];

$is_correct = ($user_answer == $correct_value);
    
    // Store answer details
    $admin->cud("INSERT INTO answer_details 
        (result_id, question_id, user_answer, is_correct) 
        VALUES ('$result_id', '".$question['id']."', '$user_answer', '".($is_correct ? 1 : 0)."')", 
        "Inserted");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Results - <?php echo htmlspecialchars($exam['exam_name']); ?></title>
    
    <!-- Include CSS files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <!-- Results Section -->
        <section class="page-content course-sec">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center mb-4">Test Results</h2>
                                
                                <!-- Test Information -->
                                <div class="test-info mb-4">
                                    <h4><?php echo htmlspecialchars($exam['exam_name']); ?></h4>
                                    <p class="text-muted">
                                        Subject: <?php echo htmlspecialchars($exam['subject']); ?><br>
                                        Difficulty: <?php echo htmlspecialchars($difficulty); ?>
                                    </p>
                                </div>
                                
                                <!-- Questions Review -->
                                <?php
                                while($question = $questions->fetch(PDO::FETCH_ASSOC)) {
                                    $total_marks += $question['marks'];
                                    $user_answer = isset($user_answers[$question['id']]) ? $user_answers[$question['id']] : '';
                                    $options = json_decode($question['options'], true);
$correct_index = $question['correct_answer'];
$correct_value = $options[$correct_index];

$is_correct = ($user_answer == $correct_value);
                                    
                                    if(empty($user_answer)) {
                                        $unattempted++;
                                    } else if($is_correct) {
                                        $correct_answers++;
                                        $obtained_marks += $question['marks'];
                                    } else {
                                        $incorrect_answers++;
                                    }
                                    
                                    // Store answer details
                                    $admin->cud("INSERT INTO answer_details 
                                        (result_id, question_id, user_answer, is_correct) 
                                        VALUES ('$result_id', '".$question['id']."', '$user_answer', '".($is_correct ? 1 : 0)."')", 
                                        "Inserted");
                                ?>
                                <div class="card mb-3 <?php echo empty($user_answer) ? 'border-warning' : ($is_correct ? 'border-success' : 'border-danger'); ?>">
                                    <div class="card-body">
                                        <div class="question-header d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0">Question <?php echo $question['question_number']; ?></h5>
                                            <span class="badge bg-primary">Marks: <?php echo $question['marks']; ?></span>
                                        </div>
                                        
                                        <p class="questio   n-text mb-3"><?php echo htmlspecialchars($question['question_text']); ?></p>
                                        
                                        <div class="answer-details">
                                            <p>
                                                <strong>Your Answer:</strong> 
                                                <?php echo empty($user_answer) ? '<span class="text-warning">Not attempted</span>' : htmlspecialchars($user_answer); ?>
                                            </p>
                                            <p>
                                                <strong>Correct Answer:</strong> 
                                                <span class="text-success"><?php echo htmlspecialchars($question['correct_answer']); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <!-- Results Summary -->
                                <div class="results-summary mt-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Summary</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="result-item text-center">
                                                        <h5>Total Marks</h5>
                                                        <p class="h3"><?php echo $total_marks; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="result-item text-center">
                                                        <h5>Obtained Marks</h5>
                                                        <p class="h3"><?php echo $obtained_marks; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="result-item text-center">
                                                        <h5>Percentage</h5>
                                                        <p class="h3"><?php echo round(($obtained_marks/$total_marks) * 100, 2); ?>%</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="result-item text-center">
                                                        <h5>Result</h5>
                                                        <p class="h3 <?php echo $obtained_marks >= $exam['pass_marks'] ? 'text-success' : 'text-danger'; ?>">
                                                            <?php echo $obtained_marks >= $exam['pass_marks'] ? 'PASS' : 'FAIL'; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-4">
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h5>Correct Answers</h5>
                                                        <p class="h3 text-success"><?php echo $correct_answers; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h5>Incorrect Answers</h5>
                                                        <p class="h3 text-danger"><?php echo $incorrect_answers; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h5>Not Attempted</h5>
                                                        <p class="h3 text-warning"><?php echo $unattempted; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="text-center mt-4">
                                    <a href="testdetails.php?id=<?php echo $exam_id; ?>" class="btn btn-primary me-2">
                                        <i class="fas fa-chevron-left me-2"></i>Back to Test Details
                                    </a>
                                    <a href="index.php" class="btn btn-success">
                                        <i class="fas fa-home me-2"></i>Go to Homepage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>