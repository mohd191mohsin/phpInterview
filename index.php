<?php
require_once('includes/layouts/header.php');
require_once('includes/config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the user is logged in

if (isset($_SESSION['user_id'])) {
  // Check if the user is admin
  if ($_SESSION['is_admin'] == 1) {
  header('Location: admin_dashboard.php'); // Redirect to dashboard or home page
  exit;
}
}
// Example: Fetch user details from database based on user_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Handle error or no user found
    echo '<div class="alert alert-danger" role="alert">Error: User not found!</div>';
}

?>

<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Profile</h5>
                    <p class="card-text">
                        Username: <?php echo htmlspecialchars($user['username']); ?><br>
                        Email: <?php echo htmlspecialchars($user['email']); ?>
                    </p>
                    <a href="edit.php" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once('includes/layouts/footer.php');
?>
