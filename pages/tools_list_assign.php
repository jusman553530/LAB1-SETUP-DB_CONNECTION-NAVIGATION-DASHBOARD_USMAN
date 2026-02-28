<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../db.php";
 
$message = "";
$message_type = ""; // success or error

// ASSIGN TOOL
if (isset($_POST['assign_tool'])) {  // Changed name
  $booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
  $tool_id = mysqli_real_escape_string($conn, $_POST['tool_id']);
  $qty = mysqli_real_escape_string($conn, $_POST['qty_used']);
 
  $toolRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT quantity_available FROM tools WHERE tool_id=$tool_id"));
 
  if ($qty > $toolRow['quantity_available']) {
    $message = "Not enough available tools! Only " . $toolRow['quantity_available'] . " left.";
    $message_type = "error";
  } else {
    mysqli_query($conn, "INSERT INTO booking_tools (booking_id, tool_id, qty_used)
      VALUES ($booking_id, $tool_id, $qty)");
 
    mysqli_query($conn, "UPDATE tools SET quantity_available = quantity_available - $qty WHERE tool_id=$tool_id");
 
    $message = "Tool assigned successfully to Booking #" . $booking_id . "!";
    $message_type = "success";
  }
}

// Get data with refreshed values after assignment
$tools = mysqli_query($conn, "SELECT * FROM tools ORDER BY tool_name ASC");
$bookings = mysqli_query($conn, "SELECT booking_id FROM bookings ORDER BY booking_id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tools & Inventory</title>
  <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>
 
<h2 class="tools-heading">Tools & Inventory Management</h2>

<!-- Message display -->
<?php if($message != ""): ?>
  <div class="tools-message tools-message-<?php echo $message_type; ?>">
    <?php echo $message; ?>
  </div>
<?php endif; ?>
 
<!-- Tools Inventory Table -->
<div class="tools-section">
  <h3 class="tools-subheading">Available Tools Inventory</h3>
  
  <div class="tools-table-container">
    <table class="tools-table">
      <thead>
        <tr bgcolor=#e7671d>
          <th class="tools-name-col">Tool Name</th>
          <th class="tools-total-col">Total Quantity</th>
          <th class="tools-avail-col">Available</th>
          <th class="tools-status-col">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while($t = mysqli_fetch_assoc($tools)) { 
          $status = ($t['quantity_available'] > 0) ? "In Stock" : "Out of Stock";
          $status_class = ($t['quantity_available'] > 0) ? "in-stock" : "out-of-stock";
        ?>
          <tr class="tools-row">
            <td class="tools-name"><?php echo htmlspecialchars($t['tool_name']); ?></td>
            <td class="tools-total"><?php echo $t['quantity_total']; ?></td>
            <td class="tools-avail <?php echo ($t['quantity_available'] < 3) ? 'tools-low-stock' : ''; ?>">
              <?php echo $t['quantity_available']; ?>
            </td>
            <td class="tools-status">
              <span class="tools-status-badge tools-<?php echo $status_class; ?>">
                <?php echo $status; ?>
              </span>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Assign Tool Section -->
<div class="tools-section">
  <h3 class="tools-subheading">Assign Tool to Booking</h3>
  
  <form method="post" class="tools-assign-form">
    <div class="form-group">
      <label for="tools_booking_id">Select Booking *</label>
      <select name="booking_id" id="tools_booking_id" class="tools-select" required>
        <option value="">-- Choose a booking --</option>
        <?php while($b = mysqli_fetch_assoc($bookings)) { ?>
          <option value="<?php echo $b['booking_id']; ?>">Booking #<?php echo $b['booking_id']; ?></option>
        <?php } ?>
      </select>
    </div>
  
    <div class="form-group">
      <label for="tools_tool_id">Select Tool *</label>
      <select name="tool_id" id="tools_tool_id" class="tools-select" required>
        <option value="">-- Choose a tool --</option>
        <?php
          $tools2 = mysqli_query($conn, "SELECT * FROM tools ORDER BY tool_name ASC");
          while($t2 = mysqli_fetch_assoc($tools2)) {
            $disabled = ($t2['quantity_available'] == 0) ? "disabled" : "";
        ?>
          <option value="<?php echo $t2['tool_id']; ?>" <?php echo $disabled; ?>>
            <?php echo htmlspecialchars($t2['tool_name']); ?> 
            (Available: <?php echo $t2['quantity_available']; ?>)
            <?php echo ($t2['quantity_available'] == 0) ? " - OUT OF STOCK" : ""; ?>
          </option>
        <?php } ?>
      </select>
      <small class="tools-hint">Tools with 0 available are disabled</small>
    </div>
  
    <div class="form-group">
      <label for="tools_qty">Quantity to Assign *</label>
      <input type="number" 
             name="qty_used" 
             id="tools_qty" 
             class="tools-input" 
             min="1" 
             value="1" 
             required>
    </div>
  
    <div class="button-group">
      <button type="submit" name="assign_tool" class="tools-assign-btn">Assign Tool to Booking</button>
      <a href="bookings_list.php" class="back-link">View Bookings</a>
    </div>
  </form>
</div>

<!-- Recent Assignments Section (Optional) -->
<?php
$recent = mysqli_query($conn, "
  SELECT bt.*, t.tool_name, b.booking_id 
  FROM booking_tools bt
  JOIN tools t ON bt.tool_id = t.tool_id
  JOIN bookings b ON bt.booking_id = b.booking_id
  ORDER BY bt.created_at DESC 
  LIMIT 5
");
?>
<?php if(mysqli_num_rows($recent) > 0): ?>
<div class="tools-section">
  <h3 class="tools-subheading">Recent Tool Assignments</h3>
  
  <div class="tools-table-container">
    <table class="tools-table tools-recent-table">
      <thead>
        <tr>
          <th>Booking #</th>
          <th>Tool</th>
          <th>Quantity</th>
          <th>Assigned Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while($r = mysqli_fetch_assoc($recent)) { ?>
          <tr>
            <td>#<?php echo $r['booking_id']; ?></td>
            <td><?php echo htmlspecialchars($r['tool_name']); ?></td>
            <td><?php echo $r['qty_used']; ?></td>
            <td><?php echo date('M d, Y h:i A', strtotime($r['created_at'])); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

</body>
</html>