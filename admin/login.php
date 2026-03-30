<?php
require('../config.php');

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
        body { 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
            position: relative;
        }
        
        /* Subtle background pulse animation */
        body::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(220,53,69,0.06) 0%, transparent 60%);
            animation: pulseBg 8s infinite alternate ease-in-out;
            z-index: -1;
        }

        /* Float container for steady motion */
        .login-card-container {
            width: 100%;
            max-width: 450px;
            animation: floatCard 6s ease-in-out infinite;
            padding: 0 15px;
        }

        .login-card { 
            width: 100%; 
            border: none; 
            border-radius: 12px; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.12); 
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(40px);
        }

        .login-header { 
            background: var(--bsu-red, #dc3545); 
            color: white; 
            padding: 35px 30px; 
            border-radius: 12px 12px 0 0; 
            text-align: center; 
            position: relative; 
            overflow: hidden; 
        }

        /* Shine sweep effect on the header */
        .login-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg);
            animation: shine 4s infinite 1s;
        }

        .login-icon-wrapper i {
            font-size: 2.5rem;
            display: inline-block;
            animation: bounceIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            opacity: 0;
            transform: scale(0.5);
            animation-delay: 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
            border-color: #dc3545;
        }

        /* Keyframes */
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes floatCard {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        @keyframes pulseBg {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.1); opacity: 1; }
        }

        @keyframes shine {
            0% { left: -150%; }
            40% { left: 200%; }
            100% { left: 200%; }
        }

        @keyframes bounceIn {
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="login-card-container">
        <div class="login-card bg-white p-0">
            <div class="login-header">
                <div class="login-icon-wrapper mb-2"><i class="bi bi-shield-lock-fill text-white"></i></div>
                <h3 class="fw-bold m-0 z-1 position-relative">Admin Portal</h3>
                <p class="mb-0 mt-2 text-white-50 z-1 position-relative">BSU Laboratory Asset Management</p>
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
                <button name="login" type="submit" class="btn btn-danger btn-lg w-100 shadow mb-2 mt-2">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>
