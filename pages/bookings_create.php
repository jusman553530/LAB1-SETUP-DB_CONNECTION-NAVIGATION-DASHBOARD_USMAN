<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../db.php";
 
$clients = mysqli_query($conn, "SELECT * FROM clients ORDER BY full_name ASC");
$services = mysqli_query($conn, "SELECT * FROM services WHERE is_active=1 ORDER BY service_name ASC");
 
if (isset($_POST['create_booking'])) {  // Changed name
  $client_id = $_POST['client_id'];
  $service_id = $_POST['service_id'];
  $booking_date = $_POST['booking_date'];
  $hours = $_POST['hours'];
 
  // get service hourly rate
  $s = mysqli_fetch_assoc(mysqli_query($conn, "SELECT hourly_rate FROM services WHERE service_id=$service_id"));
  $rate = $s['hourly_rate'];
 
  $total = $rate * $hours;
 
  mysqli_query($conn, "INSERT INTO bookings (client_id, service_id, booking_date, hours, hourly_rate_snapshot, total_cost, status)
    VALUES ($client_id, $service_id, '$booking_date', $hours, $rate, $total, 'PENDING')");
 
  header("Location: bookings_list.php");
  exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Booking</title>
  <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>
 
<h2 class="create-booking-heading">Create New Booking</h2>
 
<form method="post" class="create-booking-form" bgcolor=#ffffff>
  <div class="form-group">
    <label for="booking_client">Select Client *</label>
    <select name="client_id" id="booking_client" class="booking-select" required>
      <option value="">-- Choose a client --</option>
      <?php while($c = mysqli_fetch_assoc($clients)) { ?>
        <option value="<?php echo $c['client_id']; ?>"><?php echo htmlspecialchars($c['full_name']); ?></option>
      <?php } ?>
    </select>
  </div>
 
  <div class="form-group">
    <label for="booking_service">Select Service *</label>
    <select name="service_id" id="booking_service" class="booking-select" required>
      <option value="">-- Choose a service --</option>
      <?php while($s = mysqli_fetch_assoc($services)) { ?>
        <option value="<?php echo $s['service_id']; ?>">
          <?php echo htmlspecialchars($s['service_name']); ?> (₱<?php echo number_format($s['hourly_rate'],2); ?>/hr)
        </option>
      <?php } ?>
    </select>
  </div>
 
  <div class="form-group">
    <label for="booking_date">Booking Date *</label>
    <input type="date" name="booking_date" id="booking_date" class="booking-input" required>
  </div>
 
  <div class="form-group">
    <label for="booking_hours">Number of Hours *</label>
    <input type="number" name="hours" id="booking_hours" min="1" value="1" class="booking-input" required>
  </div>
 
  <div class="button-group">
    <button type="submit" name="create_booking" class="booking-submit-btn">Create Booking</button>
    <a href="bookings_list.php" class="back-link">← Back to Bookings</a>
  </div>
</form>

<!-- Optional: Show total calculation preview (can be enhanced with JavaScript later) -->
<div class="booking-info">
  <p><strong>Note:</strong> Total cost will be calculated based on the service rate and hours.</p>
</div>

</body>
</html>