<?php
require_once('includes/layouts/header.php');
require_once('includes/config.php');

// Check if the user is logged in and is an admin
if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Function to sanitize input to prevent SQL injection
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

// Initialize variables
$user_id = isset($_GET['id']) ? sanitize($conn, $_GET['id']) : null;
$username = '';
$email = '';

// Fetch user details
if ($user_id) {
    $sql = "SELECT * FROM `Users` WHERE `id` = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $username = $user['username'];
        $email = $user['email'];
    } else {
        echo '<div class="alert alert-danger" role="alert">User not found!</div>';
    }
}

// Handle form submission to update user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = sanitize($conn, $_POST['id']);
    $username = sanitize($conn, $_POST['username']);
    $email = sanitize($conn, $_POST['email']);

    $update_sql = "UPDATE `Users` SET `username` = '$username', `email` = '$email' WHERE `id` = '$user_id'";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        echo '<div class="alert alert-success" role="alert">User updated successfully.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error updating user!</div>';
    }
}

?>

<div class="container pt-5">
    <h2 class="mb-4">Edit User</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $user_id; ?>">
        <input type="hidden" id="user-id" name="id" value="<?php echo htmlspecialchars($user_id); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="update_user">Save Changes</button>
    </form>
</div>

<?php
require_once('includes/layouts/footer.php');
?>
