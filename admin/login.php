<?php
require('../config.php');

session_start();
if(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true){
    redirect('index.php');
}

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM lab_admin_users WHERE username = ? OR email = ? LIMIT 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        if(password_verify($password, $row['password'])) {
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminId'] = $row['id'];
            $_SESSION['adminUsername'] = $row['username'];
            redirect('index.php');
        } else {
            $error = "Incorrect Password!";
        }
    } else {
        $error = "Admin Account not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel - BSU LAMS</title>
    <?php require('../inc/link.php'); ?>
    <style>
        body { background: linear-gradient(135deg, #f8f9fa 50%, #e9ecef 50%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 450px; width: 100%; border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .login-header { background: var(--bsu-red); color: white; padding: 30px; border-radius: 12px 12px 0 0; text-align: center; }
    </style>
</head>
<body>
    <div class="login-card bg-white p-0">
        <div class="login-header">
            <h3 class="fw-bold m-0"><i class="bi bi-shield-lock me-2"></i>Admin Area</h3>
            <p class="mb-0 mt-2 text-white-50">BSU Laboratory Asset Management System</p>
        </div>
        <div class="p-4">
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-medium text-secondary">Username or Email</label>
                    <input name="username" required type="text" class="form-control form-control-lg bg-light" placeholder="Enter username">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium text-secondary">Password</label>
                    <input name="password" required type="password" class="form-control form-control-lg bg-light" placeholder="Enter password">
                </div>
                <button name="login" type="submit" class="btn btn-danger btn-lg w-100 shadow mb-3">LOGIN</button>
            </form>
            <div class="text-center mt-3">
                <a href="<?= BASE_URL ?>/index.php" class="text-decoration-none text-secondary"><i class="bi bi-arrow-left me-1"></i> Back to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>
