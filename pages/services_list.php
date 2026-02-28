<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../db.php";
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
 
<h2 class="services-heading">Services</h2>  <!-- Changed: unique class -->

<!-- Table container -->
<div class="services-table-container">  <!-- Changed: unique class -->
  <table class="services-table">  <!-- Changed: unique class -->
    <thead>
      <tr bgcolor=#e7671d>
        <th class="services-id-col">ID</th>  <!-- Changed: unique class -->
        <th class="services-name-col">Service Name</th>  <!-- Changed: unique class -->
        <th class="services-rate-col">Hourly Rate</th>  <!-- Changed: unique class -->
        <th class="services-status-col">Status</th>  <!-- Changed: unique class -->
        <th class="services-action-col">Action</th>  <!-- Changed: unique class -->
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr class="services-row">  <!-- Changed: unique class -->
          <td class="services-id"><?php echo $row['service_id']; ?></td>
          <td class="services-name"><?php echo htmlspecialchars($row['service_name']); ?></td>
          <td class="services-rate">₱<?php echo number_format($row['hourly_rate'], 2); ?></td>
          <td class="services-status">
            <span class="status-badge-<?php echo $row['is_active'] ? 'active' : 'inactive'; ?>">  <!-- Changed: unique class -->
              <?php echo $row['is_active'] ? "Active" : "Inactive"; ?>
            </span>
          </td>
          <td class="services-action">  <!-- Changed: unique class -->
            <a href="services_edit.php?id=<?php echo $row['service_id']; ?>" class="services-edit-link">Edit</a>  <!-- Changed: unique class -->
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- Show message if no services -->
<?php if(mysqli_num_rows($result) == 0): ?>
  <div class="services-empty-message">  <!-- Changed: unique class -->
    <p>No services found. <a href="services_add.php" class="services-empty-link">Add your first service</a>.</p>
  </div>
<?php endif; ?>

</body>
</html>