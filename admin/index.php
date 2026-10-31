<?php
session_start();
require_once '../db.php'; // db.php കണക്ട് ചെയ്യുന്നു

// ഇതിനകം ലോഗിൻ ചെയ്തിട്ടുണ്ടെങ്കിൽ ഡാഷ്ബോർഡിലേക്ക് പോകുക
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ഡാറ്റാബേസിൽ നിന്ന് അഡ്മിനെ തിരയുന്നു
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        // പാസ്‌വേഡ് ചെക്ക് ചെയ്യുന്നു (നേരിട്ടുള്ള പ്ലെയിൻ ടെക്സ്റ്റ് - ടെസ്റ്റിംഗിന് വേണ്ടി)
        if ($password === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Golden Aura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f5; font-family: 'Roboto', sans-serif; }
        .login-card { margin-top: 100px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-header { background-color: #31583b; color: white; text-align: center; padding: 20px; border-radius: 5px 5px 0 0; }
        .btn-custom { background-color: #31583b; color: white; }
        .btn-custom:hover { background-color: #24422c; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card login-card">
                    <div class="card-header">
                        <h4>Golden Aura Admin</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-custom w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>