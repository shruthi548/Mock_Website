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
    <title>Admin - Manage Educators</title>
    
    <link href="public/assets/vendor/owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css"/>    
    <link href="public/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>    
    <link href="public/assets/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet" type="text/css"/>        
    <link href="public/assets/css/style.css" rel="stylesheet" type="text/css"/>        
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
                                <h4 class="card-title">Manage Educators</h4>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEducatorModal">Add Educator</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Qualification</th>
                                                <th>Experience</th>
                                                <th>Specialization</th>
                                                <th>Address</th>
                                                <th>Joining Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM `educators` ORDER BY `created_at` DESC";
                                            $result = $admin->ret($query);
                                            if ($result->rowCount() > 0) {
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                                                    <tr>
                                                        <td>
                                                            <?php if(!empty($row['image'])): ?>
                                                                <img src="controller/uploads/<?php echo $row['image']; ?>" alt="Educator" style="width: 50px; height: 50px; border-radius: 50%;">
                                                            <?php else: ?>
                                                                <img src="public/assets/images/no-image.png" alt="No Image" style="width: 50px; height: 50px; border-radius: 50%;">
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td><?php echo $row['qualification']; ?></td>
                                                        <td><?php echo $row['experience']; ?></td>
                                                        <td><?php echo $row['specialization']; ?></td>
                                                        <td><?php echo isset($row['address']) ? $row['address'] : 'N/A'; ?></td>
                                                        <td><?php echo isset($row['joining_date']) ? date('d-m-Y', strtotime($row['joining_date'])) : 'N/A'; ?></td>
                                                        <td><?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning btn-sm" 
                                                                onclick="editEducator(<?php echo $row['id']; ?>)">Edit</button>
                                                            <a href="controller/educator.php?delete=<?php echo $row['id']; ?>" 
                                                                class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Are you sure you want to delete this educator?')">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else {
                                                echo "<tr><td colspan='11' class='text-center'>No educators found</td></tr>";
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

        <!-- Add Educator Modal -->
        <div class="modal fade" id="addEducatorModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Educator</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/educator.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" class="form-control" name="qualification" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" name="experience" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" class="form-control" name="specialization" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Joining Date</label>
                                <input type="date" class="form-control" name="joining_date">
                            </div>
                            <div class="mb-3">
                             <lable class="form-label">Image</lable>
                             <input type="file" class="form-control" name="image" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_educator" class="btn btn-primary">Add Educator</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Educator Modal -->
        <div class="modal fade" id="editEducatorModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Educator</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="controller/educator.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="educator_id" id="edit_educator_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" id="edit_phone" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" class="form-control" name="qualification" id="edit_qualification" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" name="experience" id="edit_experience" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" class="form-control" name="specialization" id="edit_specialization" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="edit_address" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Joining Date</label>
                                <input type="date" class="form-control" name="joining_date" id="edit_joining_date">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" id="edit_image">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_educator" class="btn btn-primary">Update Educator</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <script src="public/assets/vendor/global/global.min.js"></script>
    <script src="public/assets/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="public/assets/vendor/apexchart/apexchart.js"></script>
    <script src="public/assets/js/dashboard/dashboard-1.js"></script>
    <script src="public/assets/vendor/owl-carousel/owl.carousel.js"></script>
    <script src="public/assets/vendor/bootstrap-datetimepicker/js/moment.js"></script>
    <script src="public/assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="public/assets/js/custom.min.js"></script>
    <script src="public/assets/js/dlabnav-init.js"></script>
    <script src="public/assets/js/demo.js"></script>

    <script>
    function editEducator(id) {
        // Make an AJAX call to get educator details
        $.ajax({
            url: 'controller/educator.php',
            type: 'POST',
            data: {get_educator: id},
            dataType: 'json',
            success: function(response) {
                $('#edit_educator_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_email').val(response.email);
                $('#edit_phone').val(response.phone);
                $('#edit_qualification').val(response.qualification);
                $('#edit_experience').val(response.experience);
                $('#edit_specialization').val(response.specialization);
                $('#edit_address').val(response.address);
                $('#edit_joining_date').val(response.joining_date);
                $('#edit_status').val(response.status);
                $('#editEducatorModal').modal('show');
            }
        });
    }
    </script>
</body>
</html>