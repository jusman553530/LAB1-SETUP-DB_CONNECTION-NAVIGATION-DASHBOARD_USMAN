<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../db.php";
 
$sql = "
SELECT b.*, c.full_name AS client_name, s.service_name
FROM bookings b
JOIN clients c ON b.client_id = c.client_id
JOIN services s ON b.service_id = s.service_id
ORDER BY b.booking_id DESC
";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bookings</title>
  <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>
 
<h2 class="bookings-heading">Bookings</h2>

<p><a href="bookings_create.php" class="bookings-add-btn">+ Create New Booking</a></p>
 
<div class="bookings-table-container">
  <table class="bookings-table">
    <thead>
      <tr bgcolor=#e7671d>
        <th class="bookings-id-col">ID</th>
        <th class="bookings-client-col">Client</th>
        <th class="bookings-service-col">Service</th>
        <th class="bookings-date-col">Date</th>
        <th class="bookings-hours-col">Hours</th>
        <th class="bookings-total-col">Total</th>
        <th class="bookings-status-col">Status</th>
        <th class="bookings-action-col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($b = mysqli_fetch_assoc($result)) { ?>
        <tr class="bookings-row">
          <td class="bookings-id"><?php echo $b['booking_id']; ?></td>
          <td class="bookings-client"><?php echo htmlspecialchars($b['client_name']); ?></td>
          <td class="bookings-service"><?php echo htmlspecialchars($b['service_name']); ?></td>
          <td class="bookings-date"><?php echo date('M d, Y', strtotime($b['booking_date'])); ?></td>
          <td class="bookings-hours"><?php echo $b['hours']; ?></td>
          <td class="bookings-total">₱<?php echo number_format($b['total_cost'], 2); ?></td>
          <td class="bookings-status">
            <span class="status-badge-<?php echo strtolower($b['status']); ?>">
              <?php echo $b['status']; ?>
            </span>
          </td>
          <td class="bookings-action">
            <a href="payment_process.php?booking_id=<?php echo $b['booking_id']; ?>" class="bookings-payment-link">Process Payment</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- Show message if no bookings -->
<?php if(mysqli_num_rows($result) == 0): ?>
  <div class="bookings-empty-message">
    <p>No bookings found. <a href="bookings_create.php" class="bookings-empty-link">Create your first booking</a>.</p>
  </div>
<?php endif; ?>

</body>
</html>