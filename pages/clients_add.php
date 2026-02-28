<?php
include "../db.php";
 
$message = "";
 
if (isset($_POST['save_client'])) {  // Changed from 'save'
  $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
 
  if ($full_name == "" || $email == "") {
    $message = "Name and Email are required!";
  } else {
    $sql = "INSERT INTO clients (full_name, email, phone, address)
            VALUES ('$full_name', '$email', '$phone', '$address')";
    
    if(mysqli_query($conn, $sql)) {
      header("Location: clients_list.php");
      exit;
    } else {
      $message = "Error: " . mysqli_error($conn);
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Client</title>
  <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>
 
<h2>Add Client</h2>

<?php if($message != ""): ?>
  <div class="error-message"><?php echo $message; ?></div>
<?php endif; ?>
 
<form method="post" class="add-form" bgcolor = #ffffff>  <!-- Added unique class -->
  <label for="add_full_name">Full Name *</label>  <!-- Added unique for attribute -->
  <input type="text" 
         id="add_full_name" 
         name="full_name" 
         value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" 
         required>
 
  <label for="add_email">Email *</label>  <!-- Added unique for attribute -->
  <input type="email" 
         id="add_email" 
         name="email" 
         value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
         required>
 
  <label for="add_phone">Phone</label>  <!-- Added unique for attribute -->
  <input type="text" 
         id="add_phone" 
         name="phone" 
         value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
 
  <label for="add_address">Address</label>  <!-- Added unique for attribute -->
  <input type="text" 
         id="add_address" 
         name="address" 
         value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
 
  <div class="button-group">
    <button type="submit" name="save_client" class="save-btn">Save Client</button>  <!-- Changed name and added class -->
    <a href="clients_list.php" class="back-link">← Back to List</a>
  </div>
</form>

</body>
</html>