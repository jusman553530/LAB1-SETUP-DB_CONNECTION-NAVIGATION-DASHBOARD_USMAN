<?php
include "../db.php";
$result = mysqli_query($conn, "SELECT * FROM clients ORDER BY client_id DESC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link rel="stylesheet" href="/assessment_beginner/style.css">
</head>
<body>

<?php include "../nav.php"; ?>
 
<h2>Clients</h2>

<p><a href="clients_add.php" class="add-btn">+ Add Client</a></p>
 
<table class="clients-table">
  <thead>
    <tr bgcolor=#e7671d>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row['client_id']; ?></td>
        <td><?php echo $row['full_name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td>
          <a href="clients_edit.php?id=<?php echo $row['client_id']; ?>" class="edit-link">
            Edit
          </a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<?php if(mysqli_num_rows($result) == 0): ?>
  <p style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 8px;">
    No clients found. <a href="clients_add.php" style="color: #e7671d;">Add your first client</a>.
  </p>
<?php endif; ?>

</body>
</html>