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
    <title>Admin - Manage Test Names</title>
    
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
                                <h4 class="card-title">Manage Test Names</h4>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTestModal">Add Test Name</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Subject</th>
                                                <th>Description</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM test_names ORDER BY created_at DESC";
                                            $result = $admin->ret($query);
                                            if ($result->rowCount() > 0) {
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <tr>
                                                        <td><?php echo $row['test_name']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td><?php echo $row['description']; ?></td>
                                                        <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                        <td>
                                                            <span class="badge badge-<?php echo $row['status'] == 1 ? 'success' : 'danger'; ?>">
                                                                <?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-warning btn-sm me-2" 
                                                                    onclick="editTest(<?php echo $row['id']; ?>)">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <a href="controller/test_name.php?delete=<?php echo $row['id']; ?>" 
                                                                    class="btn btn-danger btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to delete this test?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>No test names found</td></tr>";
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

        <!-- Add Test Modal -->
        <div class="modal fade" id="addTestModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Test Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/test_name.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Test Name</label>
                                <input type="text" class="form-control" name="test_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_test" class="btn btn-primary">Add Test</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Test Modal -->
        <div class="modal fade" id="editTestModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Test Name</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/test_name.php" method="post">
                        <input type="hidden" name="test_id" id="edit_test_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Test Name</label>
                                <input type="text" class="form-control" name="test_name" id="edit_test_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" id="edit_subject" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_test" class="btn btn-primary">Update Test</button>
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
    function editTest(id) {
        $.ajax({
            url: 'controller/test_name.php',
            type: 'POST',
            data: {get_test: id},
            dataType: 'json',
            success: function(response) {
                $('#edit_test_id').val(response.id);
                $('#edit_test_name').val(response.test_name);
                $('#edit_subject').val(response.subject);
                $('#edit_description').val(response.description);
                $('#edit_status').val(response.status);
                $('#editTestModal').modal('show');
            }
        });
    }
    </script>
</body>
</html>