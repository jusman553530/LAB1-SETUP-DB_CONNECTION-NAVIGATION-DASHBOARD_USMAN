<?php
include "../db.php";

/* ============================
   SOFT DELETE (Deactivate)
   ============================ */
if (isset($_GET['delete_id'])) {
  $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
  
  // Soft delete (set is_active to 0)
  mysqli_query($conn, "UPDATE services SET is_active=0 WHERE service_id=$delete_id");
  
  header("Location: services_list.php");
  exit;
}

/* ============================
   FETCH ALL SERVICES
   ============================ */
$result = mysqli_query($conn, "SELECT * FROM services ORDER BY service_id DESC");
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services</title>
  <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<h2 class="services-list-heading">Services Management</h2>

<p>
  <a href="services_add.php" class="services-list-add-btn">+ Add New Service</a>
</p>

<div class="services-list-table-container">
  <table class="services-list-table">
    <thead>
      <tr bgcolor = #e7671d>
        <th class="services-list-id">ID</th>
        <th class="services-list-name">Service Name</th>
        <th class="services-list-rate">Hourly Rate</th>
        <th class="services-list-status">Status</th>
        <th class="services-list-action">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
          <tr class="services-list-row <?php echo ($row['is_active'] == 0) ? 'services-list-inactive' : ''; ?>">
            <td class="services-list-id"><?php echo $row['service_id']; ?></td>
            <td class="services-list-name"><?php echo htmlspecialchars($row['service_name']); ?></td>
            <td class="services-list-rate">₱<?php echo number_format($row['hourly_rate'], 2); ?></td>
            <td class="services-list-status">
              <?php if ($row['is_active'] == 1): ?>
                <span class="services-list-badge services-list-active">Active</span>
              <?php else: ?>
                <span class="services-list-badge services-list-inactive-badge">Inactive</span>
              <?php endif; ?>
            </td>
            <td class="services-list-action">
              <a href="services_edit.php?id=<?php echo $row['service_id']; ?>" class="services-list-edit">Edit</a>
              
              <?php if ($row['is_active'] == 1): ?>
                <span class="services-list-separator">|</span>
                <a href="services_list.php?delete_id=<?php echo $row['service_id']; ?>"
                   class="services-list-delete"
                   onclick="return confirm('Deactivate this service?')">
                   Deactivate
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php } ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="services-list-empty">No services found. <a href="services_add.php">Add your first service</a>.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>