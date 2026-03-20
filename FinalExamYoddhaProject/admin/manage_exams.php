<?php
include '../config.php';
$admin=new Admin();

if(!isset($_SESSION['a_id'])){
    header("location:login.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- PAGE TITLE HERE -->
    <title>Admin - Manage Exams</title>
    
    <!-- STYLESHEETS -->
    <link href="public/assets/vendor/owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css"/>    
    <link href="public/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>    
    <link href="public/assets/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet" type="text/css"/>        
    <link href="public/assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    
    <div id="main-wrapper">
        <?php include 'includes/header.php'?>
        <?php include 'includes/sidebar.php';?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title">Manage Exams</h4>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addExamModal">Add Exam</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Exam Name</th>
                                                <th>Exam Code</th>
                                                <th>Test Type</th>
                                                <th>Duration</th>
                                                <th>Marks</th>
                                                <th>Assigned To</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT e.*, ed.name as educator_name, ed.image as educator_image, tn.test_name as test_type 
                                                     FROM `exams` e 
                                                     LEFT JOIN `educators` ed ON e.educator_id = ed.id 
                                                     LEFT JOIN `test_names` tn ON e.test_name_id = tn.id
                                                     ORDER BY e.created_at DESC";
                                            $result = $admin->ret($query);
                                            if ($result->rowCount() > 0) {
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                    <?php if($row['image']): ?>
                                                                        <img src="controller/uploads/exams/<?php echo $row['image']; ?>" class="rounded-circle me-2" width="30" height="30" alt="">
                                                                    <?php endif; ?>
                                                                    <span class="badge badge-success"><?php echo $row['exam_name']; ?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $row['exam_code']; ?></td>
                                                        <td><span class="badge badge-primary"><?php echo $row['test_type']; ?></span></td>
                                                        <td><?php echo $row['duration']; ?> mins</td>
                                                        <td>
                                                            <div>Total: <?php echo $row['total_marks']; ?></div>
                                                            <div class="text-muted">Pass: <?php echo $row['pass_marks']; ?></div>
                                                        </td>
                                                        <td>
                                                            <?php if($row['educator_name']): ?>
                                                                <div class="d-flex align-items-center">
                                                                    <?php if($row['educator_image']): ?>
                                                                        <img src="controller/uploads/<?php echo $row['educator_image']; ?>" class="rounded-circle me-2" width="30" height="30" alt="">
                                                                    <?php endif; ?>
                                                                    <span class="badge badge-success"><?php echo $row['educator_name']; ?></span>
                                                                </div>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger">Not Assigned</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?php echo $row['exam_type'] == 'Mock' ? 'warning' : 'info'; ?>">
                                                                <?php echo $row['exam_type']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?php echo $row['status'] == 1 ? 'success' : 'danger'; ?>">
                                                                <?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-warning btn-sm me-2" 
                                                                    onclick="editExam(<?php echo $row['id']; ?>)">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <a href="controller/exam.php?delete=<?php echo $row['id']; ?>" 
                                                                    class="btn btn-danger btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to delete this exam?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center'>No exams found</td></tr>";
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Exam Modal -->
        <div class="modal fade" id="addExamModal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Exam</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/exam.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Name</label>
                                        <input type="text" class="form-control" name="exam_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Code</label>
                                        <input type="text" class="form-control" name="exam_code" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Test Type</label>
                                        <select class="form-control" name="test_name_id" required>
                                            <option value="">Select Test Type</option>
                                            <?php
                                            $test_query = "SELECT * FROM test_names WHERE status = 1 ORDER BY test_name ASC";
                                            $test_result = $admin->ret($test_query);
                                            while ($test = $test_result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$test['id']."'>".$test['test_name']." (".$test['subject'].")</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Duration (minutes)</label>
                                        <input type="number" class="form-control" name="duration" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Marks</label>
                                        <input type="number" class="form-control" name="total_marks" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pass Marks</label>
                                        <input type="number" class="form-control" name="pass_marks" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Assign Educator</label>
                                        <select class="form-control" name="educator_id">
                                            <option value="">Select Educator</option>
                                            <?php
                                            $educator_query = "SELECT * FROM educators WHERE status = 1 ORDER BY name ASC";
                                            $educator_result = $admin->ret($educator_query);
                                            while ($educator = $educator_result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$educator['id']."'>".$educator['name']." - ".$educator['specialization']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Type</label>
                                        <select class="form-control" name="exam_type" required>
                                            <option value="Mock">Mock</option>
                                            <option value="Practice">Practice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Thumbnail Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Instructions</label>
                                        <textarea class="form-control" name="instructions" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_exam" class="btn btn-primary">Add Exam</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Exam Modal -->
        <div class="modal fade" id="editExamModal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Exam</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/exam.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="exam_id" id="edit_exam_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Name</label>
                                        <input type="text" class="form-control" name="exam_name" id="edit_exam_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Code</label>
                                        <input type="text" class="form-control" name="exam_code" id="edit_exam_code" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Test Type</label>
                                        <select class="form-control" name="test_name_id" id="edit_test_name_id" required>
                                            <option value="">Select Test Type</option>
                                            <?php
                                            $test_query = "SELECT * FROM test_names WHERE status = 1 ORDER BY test_name ASC";
                                            $test_result = $admin->ret($test_query);
                                            while ($test = $test_result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$test['id']."'>".$test['test_name']." (".$test['subject'].")</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Duration (minutes)</label>
                                        <input type="number" class="form-control" name="duration" id="edit_duration" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Marks</label>
                                        <input type="number" class="form-control" name="total_marks" id="edit_total_marks" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pass Marks</label>
                                        <input type="number" class="form-control" name="pass_marks" id="edit_pass_marks" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Assign Educator</label>
                                        <select class="form-control" name="educator_id" id="edit_educator_id">
                                            <option value="">Select Educator</option>
                                            <?php
                                            $educator_query = "SELECT * FROM educators WHERE status = 1 ORDER BY name ASC";
                                            $educator_result = $admin->ret($educator_query);
                                            while ($educator = $educator_result->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$educator['id']."'>".$educator['name']." - ".$educator['specialization']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Type</label>
                                        <select class="form-control" name="exam_type" id="edit_exam_type" required>
                                            <option value="Mock">Mock</option>
                                            <option value="Practice">Practice</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Thumbnail Image</label>
                                        <input type="file" class="form-control" name="image" id="edit_image">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Instructions</label>
                                        <textarea class="form-control" name="instructions" id="edit_instructions" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status" id="edit_status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_exam" class="btn btn-primary">Update Exam</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <script src="public/assets/vendor/global/global.min.js"></script>
    <script src="public/assets/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="public/assets/js/custom.min.js"></script>
    <script src="public/assets/js/dlabnav-init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // Initialize nice select
        $('select').niceSelect();
    });
    function editExam(id) {
        $.ajax({
            url: 'controller/exam.php',
            type: 'POST',
            data: {get_exam: id},
            dataType: 'json',
            success: function(response) {
                $('#edit_exam_id').val(response.id);
                $('#edit_exam_name').val(response.exam_name);
                $('#edit_exam_code').val(response.exam_code);
                $('#edit_test_name_id').val(response.test_name_id);
                $('#edit_duration').val(response.duration);
                $('#edit_total_marks').val(response.total_marks);
                $('#edit_pass_marks').val(response.pass_marks);
                $('#edit_educator_id').val(response.educator_id);
                $('#edit_exam_type').val(response.exam_type);
                $('#edit_description').val(response.description);
                $('#edit_instructions').val(response.instructions);
                $('#edit_status').val(response.status);
                
                if(response.image) {
                    $('#current_image').html('<img src="controller/uploads/exams/' + response.image + '" width="100">');
                } else {
                    $('#current_image').html('No image uploaded');
                }
                
                $('#editExamModal').modal('show');
            }
        });
    }
    </script>
    
    function viewExam(id) {
        // You can implement view functionality here
        window.location.href = 'view_exam.php?id=' + id;
    }
    </script>
</body>
</html>