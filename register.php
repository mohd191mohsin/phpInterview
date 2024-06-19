<?php
require_once('includes/layouts/header.php');
require_once('includes/config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Success = false;
$Error = false;
$name = $email = ''; 

if($_SERVER["REQUEST_METHOD"] == 'POST'){
    // Capture form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirmation']);

    // Validate form fields
    if(empty($name) && empty($email) && empty($password) && empty($password_confirm)){
        $Error = ' All fields are required!!';
    } elseif(empty($name)){
        $Error = ' Name is required!!';
    } elseif(empty($email)){
        $Error = ' Email is required!!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $Error = ' Please enter a valid email address!!';
    } elseif(empty($password)){
        $Error = ' Password is required!!';
    } elseif(strlen($password) < 8){
        $Error = ' Password should be at least 8 characters long!!';
    } elseif($password !== $password_confirm){
        $Error = ' Passwords do not match!!';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO `Users` (`username`, `email`, `password`) VALUES ('$name', '$email', '$hashed_password')";
        $result = mysqli_query($conn, $sql);

        if($result){
            $Success = true;
            // Reset form fields after successful registration, except passwords
            $name = $email = '';
        } else {
            $Error = 'Error executing query: ' . mysqli_error($conn);
        }
    }
}
?>
<?php
// Display success or error messages
if($Success){
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> You have registered successfully. You can now login.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if($Error){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong>'.$Error.'
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center"><h3>Register</h3></div>

                <div class="card-body">
                    <form method="POST" action="register.php" novalidate>
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">User name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required autocomplete="name" autofocus>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required autocomplete="email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once('includes/layouts/footer.php');
?>
