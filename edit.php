<?php
require_once('includes/layouts/header.php');
require_once('includes/config.php');

// Check if the user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch current user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Handle error or no user found
    echo '<div class="alert alert-danger" role="alert">Error: User not found!</div>';
}

// Initialize variables for form submission
$username = $user['username'];
$email = $user['email'];
$success = false;
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    // Perform update if inputs are valid
    if (!empty($new_username) && !empty($new_email)) {
        $update_sql = "UPDATE Users SET username = '$new_username', email = '$new_email' WHERE id = '$user_id'";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            $success = true;
            // Refresh user details after update
            $user['username'] = $new_username;
            $user['email'] = $new_email;
            header('Location: index.php');

        } else {
            $error = 'Error updating profile. Please try again.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}

?>

<div class="container mt-5">
    <h2>Edit Profile</h2>
    <?php if ($success) : ?>
        <div class="alert alert-success" role="alert">
            Profile updated successfully.
        </div>
    <?php elseif ($error) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<?php
require_once('includes/layouts/footer.php');
?>
