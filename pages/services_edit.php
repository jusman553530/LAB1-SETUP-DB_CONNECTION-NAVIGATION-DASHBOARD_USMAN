<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../db.php";

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: services_list.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Get service data
$get = mysqli_query($conn, "SELECT * FROM services WHERE service_id = $id");

// Check if service exists
if(mysqli_num_rows($get) == 0) {
    header("Location: services_list.php");
    exit;
}

$service = mysqli_fetch_assoc($get);
$message = "";

if (isset($_POST['update_service'])) {  // Changed name
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $rate = mysqli_real_escape_string($conn, $_POST['hourly_rate']);
    $active = mysqli_real_escape_string($conn, $_POST['is_active']);
    
    if($name == "" || $rate == "") {
        $message = "Service Name and Hourly Rate are required!";
    } else {
        mysqli_query($conn, "UPDATE services
            SET service_name='$name', description='$desc', hourly_rate='$rate', is_active='$active'
            WHERE service_id=$id");
        
        header("Location: services_list.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>

<h2 class="edit-service-heading">Edit Service</h2>

<?php if($message != ""): ?>
    <div class="edit-service-error"><?php echo $message; ?></div>
<?php endif; ?>

<form method="post" class="edit-service-form" bgcolor = #ffffff>
    <div class="form-group">
        <label for="edit_service_name">Service Name *</label>
        <input type="text" 
               name="service_name" 
               id="edit_service_name" 
               class="edit-service-input" 
               value="<?php echo htmlspecialchars($service['service_name']); ?>" 
               required>
    </div>
    
    <div class="form-group">
        <label for="edit_service_desc">Description</label>
        <textarea name="description" 
                  id="edit_service_desc" 
                  class="edit-service-textarea" 
                  rows="4"><?php echo htmlspecialchars($service['description']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="edit_service_rate">Hourly Rate (₱) *</label>
        <input type="number" 
               name="hourly_rate" 
               id="edit_service_rate" 
               class="edit-service-input" 
               value="<?php echo $service['hourly_rate']; ?>" 
               step="0.01" 
               min="0" 
               required>
    </div>
    
    <div class="form-group">
        <label for="edit_service_status">Status</label>
        <select name="is_active" id="edit_service_status" class="edit-service-select">
            <option value="1" <?php if($service['is_active']==1) echo "selected"; ?>>Active</option>
            <option value="0" <?php if($service['is_active']==0) echo "selected"; ?>>Inactive</option>
        </select>
    </div>
    
    <div class="button-group">
        <button type="submit" name="update_service" class="edit-service-btn">Update Service</button>
        <a href="services_list.php" class="back-link">← Back to Services</a>
    </div>
</form>

</body>
</html>