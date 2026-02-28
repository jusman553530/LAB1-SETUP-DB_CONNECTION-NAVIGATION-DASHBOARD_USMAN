<?php
// Show errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "../db.php";

// Query payments with client and booking info
$sql = "
SELECT p.*, b.booking_date, c.full_name, b.total_cost
FROM payments p
JOIN bookings b ON p.booking_id = b.booking_id
JOIN clients c ON b.client_id = c.client_id
ORDER BY p.payment_id DESC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Calculate total revenue
$total_sql = "SELECT SUM(amount_paid) as total FROM payments";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_revenue = $total_row['total'] ?? 0;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>
<?php include "../nav.php"; ?>

<h2 class="payments-heading">Payments</h2>

<!-- Summary Cards -->
<div class="payments-summary">
    <div class="payments-summary-card">
        <div class="payments-summary-label">Total Payments</div>
        <div class="payments-summary-value"><?php echo mysqli_num_rows($result); ?></div>
    </div>
    <div class="payments-summary-card">
        <div class="payments-summary-label">Total Revenue</div>
        <div class="payments-summary-value">₱<?php echo number_format($total_revenue, 2); ?></div>
    </div>
    <div class="payments-summary-card">
        <div class="payments-summary-label">Average Payment</div>
        <div class="payments-summary-value">
            ₱<?php echo mysqli_num_rows($result) > 0 ? number_format($total_revenue / mysqli_num_rows($result), 2) : '0.00'; ?>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="payments-table-container">
    <table class="payments-table">
        <thead>
            <tr bgcolor = #e7671d>
                <th class="payments-id-col">ID</th>
                <th class="payments-client-col">Client</th>
                <th class="payments-booking-col">Booking #</th>
                <th class="payments-amount-col">Amount</th>
                <th class="payments-method-col">Payment Method</th>
                <th class="payments-date-col">Date & Time</th>
                <th class="payments-status-col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($p = mysqli_fetch_assoc($result)): 
                    // Determine payment status (you can adjust this logic)
                    $payment_date = strtotime($p['payment_date']);
                    $now = time();
                    $days_diff = floor(($now - $payment_date) / (60 * 60 * 24));
                    
                    if ($days_diff <= 1) {
                        $status = "Recent";
                        $status_class = "recent";
                    } elseif ($days_diff <= 7) {
                        $status = "This Week";
                        $status_class = "week";
                    } else {
                        $status = "Older";
                        $status_class = "older";
                    }
                ?>
                    <tr class="payments-row">
                        <td class="payments-id">#<?php echo $p['payment_id']; ?></td>
                        <td class="payments-client"><?php echo htmlspecialchars($p['full_name']); ?></td>
                        <td class="payments-booking">
                            <a href="bookings_view.php?id=<?php echo $p['booking_id']; ?>" class="payments-booking-link">
                                #<?php echo $p['booking_id']; ?>
                            </a>
                        </td>
                        <td class="payments-amount">₱<?php echo number_format($p['amount_paid'], 2); ?></td>
                        <td class="payments-method">
                            <span class="payments-method-badge payments-method-<?php echo strtolower($p['method']); ?>">
                                <?php echo htmlspecialchars($p['method']); ?>
                            </span>
                        </td>
                        <td class="payments-date">
                            <?php echo date('M d, Y', strtotime($p['payment_date'])); ?>
                            <span class="payments-time"><?php echo date('h:i A', strtotime($p['payment_date'])); ?></span>
                        </td>
                        <td class="payments-status">
                            <span class="payments-status-badge payments-status-<?php echo $status_class; ?>">
                                <?php echo $status; ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="payments-empty-row">No payments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Export Options (Optional) -->
<div class="payments-actions">
    <a href="payments_export.php" class="payments-export-btn">Export to CSV</a>
    <a href="payments_report.php" class="payments-report-btn">View Report</a>
</div>

</body>
</html>