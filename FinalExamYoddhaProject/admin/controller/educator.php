<?php
include '../../config.php';
$admin = new Admin();

// Create uploads directory if it doesn't exist
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Get educator details for editing
if (isset($_POST['get_educator'])) {
    $id = $_POST['get_educator'];
    $query = $admin->ret("SELECT * FROM `educators` WHERE `id` = '$id'");
    $educator = $query->fetch(PDO::FETCH_ASSOC);
    echo json_encode($educator);
    exit;
}

// Add new educator
if (isset($_POST['add_educator'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $qualification = trim($_POST['qualification']);
    $experience = intval($_POST['experience']);
    $specialization = trim($_POST['specialization']);
    $address = trim($_POST['address']);
    $joining_date = $_POST['joining_date'];
    $password=md5($_POST['password']);
    
    // Handle image upload
    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = time() . '_' . $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $extensions = array("jpeg", "jpg", "png");
        
        if(in_array($file_ext, $extensions)) {
            if(move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $image = $file_name;
            }
        }
    }

    // Check if email already exists
    $checkQuery = $admin->ret("SELECT * FROM `educators` WHERE `email` = '$email'");
    if ($checkQuery->rowCount() > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='../manage_educators.php';</script>";
        exit;
    }

    // Insert into database
    $query = $admin->cud("INSERT INTO `educators` 
        (`name`, `email`, `phone`, `qualification`, `experience`, `specialization`, `address`, `joining_date`, `status`, `image`, `created_at`,`password`) 
        VALUES ('$name', '$email', '$phone', '$qualification', '$experience', '$specialization', '$address', '$joining_date', 1, '$image', NOW(),'$password')", 
        "Inserted");

    if ($query) {
        echo "<script>alert('Educator added successfully!'); window.location.href='../manage_educators.php';</script>";
    } else {
        echo "<script>alert('Error adding educator. Try again!'); window.location.href='../manage_educators.php';</script>";
    }
}

// Update educator
if (isset($_POST['update_educator'])) {
    $educator_id = intval($_POST['educator_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $qualification = trim($_POST['qualification']);
    $experience = intval($_POST['experience']);
    $specialization = trim($_POST['specialization']);
    $address = trim($_POST['address']);
    $joining_date = $_POST['joining_date'];
    $status = intval($_POST['status']);

    // Handle image upload for update
    $image_update = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Get old image to delete
        $old_image_query = $admin->ret("SELECT `image` FROM `educators` WHERE `id` = '$educator_id'");
        $old_image = $old_image_query->fetch(PDO::FETCH_ASSOC);
        
        // Delete old image if exists
        if(!empty($old_image['image']) && file_exists($upload_dir . $old_image['image'])) {
            unlink($upload_dir . $old_image['image']);
        }
        
        $file_name = time() . '_' . $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $extensions = array("jpeg", "jpg", "png");
        
        if(in_array($file_ext, $extensions)) {
            if(move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $image_update = ", `image` = '$file_name'";
            }
        }
    }

    // Check if email exists for other educators
    $checkQuery = $admin->ret("SELECT * FROM `educators` WHERE `email` = '$email' AND `id` != '$educator_id'");
    if ($checkQuery->rowCount() > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='../manage_educators.php';</script>";
        exit;
    }

    // Update the educator
    $query = $admin->cud("UPDATE `educators` SET 
        `name` = '$name',
        `email` = '$email',
        `phone` = '$phone',
        `qualification` = '$qualification',
        `experience` = '$experience',
        `specialization` = '$specialization',
        `address` = '$address',
        `joining_date` = '$joining_date',
        `status` = '$status'
        $image_update
        WHERE `id` = '$educator_id'", "Updated");

    if ($query) {
        echo "<script>alert('Educator updated successfully!'); window.location.href='../manage_educators.php';</script>";
    } else {
        echo "<script>alert('Error updating educator. Try again!'); window.location.href='../manage_educators.php';</script>";
    }
}

// Delete educator
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Get image name before deleting record
    $image_query = $admin->ret("SELECT `image` FROM `educators` WHERE `id` = '$id'");
    $image_data = $image_query->fetch(PDO::FETCH_ASSOC);
    
    // Delete the educator record
    $query = $admin->cud("DELETE FROM `educators` WHERE `id` = '$id'", "Deleted");
    
    // If deletion successful and image exists, delete the image file
    if ($query && !empty($image_data['image'])) {
        $image_path = $upload_dir . $image_data['image'];
        if(file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    if ($query) {
        echo "<script>alert('Educator deleted successfully!'); window.location.href='../manage_educators.php';</script>";
    } else {
        echo "<script>alert('Error deleting educator. Try again!'); window.location.href='../manage_educators.php';</script>";
    }
}
?>