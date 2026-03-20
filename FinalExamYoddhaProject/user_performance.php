<?php
include 'config.php';
$admin = new Admin();

if (!isset($_SESSION['u_id'])) {
    header("location:login.php");
    exit;
}

$user_id = $_SESSION['u_id'];

// Fetch user data
$user_query = "SELECT * FROM users WHERE u_id = '$user_id'";
$user_result = $admin->ret($user_query);
$user_data = $user_result->fetch(PDO::FETCH_ASSOC);

// Fetch user performance data
$query = "SELECT tr.*, e.exam_name, e.total_marks, e.pass_marks, e.exam_type, e.duration, e.description 
          FROM test_results tr 
          JOIN exams e ON tr.exam_id = e.id 
          WHERE tr.user_id = '$user_id' 
          ORDER BY tr.completion_time DESC";
$results = $admin->ret($query);
$performance_data = $results->fetchAll(PDO::FETCH_ASSOC);

// Clone the results for later use
$results = $admin->ret($query);

// Fetch detailed question analysis
$detailed_query = "SELECT q.*, ad.user_answer, ad.is_correct, tr.exam_id, e.exam_name
                   FROM answer_details ad
                   JOIN questions q ON ad.question_id = q.id
                   JOIN test_results tr ON ad.result_id = tr.id
                   JOIN exams e ON tr.exam_id = e.id
                   WHERE tr.user_id = '$user_id'
                   ORDER BY tr.completion_time DESC, q.question_number ASC";
$detailed_results = $admin->ret($detailed_query);
$question_data = $detailed_results->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for charts
$exam_names = [];
$total_marks_data = [];
$obtained_marks_data = [];
$pass_marks_data = [];
$correct_answers_data = [];
$incorrect_answers_data = [];
$unattempted_data = [];
$percentage_data = [];

// Difficulty level distribution
$difficulty_counts = [
    'Easy' => ['correct' => 0, 'incorrect' => 0, 'total' => 0],
    'Medium' => ['correct' => 0, 'incorrect' => 0, 'total' => 0],
    'Hard' => ['correct' => 0, 'incorrect' => 0, 'total' => 0]
];

// Question type distribution
$question_types = [];

// Topic/subject performance
$topic_performance = [];

// Time-based performance trend
$time_performance = [];

foreach ($performance_data as $row) {
    $exam_names[] = $row['exam_name'];
    $total_marks_data[] = $row['total_marks'];
    $obtained_marks_data[] = $row['obtained_marks'];
    $pass_marks_data[] = $row['pass_marks'];
    $correct_answers_data[] = $row['correct_answers'];
    $incorrect_answers_data[] = $row['incorrect_answers'];
    $unattempted_data[] = $row['unattempted'];
    $percentage_data[] = $row['percentage'];
    
    $date = date('Y-m-d', strtotime($row['completion_time']));
    if (!isset($time_performance[$date])) {
        $time_performance[$date] = ['count' => 0, 'total_percentage' => 0];
    }
    $time_performance[$date]['count']++;
    $time_performance[$date]['total_percentage'] += $row['percentage'];
}

// Calculate average performance over time
$dates = [];
$avg_performances = [];
foreach ($time_performance as $date => $data) {
    $dates[] = $date;
    $avg_performances[] = $data['total_percentage'] / $data['count'];
}

// Process question data for detailed analytics
foreach ($question_data as $question) {
    // Process difficulty distribution
    if (isset($question['difficulty'])) {
        $difficulty = $question['difficulty'];
        if (!isset($difficulty_counts[$difficulty])) {
            $difficulty_counts[$difficulty] = ['correct' => 0, 'incorrect' => 0, 'total' => 0];
        }
        $difficulty_counts[$difficulty]['total']++;
        if ($question['is_correct']) {
            $difficulty_counts[$difficulty]['correct']++;
        } else {
            $difficulty_counts[$difficulty]['incorrect']++;
        }
    }
    
    // Process question type distribution
    if (isset($question['question_type'])) {
        $type = $question['question_type'];
        if (!isset($question_types[$type])) {
            $question_types[$type] = ['correct' => 0, 'incorrect' => 0, 'total' => 0];
        }
        $question_types[$type]['total']++;
        if ($question['is_correct']) {
            $question_types[$type]['correct']++;
        } else {
            $question_types[$type]['incorrect']++;
        }
    }
    
    // Extract topics from questions (assuming topics might be in question text or separate field)
    // For demonstration, let's extract a simple topic from the question text
    $topic = "General"; // Default topic
    if (strpos(strtolower($question['question_text']), 'math') !== false) $topic = "Mathematics";
    if (strpos(strtolower($question['question_text']), 'physics') !== false) $topic = "Physics";
    if (strpos(strtolower($question['question_text']), 'chemistry') !== false) $topic = "Chemistry";
    if (strpos(strtolower($question['question_text']), 'biology') !== false) $topic = "Biology";
    if (strpos(strtolower($question['question_text']), 'history') !== false) $topic = "History";
    if (strpos(strtolower($question['question_text']), 'geography') !== false) $topic = "Geography";
    
    if (!isset($topic_performance[$topic])) {
        $topic_performance[$topic] = ['correct' => 0, 'incorrect' => 0, 'total' => 0];
    }
    $topic_performance[$topic]['total']++;
    if ($question['is_correct']) {
        $topic_performance[$topic]['correct']++;
    } else {
        $topic_performance[$topic]['incorrect']++;
    }
}

