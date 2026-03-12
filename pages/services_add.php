<?php
include "../db.php";
 
$message = "";
 
if (isset($_POST['save_service'])) {  // Changed name
  $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $hourly_rate = mysqli_real_escape_string($conn, $_POST['hourly_rate']);
  $is_active = mysqli_real_escape_string($conn, $_POST['is_active']);
 
  // simple validation
  if ($service_name == "" || $hourly_rate == "") {
    $message = "Service name and hourly rate are required!";
  } else if (!is_numeric($hourly_rate) || $hourly_rate <= 0) {
    $message = "Hourly rate must be a number greater than 0.";
  } else {
    $sql = "INSERT INTO services (service_name, description, hourly_rate, is_active)
            VALUES ('$service_name', '$description', '$hourly_rate', '$is_active')";
    mysqli_query($conn, $sql);
 
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
  <title>Add Service</title>
  <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>
 
<h2 class="add-service-heading">Add New Service</h2>

<?php if($message != ""): ?>
  <div class="add-service-error"><?php echo $message; ?></div>
<?php endif; ?>
 
<form method="post" class="add-service-form">
  <div class="form-group">
    <label for="add_service_name">Service Name *</label>
    <input type="text" 
           name="service_name" 
           id="add_service_name" 
           class="add-service-input" 
           value="<?php echo isset($_POST['service_name']) ? htmlspecialchars($_POST['service_name']) : ''; ?>" 
           required>
  </div>
 
  <div class="form-group">
    <label for="add_service_desc">Description</label>
    <textarea name="description" 
              id="add_service_desc" 
              class="add-service-textarea" 
              rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
  </div>
 
  <div class="form-group">
    <label for="add_service_rate">Hourly Rate (₱) *</label>
    <input type="number" 
           name="hourly_rate" 
           id="add_service_rate" 
           class="add-service-input" 
           value="<?php echo isset($_POST['hourly_rate']) ? htmlspecialchars($_POST['hourly_rate']) : ''; ?>" 
           step="0.01" 
           min="0.01" 
           required>
  </div>
 
  <div class="form-group">
    <label for="add_service_status">Status</label>
    <select name="is_active" id="add_service_status" class="add-service-select">
      <option value="1" <?php echo (isset($_POST['is_active']) && $_POST['is_active'] == 1) ? 'selected' : ''; ?>>Active</option>
      <option value="0" <?php echo (isset($_POST['is_active']) && $_POST['is_active'] == 0) ? 'selected' : ''; ?>>Inactive</option>
    </select>
  </div>
 
  <div class="button-group">
    <button type="submit" name="save_service" class="add-service-btn">Save Service</button>
    <a href="services_list.php" class="back-link">← Back to Services</a>
  </div>
</form>
 
</body>
</html>