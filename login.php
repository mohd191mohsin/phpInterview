<?php
require_once('includes/layouts/header.php');
require_once('includes/config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Error = false;

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email and password
    if (empty($email) || empty($password)) {
        $Error = 'Email and password are required!!';
    } else {
        // Check if the user exists in the database
        $sql = "SELECT * FROM `Users` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, log in the user
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'] == 1;
                if ($user['is_admin'] == 1) {
                    $_SESSION['is_admin'] = true;
                    header('Location: admin_dashboard.php'); // Redirect to dashboard or home page
                    exit;
                }
                else{
                    header('Location: index.php'); // Redirect to dashboard or home page
                    exit;
                }
            } else {
                $Error = 'Incorrect password!!';
            }
        } else {
            $Error = 'User not found!!';
        }
    }
}
?>

<?php
if ($Error) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong> ' . $Error . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center"><h3>Login</h3></div>
                <div class="card-body">
                    <form method="POST" action="login.php" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once('includes/layouts/footer.php');
?>
