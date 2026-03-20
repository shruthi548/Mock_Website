<?php
include '../config.php';
$admin = new Admin();

if(!isset($_SESSION['a_id'])){
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Admin - Manage Tests</title>
    
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
                                <h4 class="card-title">Manage Tests</h4>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTestModal">Add Test</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Category</th>
                                                <th>Test Type</th>
                                                <th>Test Type</th>
                                                <th>Duration</th>
                                                <th>Languages</th>
                                                <th>Start Date</th>
                                                <th>Tutor</th>
                                                <th>Tags</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT t.*, e.name as educator_name 
                                                     FROM tests t 
                                                     LEFT JOIN educators e ON t.educator_id = e.id 
                                                     ORDER BY t.start_datetime DESC";
                                            $result = $admin->ret($query);
                                            if ($result->rowCount() > 0) {
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <tr>
                                                        <td><?php echo $row['test_name']; ?></td>
                                                        <td><?php echo $row['category']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td><?php echo $row['test_type']; ?></td>
                                                        <td><?php echo $row['duration'].' mins'; ?></td>
                                                        <td><?php echo $row['languages']; ?></td>
                                                        <td><?php echo date('d-m-Y H:i', strtotime($row['start_datetime'])); ?></td>
                                                        <td><?php echo $row['educator_name']; ?></td>
                                                        <td>
                                                            <?php 
                                                            $tags = explode(',', $row['display_tags']);
                                                            foreach($tags as $tag) {
                                                                echo '<span class="badge badge-primary me-1">'.$tag.'</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?php echo $row['status'] == 'Active' ? 'success' : 'danger'; ?>">
                                                                <?php echo $row['status']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-warning btn-sm me-2" 
                                                                    onclick="editTest(<?php echo $row['id']; ?>)">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <a href="controller/test.php?delete=<?php echo $row['id']; ?>" 
                                                                    class="btn btn-danger btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to delete this test?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr><td colspan='11' class='text-center'>No tests found</td></tr>";
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
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Test</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/test.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Test Name</label>
                                        <input type="text" class="form-control" name="test_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control" name="category" id="category" required onchange="loadSubjects()">
                                            <option value="">Select Category</option>
                                            <option value="JEE">JEE</option>
                                            <option value="NEET">NEET</option>
                                            <option value="SSC">SSC</option>
                                            <option value="UPSC">UPSC</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <select class="form-control" name="subject" id="subject" required>
                                            <option value="">Select Subject</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Test Type</label>
                                        <select class="form-control" name="test_type" required>
                                            <option value="">Select Test Type</option>
                                            <option value="Full Test">Full Test</option>
                                            <option value="Chapter-wise">Chapter-wise</option>
                                            <option value="PYQ">Previous Year Questions</option>
                                            <option value="Topic Test">Topic Test</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Test Cover Image</label>
                                        <input type="file" class="form-control" name="cover_image" accept="image/*" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Duration (minutes)</label>
                                        <input type="number" class="form-control" name="duration" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Languages</label>
                                        <select class="form-control select2" name="languages[]" multiple required>
                                            <option value="English">English</option>
                                            <option value="Hindi">Hindi</option>
                                            <option value="Gujarati">Gujarati</option>
                                            <option value="Marathi">Marathi</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Start Date & Time</label>
                                        <input type="datetime-local" class="form-control" name="start_datetime" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Assign Tutor</label>
                                        <select class="form-control" name="educator_id" required>
                                            <option value="">Select Tutor</option>
                                            <?php
                                            $educators = $admin->ret("SELECT * FROM educators WHERE status = 1");
                                            while ($educator = $educators->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='".$educator['id']."'>".$educator['name']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Display Tags</label>
                                        <select class="form-control select2" name="display_tags[]" multiple>
                                            <option value="Most Popular">Most Popular</option>
                                            <option value="Recommended">Recommended</option>
                                            <option value="Free">Free</option>
                                            <option value="New">New</option>
                                            <option value="Featured">Featured</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="summernote" name="description" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Instructions</label>
                                        <textarea class="summernote" name="instructions" required></textarea>
                                    </div>
                                </div>
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

        <!-- Edit Test Modal - Similar structure as Add Modal with pre-filled values -->
        <div class="modal fade" id="editTestModal">
            <!-- Similar structure as Add Modal -->
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <script src="public/assets/vendor/global/global.min.js"></script>
    <script src="public/assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="public/assets/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="public/assets/vendor/select2/js/select2.full.min.js"></script>
    <script src="public/assets/vendor/summernote/summernote-lite.min.js"></script>
    <script src="public/assets/js/custom.min.js"></script>
    <script src="public/assets/js/dlabnav-init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    function loadSubjects() {
        var category = $('#category').val();
        var subjects = {
            'JEE': ['Physics', 'Chemistry', 'Mathematics'],
            'NEET': ['Physics', 'Chemistry', 'Biology'],
            'SSC': ['Mathematics', 'General Science', 'English', 'Reasoning'],
            'UPSC': ['General Studies', 'CSAT', 'Optional Subject']
        };
        
        var subjectSelect = $('#subject');
        subjectSelect.empty();
        subjectSelect.append('<option value="">Select Subject</option>');
        
        if(category && subjects[category]) {
            subjects[category].forEach(function(subject) {
                subjectSelect.append('<option value="' + subject + '">' + subject + '</option>');
            });
        }
    }

    function editTest(id) {
        $.ajax({
            url: 'controller/test.php',
            type: 'POST',
            data: {get_test: id},
            dataType: 'json',
            success: function(response) {
                // Fill form fields with response data
                $('#edit_test_id').val(response.id);
                $('#edit_test_name').val(response.test_name);
                $('#edit_category').val(response.category).trigger('change');
                $('#edit_subject').val(response.subject);
                // ... fill other fields ...
                $('#editTestModal').modal('show');
            }
        });
    }
    </script>
</body>
</html>