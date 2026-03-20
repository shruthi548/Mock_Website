<?php
include '../config.php';
$admin=new Admin();

if(!isset($_SESSION['a_id'])){
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    
    
<!-- Mirrored from travl.dexignlab.com/codeigniter/demo/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Feb 2025 05:43:10 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="description" content="Travl - CodeIgniter Hotel Admin Dashboard Bootstrap Template" />
	<meta property="og:title" content="Travl - CodeIgniter Hotel Admin Dashboard Bootstrap Template" />
	<meta property="og:description" content="Travl - CodeIgniter Hotel Admin Dashboard Bootstrap Template" />
	<meta property="og:image" content="../social-image.html" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Admin</title>

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
	<!-- row -->
	<div class="container-fluid">
    <div class="row">
    <div class="col-xl-12">
    <div class="card">
    <div class="card-header">
        <h4 class="card-title">User List</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM `users`";
                    $result = $admin->ret($query);
                    if ($result->rowCount() > 0) {
                        $count = 1;
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row['u_name']; ?></td>
                                <td><?php echo $row['u_email']; ?></td>
                                <td><?php echo $row['u_phone']; ?></td>
                                <td>
                                    <a href="controller/user.php?del=<?php echo $row['u_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='5'>No data found</td></tr>";
                    }   ?>
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
    	
			<script src="public/assets/js/custom.min.js"></script>
			<script src="public/assets/js/dlabnav-init.js"></script>
			<script src="public/assets/js/demo.js"></script>



    <!--**********************************
        Main wrapper end
    ***********************************-->
</body>

<!-- Mirrored from travl.dexignlab.com/codeigniter/demo/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Feb 2025 05:43:35 GMT -->
</html>