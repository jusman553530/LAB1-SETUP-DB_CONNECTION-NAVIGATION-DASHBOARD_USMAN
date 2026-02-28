<?php
include "../db.php";

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: clients_list.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Get client data
$get = mysqli_query($conn, "SELECT * FROM clients WHERE client_id = $id");

// Check if client exists
if(mysqli_num_rows($get) == 0) {
    header("Location: clients_list.php");
    exit;
}

$client = mysqli_fetch_assoc($get);

// Initialize message variable
$message = "";

if (isset($_POST['update'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
 
    if ($full_name == "" || $email == "") {
        $message = "Name and Email are required!";
    } else {
        $sql = "UPDATE clients
                SET full_name='$full_name', email='$email', phone='$phone', address='$address'
                WHERE client_id=$id";
        
        if(mysqli_query($conn, $sql)) {
            header("Location: clients_list.php");
            exit;
        } else {
            $message = "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>
 
<h2>Edit Client</h2>

<?php if($message != ""): ?>
    <div class="error-message" style="background-color: #fee9e7; color: #c62828; padding: 12px 20px; border-radius: 8px; border-left: 4px solid #c62828; margin-bottom: 20px;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
 
<form method="post">
    <label>Full Name *</label>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($client['full_name'] ?? ''); ?>" required>
 
    <label>Email *</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>" required>
 
    <label>Phone</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($client['phone'] ?? ''); ?>">
 
    <label>Address</label>
    <input type="text" name="address" value="<?php echo htmlspecialchars($client['address'] ?? ''); ?>">
 
    <div class="button-group">
        <button type="submit" name="update">Update Client</button>
        <a href="clients_list.php" class="back-link">← Back to List</a>
    </div>
</form>
</body>
</html>