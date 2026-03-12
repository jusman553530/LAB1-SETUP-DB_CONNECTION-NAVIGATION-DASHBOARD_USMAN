<?php
$currentFile = $_SERVER['REQUEST_URI']; // full request URI
?>

<nav class="main-nav">
  <a href="/assessment_beginner/index.php" class="<?= (strpos($currentFile, 'index.php') !== false || $currentFile == '/assessment_beginner/' ) ? 'active' : '' ?>">Dashboard</a>
  <a href="/assessment_beginner/pages/clients_list.php" class="<?= strpos($currentFile, 'clients_list.php') !== false ? 'active' : '' ?>">Clients</a>
  <a href="/assessment_beginner/pages/services_list.php" class="<?= strpos($currentFile, 'services_list.php') !== false ? 'active' : '' ?>">Services</a>
  <a href="/assessment_beginner/pages/bookings_list.php" class="<?= strpos($currentFile, 'bookings_list.php') !== false ? 'active' : '' ?>">Bookings</a>
  <a href="/assessment_beginner/pages/tools_list_assign.php" class="<?= strpos($currentFile, 'tools_list_assign.php') !== false ? 'active' : '' ?>">Tools</a>
  <a href="/assessment_beginner/pages/payments_list.php" class="<?= strpos($currentFile, 'payments_list.php') !== false ? 'active' : '' ?>">Payments</a>
</nav>