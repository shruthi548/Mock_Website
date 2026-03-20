<?php
include '../../config.php';
$admin = new Admin();

// Get test details for editing
if(isset($_POST['get_test'])) {
    $id = $_POST['get_test'];
    $query = $admin->ret("SELECT * FROM `test_names` WHERE `id` = '$id'");
    $test = $query->fetch(PDO::FETCH_ASSOC);
    echo json_encode($test);
    exit;
}

// Add new test
if(isset($_POST['add_test'])) {
    $test_name = trim($_POST['test_name']);
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);

    // Check if test name already exists
    $checkQuery = $admin->ret("SELECT * FROM `test_names` WHERE `test_name` = '$test_name'");
    if ($checkQuery->rowCount() > 0) {
        echo "<script>alert('Test name already exists!'); window.location.href='../manage_test_names.php';</script>";
        exit;
    }

    // Insert into database
    $query = $admin->cud("INSERT INTO `test_names` 
        (`test_name`, `subject`, `description`, `status`, `created_at`) 
        VALUES ('$test_name', '$subject', '$description', 1, NOW())", 
        "Inserted");

    if ($query) {
        echo "<script>alert('Test name added successfully!'); window.location.href='../manage_test_names.php';</script>";
    } else {
        echo "<script>alert('Error adding test name. Try again!'); window.location.href='../manage_test_names.php';</script>";
    }
}

// Update test
if(isset($_POST['update_test'])) {
    $test_id = intval($_POST['test_id']);
    $test_name = trim($_POST['test_name']);
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $status = intval($_POST['status']);

    // Check if test name exists for other tests
    $checkQuery = $admin->ret("SELECT * FROM `test_names` WHERE `test_name` = '$test_name' AND `id` != '$test_id'");
    if ($checkQuery->rowCount() > 0) {
        echo "<script>alert('Test name already exists!'); window.location.href='../manage_test_names.php';</script>";
        exit;
    }

    // Update the test
    $query = $admin->cud("UPDATE `test_names` SET 
        `test_name` = '$test_name',
        `subject` = '$subject',
        `description` = '$description',
        `status` = '$status'
        WHERE `id` = '$test_id'", "Updated");

    if ($query) {
        echo "<script>alert('Test name updated successfully!'); window.location.href='../manage_test_names.php';</script>";
    } else {
        echo "<script>alert('Error updating test name. Try again!'); window.location.href='../manage_test_names.php';</script>";
    }
}

// Delete test
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Delete the test record
    $query = $admin->cud("DELETE FROM `test_names` WHERE `id` = '$id'", "Deleted");
    
    if ($query) {
        echo "<script>alert('Test name deleted successfully!'); window.location.href='../manage_test_names.php';</script>";
    } else {
        echo "<script>alert('Error deleting test name. Try again!'); window.location.href='../manage_test_names.php';</script>";
    }
}
?>