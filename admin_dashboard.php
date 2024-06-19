<?php
require_once('includes/layouts/header.php');
require_once('includes/config.php');

// Check if the user is logged in and is an admin
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit;
}

// Function to sanitize input to prevent SQL injection
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

// Initialize variables
$users = [];

// Fetch all users from the database
$sql = "SELECT * FROM `Users`";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Handle delete user functionality
if (isset($_POST['delete_user'])) {
    $user_id = sanitize($conn, $_POST['delete_user']);
    $delete_sql = "DELETE FROM `Users` WHERE `id` = '$user_id'";
    $delete_result = mysqli_query($conn, $delete_sql);

    if ($delete_result) {
        // Redirect to refresh user list after successful delete
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting user!</div>';
    }
}

?>

<div class="container pt-5">
    <h2 class="mb-4">User Management</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($users as $user) : ?>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($user['username']); ?></h5>
                        <p class="card-text">
                            Email: <?php echo htmlspecialchars($user['email']); ?>
                            <br>
                            User ID: <?php echo htmlspecialchars($user['id']); ?>
                        </p>
                        <div class="d-flex justify-content-start align-items-center">
                            <form method="POST" action="">
                                <input type="hidden" name="delete_user" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require_once('includes/layouts/footer.php');
?>
