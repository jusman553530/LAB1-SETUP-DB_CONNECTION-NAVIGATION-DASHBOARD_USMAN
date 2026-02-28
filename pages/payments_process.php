<?php
// Enable errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection (adjust path if needed)
include "../db.php";

// Get booking_id safely
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

// Check if booking exists
$bookingQuery = mysqli_query($conn, "SELECT * FROM bookings WHERE booking_id = $booking_id");
if (!$bookingQuery || mysqli_num_rows($bookingQuery) == 0) {
    die("Booking not found.");
}

$booking = mysqli_fetch_assoc($bookingQuery);

// Compute total paid so far
$paidRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(amount_paid),0) AS paid FROM payments WHERE booking_id = $booking_id"));
$total_paid = $paidRow['paid'];

// Compute balance
$balance = $booking['total_cost'] - $total_paid;
$message = "";

// Handle form submission
if (isset($_POST['pay'])) {
    $amount = floatval($_POST['amount_paid']);
    $method = $_POST['method'];

    if ($amount <= 0) {
        $message = "Invalid amount!";
    } elseif ($amount > $balance) {
        $message = "Amount exceeds balance!";
    } else {
        // Insert payment
        $insert = mysqli_query($conn, "INSERT INTO payments (booking_id, amount_paid, method) VALUES ($booking_id, $amount, '$method')");
        if (!$insert) {
            die("Payment insert failed: " . mysqli_error($conn));
        }

        // Recompute total paid and balance
        $paidRow2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(amount_paid),0) AS paid FROM payments WHERE booking_id = $booking_id"));
        $new_balance = $booking['total_cost'] - $paidRow2['paid'];

        // Update booking status if fully paid
        if ($new_balance <= 0.009) {
            mysqli_query($conn, "UPDATE bookings SET status='PAID' WHERE booking_id=$booking_id");
        }

        // Redirect to bookings list
        header("Location: bookings_list.php");
        exit;
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Process Payment</title>
</head>
<body>
<?php include "../nav.php"; ?>

<h2>Process Payment (Booking #<?php echo $booking_id; ?>)</h2>

<p>Total Cost: ₱<?php echo number_format($booking['total_cost'],2); ?></p>
<p>Total Paid: ₱<?php echo number_format($total_paid,2); ?></p>
<p><b>Balance: ₱<?php echo number_format($balance,2); ?></b></p>

<p style="color:red;"><?php echo $message; ?></p>

<form method="post">
    <label>Amount Paid</label><br>
    <input type="number" name="amount_paid" step="0.01" required><br><br>

    <label>Method</label><br>
    <select name="method">
        <option value="CASH">CASH</option>
        <option value="GCASH">GCASH</option>
        <option value="CARD">CARD</option>
    </select><br><br>

    <button type="submit" name="pay">Save Payment</button>
</form>

</body>
</html>