// Calculate strengths and weaknesses
$strengths = [];
$weaknesses = [];

foreach ($topic_performance as $topic => $data) {
    if ($data['total'] > 0) {
        $percentage = ($data['correct'] / $data['total']) * 100;
        if ($percentage >= 70) {
            $strengths[$topic] = $percentage;
        } elseif ($percentage <= 40) {
            $weaknesses[$topic] = $percentage;
        }
    }
}

// Sort strengths and weaknesses
arsort($strengths);
asort($weaknesses);

// Calculate average performance
$overall_avg_percentage = !empty($percentage_data) ? array_sum($percentage_data) / count($percentage_data) : 0;
$overall_total_questions = array_sum($correct_answers_data) + array_sum($incorrect_answers_data) + array_sum($unattempted_data);
$overall_correct_percentage = $overall_total_questions > 0 ? (array_sum($correct_answers_data) / $overall_total_questions) * 100 : 0;

// Convert data arrays to JSON for JavaScript
$exam_names_json = json_encode($exam_names);
$total_marks_json = json_encode($total_marks_data);
$obtained_marks_json = json_encode($obtained_marks_data);
$pass_marks_json = json_encode($pass_marks_data);
$correct_answers_json = json_encode($correct_answers_data);
$incorrect_answers_json = json_encode($incorrect_answers_data);
$unattempted_json = json_encode($unattempted_data);
$percentage_json = json_encode($percentage_data);
$dates_json = json_encode($dates);
$avg_performances_json = json_encode($avg_performances);
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
    
    <title>Exam Portal | Performance Analytics Dashboard</title>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    
    <style>
        .dashboard-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-card {
            padding: 20px;
            border-radius: 12px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 50px;
            opacity: 0.3;
        }
        
        .good-stat {
            background: linear-gradient(45deg, #2196F3, #00BCD4);
        }
        
        .average-stat {
            background: linear-gradient(45deg, #FF9800, #FFEB3B);
        }
        
        .warning-stat {
            background: linear-gradient(45deg, #F44336, #FF5722);
        }
        
        .info-stat {
            background: linear-gradient(45deg, #9C27B0, #673AB7);
        }
        
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
        }
        
        .performance-indicator {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .performance-metric {
            font-size: 36px;
            font-weight: bold;
        }
        
        .progress-wrapper {
            margin: 20px 0;
        }
        
        .progress {
            height: 12px;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        
        .topic-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }
        
        .weak-topic {
            border-left: 4px solid #F44336;
        }
        
        .suggestion-item {
            background-color: #E3F2FD;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .detailed-question {
            border-left: 4px solid #ddd;
            padding-left: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .detailed-question.correct {
            border-left-color: #4CAF50;
        }
        
        .detailed-question.incorrect {
            border-left-color: #F44336;
        }
        
        .detailed-question:hover {
            background-color: #f9f9f9;
        }
        
        .answer-chip {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 0.85rem;
            margin-right: 10px;
        }
        
        .correct-answer {
            background-color: rgba(76, 175, 80, 0.2);
            color: #2E7D32;
        }
        
        .incorrect-answer {
            background-color: rgba(244, 67, 54, 0.2);
            color: #C62828;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .student-profile {
            display: flex;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg,rgb(39, 39, 65) 0%, #8B5CF6 100%);
            color: orange;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        
        .student-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #6366F1;
            margin-right: 20px;
        }
        
        .student-info h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: orange;
            font-weight: bold;
        }
        
        .student-info p {
            margin: 5px 0 0;
            opacity: 0.9;
            color: orange;
            font-weight: bold;
        }
        
        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: #e9ecef;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
        }
        
        .timeline-item.left {
            left: 0;
        }
        
        .timeline-item.right {
            left: 50%;
        }
        
        .timeline-content {
            padding: 15px;
            background-color: white;
            position: relative;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .timeline-content::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            right: -10px;
            background-color: white;
            top: 15px;
            z-index: 1;
            transform: rotate(45deg);
        }
        
        .timeline-item.right .timeline-content::after {
            left: -10px;
        }
        
        .timeline-date {
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .comparison-table th {
            background-color: #f8f9fa;
        }
        
        /* Responsive column adjustments */
        @media (max-width: 767px) {
            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>
        
        <!-- Breadcrumb -->
        <div class="breadcrumb-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="breadcrumb-list">
                            <nav aria-label="breadcrumb" class="page-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Performance Analytics</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Wrapper -->
        <div class="page-content">
            <div class="container">
                <!-- Student Profile -->

                
                <!-- Performance Summary Cards -->
                <div class="row">
                    <div class="col-12">
                        <div class="section-header">
                            <h2>Performance Overview</h2>
                            <p>Quick summary of your performance across all exams</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card good-stat dashboard-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Average Score</h3>
                            <div class="performance-metric"><?php echo number_format($overall_avg_percentage, 1); ?>%</div>
                            <p>Across all exams</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card info-stat dashboard-card">
                            <div class="stat-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h3>Exams Taken</h3>
                            <div class="performance-metric"><?php echo count($performance_data); ?></div>
                            <p>Total assessments</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card average-stat dashboard-card">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3>Success Rate</h3>
                            <div class="performance-metric"><?php echo number_format($overall_correct_percentage, 1); ?>%</div>
                            <p>Correct answers</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-card warning-stat dashboard-card">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h3>Pass Rate</h3>
                            <?php 
                            $pass_count = 0;
                            foreach ($performance_data as $result) {
                                if ($result['pass_status'] == 1) $pass_count++;
                            }
                            $pass_rate = count($performance_data) > 0 ? ($pass_count / count($performance_data)) * 100 : 0;
                            ?>
                            <div class="performance-metric"><?php echo number_format($pass_rate, 1); ?>%</div>
                            <p>Pass percentage</p>
                        </div>
                    </div>
                </div>
                
                <div class="student-profile">
                    <div class="student-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="student-info">
                        <h3><?php echo htmlspecialchars($user_data['u_name']); ?></h3>
                        <p><?php echo htmlspecialchars($user_data['u_email']); ?></p>
                        <p><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($user_data['u_phone']); ?></p>
                    </div>
                </div>
                <!-- Main Performance Charts -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Exam Performance Comparison</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="performanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Performance Trend Over Time</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="trendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Analytics Charts -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Question Type Performance</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="questionTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Performance by Difficulty</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="difficultyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Answer Distribution</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="answerDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Strengths and Weaknesses -->

                
                <!-- Personalized Recommendations -->

                
                <!-- Recent Exam Activity -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Recent Exam Activity</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped comparison-table">
                                        <thead>
                                            <tr>
                                                <th>Exam Name</th>
                                                <th>Date</th>
                                                <th>Score</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $count = 0;
                                            while ($row = $results->fetch(PDO::FETCH_ASSOC)):
                                                if ($count >= 5) break; // Show only 5 recent exams
                                                $count++;
                                                $status_class = $row['pass_status'] == 1 ? 'success' : 'danger';
                                                $status_text = $row['pass_status'] == 1 ? 'Passed' : 'Failed';
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['completion_time'])); ?></td>
                                                <td><?php echo $row['obtained_marks']; ?> / <?php echo $row['total_marks']; ?> (<?php echo $row['percentage']; ?>%)</td>
                                                <td><span class="badge bg-<?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php if ($count == 0): ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No exam activity found.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Frequently Missed Questions -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <h4 class="card-title">Frequently Missed Questions</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                // Analyze frequently missed questions
                                $missed_questions = [];
                                foreach ($question_data as $question) {
                                    if (!$question['is_correct']) {
                                        $q_id = $question['id'];
                                        if (!isset($missed_questions[$q_id])) {
                                            $missed_questions[$q_id] = [
                                                'question' => $question,
                                                'count' => 0
                                            ];
                                        }
                                        $missed_questions[$q_id]['count']++;
                                    }
                                }
                                
                                // Sort by frequency
                                usort($missed_questions, function($a, $b) {
                                    return $b['count'] - $a['count'];
                                });
                                
                                // Display top 5 frequently missed questions
                                if (empty($missed_questions)):
                                ?>
                                <div class="alert alert-info">
                                    <p>No frequently missed questions identified yet. Keep practicing!</p>
                                </div>
                                <?php else: ?>
                                <div class="accordion" id="missedQuestionsAccordion">
                                    <?php 
                                    $counter = 0;
                                    foreach ($missed_questions as $key => $item):
                                        if ($counter >= 5) break; // Limit to top 5
                                        $counter++;
                                        $question = $item['question'];
                                    ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $counter; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $counter; ?>" aria-expanded="false" aria-controls="collapse<?php echo $counter; ?>">
                                                <?php echo htmlspecialchars(substr($question['question_text'], 0, 80) . '...'); ?>
                                                <span class="badge bg-danger ms-2">Missed <?php echo $item['count']; ?> times</span>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $counter; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $counter; ?>" data-bs-parent="#missedQuestionsAccordion">
                                            <div class="accordion-body">
                                                <div class="detailed-question incorrect">
                                                    <h6><?php echo htmlspecialchars($question['question_text']); ?></h6>
                                                    <div class="mt-2">
                                                        <div class="answer-chip incorrect-answer">
                                                            <i class="fas fa-times"></i> Your Answer: <?php echo htmlspecialchars($question['user_answer']); ?>
                                                        </div>
                                                        <div class="answer-chip correct-answer">
                                                            <i class="fas fa-check"></i> Correct Answer: <?php echo htmlspecialchars($question['correct_answer']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <h6>Explanation:</h6>
                                                        <p><?php echo htmlspecialchars($question['explanation'] ?? 'No explanation available.'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- jQuery -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <script>
        // Chart.js Configuration
        Chart.register(ChartDataLabels);
        
        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: <?php echo $exam_names_json; ?>,
                datasets: [{
                    label: 'Obtained Marks',
                    data: <?php echo $obtained_marks_json; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Pass Marks',
                    data: <?php echo $pass_marks_json; ?>,
                    type: 'line',
                    fill: false,
                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Marks'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Exam Performance Comparison'
                    },
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            }
        });
        
        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: <?php echo $dates_json; ?>,
                datasets: [{
                    label: 'Performance (%)',
                    data: <?php echo $avg_performances_json; ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Performance (%)'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Performance Trend Over Time'
                    },
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        formatter: function(value, context) {
                            return value.toFixed(1) + '%';
                        }
                    }
                }
            }
        });
        
        // Question Type Chart
        const questionTypeCtx = document.getElementById('questionTypeChart').getContext('2d');
        const questionTypeData = {
            labels: [
                <?php 
                foreach ($question_types as $type => $data) {
                    echo "'" . addslashes($type) . "', ";
                }
                ?>
            ],
            datasets: [{
                label: 'Correct',
                data: [
                    <?php 
                    foreach ($question_types as $type => $data) {
                        echo $data['correct'] . ", ";
                    }
                    ?>
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Incorrect',
                data: [
                    <?php 
                    foreach ($question_types as $type => $data) {
                        echo $data['incorrect'] . ", ";
                    }
                    ?>
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };
        
        const questionTypeChart = new Chart(questionTypeCtx, {
            type: 'bar',
            data: questionTypeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Questions'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Performance by Question Type'
                    },
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        display: function(context) {
                            return context.dataset.data[context.dataIndex] > 0;
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            }
        });
        
        // Difficulty Chart
        const difficultyCtx = document.getElementById('difficultyChart').getContext('2d');
        const difficultyData = {
            labels: [
                <?php 
                foreach ($difficulty_counts as $difficulty => $data) {
                    echo "'" . addslashes($difficulty) . "', ";
                }
                ?>
            ],
            datasets: [{
                label: 'Correct',
                data: [
                    <?php 
                    foreach ($difficulty_counts as $difficulty => $data) {
                        echo $data['correct'] . ", ";
                    }
                    ?>
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Incorrect',
                data: [
                    <?php 
                    foreach ($difficulty_counts as $difficulty => $data) {
                        echo $data['incorrect'] . ", ";
                    }
                    ?>
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };
        
        const difficultyChart = new Chart(difficultyCtx, {
            type: 'bar',
            data: difficultyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Questions'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Performance by Difficulty Level'
                    },
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        display: function(context) {
                            return context.dataset.data[context.dataIndex] > 0;
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            }
        });
        
        // Answer Distribution Chart
        const answerDistributionCtx = document.getElementById('answerDistributionChart').getContext('2d');
        
        // Calculate totals for all exams
        let totalCorrect = <?php echo array_sum($correct_answers_data); ?>;
        let totalIncorrect = <?php echo array_sum($incorrect_answers_data); ?>;
        let totalUnattempted = <?php echo array_sum($unattempted_data); ?>;
        
        const answerDistributionChart = new Chart(answerDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Correct', 'Incorrect', 'Unattempted'],
                datasets: [{
                    data: [totalCorrect, totalIncorrect, totalUnattempted],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(201, 203, 207, 0.8)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Answer Distribution'
                    },
                    legend: {
                        position: 'bottom',
                    },
                    datalabels: {
                        formatter: function(value, context) {
                            let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            let percentage = (value / total * 100).toFixed(1) + '%';
                            return percentage;
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>