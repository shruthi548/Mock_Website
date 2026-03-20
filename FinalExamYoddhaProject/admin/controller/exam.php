<?php
include '../../config.php';
$admin = new Admin();

// Get exam details for editing
if(isset($_POST['get_exam'])) {
    $id = $_POST['get_exam'];
    $query = $admin->ret("SELECT * FROM `exams` WHERE `id` = '$id'");
    $exam = $query->fetch(PDO::FETCH_ASSOC);
    echo json_encode($exam);
    exit;
}

// Add new exam
if(isset($_POST['add_exam'])) {
    $exam_name = trim($_POST['exam_name']);
    $exam_code = trim($_POST['exam_code']);
    $test_name_id = intval($_POST['test_name_id']);
    $duration = intval($_POST['duration']);
    $total_marks = intval($_POST['total_marks']);
    $pass_marks = intval($_POST['pass_marks']);
    $educator_id = !empty($_POST['educator_id']) ? intval($_POST['educator_id']) : null;
    $exam_type = trim($_POST['exam_type']);
    $description = trim($_POST['description']);
    $instructions = trim($_POST['instructions']);

    // Handle image upload
    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/exams/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
    }

    // Check if exam code already exists
    $checkQuery = $admin->ret("SELECT * FROM `exams` WHERE `exam_code` = '$exam_code'");
    if($checkQuery->rowCount() > 0) {
        echo "<script>alert('Exam code already exists!'); window.location.href='../manage_exams.php';</script>";
        exit;
    }

    // Insert into database
    $query = $admin->cud("INSERT INTO `exams` 
        (`exam_name`, `exam_code`, `test_name_id`, `duration`, `total_marks`, `pass_marks`, 
        `educator_id`, `exam_type`, `description`, `instructions`, `image`, `status`, `created_at`) 
        VALUES ('$exam_name', '$exam_code', '$test_name_id', '$duration', '$total_marks', '$pass_marks', 
        " . ($educator_id ? "'$educator_id'" : "NULL") . ", '$exam_type', '$description', '$instructions', 
        '$image', 1, NOW())", "Inserted");

    if($query) {
        echo "<script>alert('Exam added successfully!'); window.location.href='../manage_exams.php';</script>";
    } else {
        echo "<script>alert('Error adding exam. Try again!'); window.location.href='../manage_exams.php';</script>";
    }
}

// Update exam
if(isset($_POST['update_exam'])) {
    $exam_id = intval($_POST['exam_id']);
    $exam_name = trim($_POST['exam_name']);
    $exam_code = trim($_POST['exam_code']);
    $test_name_id = intval($_POST['test_name_id']);
    $duration = intval($_POST['duration']);
    $total_marks = intval($_POST['total_marks']);
    $pass_marks = intval($_POST['pass_marks']);
    $educator_id = !empty($_POST['educator_id']) ? intval($_POST['educator_id']) : null;
    $exam_type = trim($_POST['exam_type']);
    $description = trim($_POST['description']);
    $instructions = trim($_POST['instructions']);
    $status = intval($_POST['status']);

    // Handle image upload
    $image_sql = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/exams/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
        $image_sql = ", `image` = '$image'";
    }

    // Check if exam code exists for other exams
    $checkQuery = $admin->ret("SELECT * FROM `exams` WHERE `exam_code` = '$exam_code' AND `id` != '$exam_id'");
    if($checkQuery->rowCount() > 0) {
        echo "<script>alert('Exam code already exists!'); window.location.href='../manage_exams.php';</script>";
        exit;
    }

    // Update the exam
    $query = $admin->cud("UPDATE `exams` SET 
        `exam_name` = '$exam_name',
        `exam_code` = '$exam_code',
        `test_name_id` = '$test_name_id',
        `duration` = '$duration',
        `total_marks` = '$total_marks',
        `pass_marks` = '$pass_marks',
        `educator_id` = " . ($educator_id ? "'$educator_id'" : "NULL") . ",
        `exam_type` = '$exam_type',
        `description` = '$description',
        `instructions` = '$instructions',
        `status` = '$status'
        $image_sql
        WHERE `id` = '$exam_id'", "Updated");

    if($query) {
        echo "<script>alert('Exam updated successfully!'); window.location.href='../manage_exams.php';</script>";
    } else {
        echo "<script>alert('Error updating exam. Try again!'); window.location.href='../manage_exams.php';</script>";
    }
}

// Delete exam
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Delete the exam record
    $query = $admin->cud("DELETE FROM `exams` WHERE `id` = '$id'", "Deleted");
    
    if($query) {
        echo "<script>alert('Exam deleted successfully!'); window.location.href='../manage_exams.php';</script>";
    } else {
        echo "<script>alert('Error deleting exam. Try again!'); window.location.href='../manage_exams.php';</script>";
    }
}
?>