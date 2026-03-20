<?php
include '../config.php';
$admin=new Admin();

if(!isset($_SESSION['a_id'])){
    header("location:login.php");
}

// Stats for dashboard cards
$totalStudents = $admin->ret("SELECT COUNT(*) as total FROM users")->fetch(PDO::FETCH_ASSOC);
$totalEducators = $admin->ret("SELECT COUNT(*) as total FROM educators")->fetch(PDO::FETCH_ASSOC);
$totalExams = $admin->ret("SELECT COUNT(*) as total FROM exams")->fetch(PDO::FETCH_ASSOC);
$totalTestResults = $admin->ret("SELECT COUNT(*) as total FROM test_results")->fetch(PDO::FETCH_ASSOC);

// Get exam pass/fail statistics for graph
$examStats = $admin->ret("
SELECT 
	e.exam_name, 
	COUNT(CASE WHEN tr.pass_status = '1' THEN 1 END) AS passed,
	COUNT(CASE WHEN tr.pass_status = '0' THEN 1 END) AS failed
FROM exams e
LEFT JOIN test_results tr ON e.id = tr.exam_id
GROUP BY e.id, e.exam_name
LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);


// Get educator performance data
$educatorStats = $admin->ret("SELECT 
                            ed.name, 
                            COUNT(e.id) as total_exams,
                            AVG(tr.percentage) as avg_score
                          FROM educators ed
                          LEFT JOIN exams e ON ed.id = e.educator_id
                          LEFT JOIN test_results tr ON e.id = tr.exam_id
                          GROUP BY ed.id")->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for charts
$examLabels = [];
$passData = [];
$failData = [];

foreach($examStats as $stat) {
    $examLabels[] = $stat['exam_name'];
    $passData[] = $stat['passed'];
    $failData[] = $stat['failed'];
}

$educatorNames = [];
$examCounts = [];
$avgScores = [];

foreach($educatorStats as $stat) {
    $educatorNames[] = $stat['name'];
    $examCounts[] = $stat['total_exams'];
    $avgScores[] = $stat['avg_score'] ?? 0;
}

?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="description" content="Education Admin Dashboard" />
    <meta property="og:title" content="Education Admin Dashboard" />
    <meta property="og:description" content="Education Admin Dashboard" />
    <meta property="og:image" content="../social-image.html" />
    <meta name="format-detection" content="telephone=no">
    
    <!-- PAGE TITLE HERE -->
    <title>Admin Dashboard</title>

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="public/assets/vendor/owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css"/>    
    <link href="public/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>    
    <link href="public/assets/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet" type="text/css"/>        
    <link href="public/assets/css/style.css" rel="stylesheet" type="text/css"/>        

</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->
    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
    Nav header start
***********************************-->
<?php include 'includes/header.php'?>
       <!--**********************************
    Sidebar start
***********************************-->
<?php include 'includes/sidebar.php';?>
<!--**********************************
    Sidebar end
***********************************-->        <!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
            </ol>
        </div>
        
        <!-- Stats Row -->
        <div class="row">
            <!-- Total Students -->
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span style="font-size: 40px;">👨‍🎓</span>
                            <div class="ms-4">
                                <h2 class="mb-0 font-w600"><?= $totalStudents['total'] ?? 0; ?></h2>
                                <h3 class="mb-0">Total Students</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Educators -->
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span style="font-size: 40px;">👨‍🏫</span>
                            <div class="ms-4">
                                <h2 class="mb-0 font-w600"><?= $totalEducators['total'] ?? 0; ?></h2>
                                <h3 class="mb-0">Total Educators</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Exams -->
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span style="font-size: 40px;">📝</span>
                            <div class="ms-4">
                                <h2 class="mb-0 font-w600"><?= $totalExams['total'] ?? 0; ?></h2>
                                <h3 class="mb-0">Total Exams</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Test Results -->
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span style="font-size: 40px;">🎯</span>
                            <div class="ms-4">
                                <h2 class="mb-0 font-w600"><?= $totalTestResults['total'] ?? 0; ?></h2>
                                <h3 class="mb-0">Total Test Results</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Exam Performance Chart -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Exam Performance</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="examPerformanceChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Educator Stats Chart -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Educator Statistics</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="educatorStatsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent Exams</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><strong>Exam Name</strong></th>
                                        <th><strong>Subject</strong></th>
                                        <th><strong>Duration</strong></th>
                                        <th><strong>Total Marks</strong></th>
                                        <th><strong>Pass Marks</strong></th>
                                        <th><strong>Educator</strong></th>
                                        <th><strong>Status</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recentExams = $admin->ret("SELECT e.*, tn.subject, ed.name as educator_name 
                                                FROM exams e
                                                JOIN test_names tn ON e.test_name_id = tn.id
                                                LEFT JOIN educators ed ON e.educator_id = ed.id
                                                ORDER BY e.created_at DESC
                                                LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach($recentExams as $exam) {
                                        echo '<tr>
                                            <td>'.$exam['exam_name'].'</td>
                                            <td>'.$exam['subject'].'</td>
                                            <td>'.$exam['duration'].' min</td>
                                            <td>'.$exam['total_marks'].'</td>
                                            <td>'.$exam['pass_marks'].'</td>
                                            <td>'.$exam['educator_name'].'</td>
                                            <td>'.($exam['status'] == 1 ? '<span class="badge light badge-success">Active</span>' : '<span class="badge light badge-danger">Inactive</span>').'</td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->
        <!--**********************************
    Footer start
***********************************-->
<?php include 'includes/footer.php'; ?>
<!--**********************************
    Footer end
***********************************-->        
        
    </div>
    <script src="public/assets/vendor/global/global.min.js"></script>
    <script src="public/assets/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>

    <script src="public/assets/vendor/apexchart/apexchart.js"></script>
    <script src="public/assets/js/dashboard/dashboard-1.js"></script>
    <script src="public/assets/vendor/owl-carousel/owl.carousel.js"></script>
    <script src="public/assets/vendor/bootstrap-datetimepicker/js/moment.js"></script>
    <script src="public/assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="public/assets/js/custom.min.js"></script>
    <script src="public/assets/js/dlabnav-init.js"></script>
    <script src="public/assets/js/demo.js"></script>

    <script>
        // Chart.js for Exam Performance
        var ctx1 = document.getElementById('examPerformanceChart').getContext('2d');
        var examPerformanceChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?= json_encode($examLabels) ?>,
                datasets: [
                    {
                        label: 'Passed',
                        data: <?= json_encode($passData) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Failed',
                        data: <?= json_encode($failData) ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Chart.js for Educator Stats
        var ctx2 = document.getElementById('educatorStatsChart').getContext('2d');
        var educatorStatsChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: <?= json_encode($educatorNames) ?>,
                datasets: [
                    {
                        label: 'Total Exams',
                        data: <?= json_encode($examCounts) ?>,
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Avg. Score (%)',
                        data: <?= json_encode($avgScores) ?>,
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Total Exams'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Avg. Score (%)'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
                        max: 100
                    }
                }
            }
        });
    </script>

    <!--**********************************
        Main wrapper end
    ***********************************-->
</body>
</html>