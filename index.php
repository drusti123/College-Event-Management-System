<?php
require_once "config.php";
if (!empty($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"] ?? "";
    if (!$email || $password === "") {
        $error = "Enter a valid email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user["password_hash"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = (int)$user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_role"] = $user["role"];
            setcookie("cem_last_login", date("Y-m-d H:i:s"), time()+60*60*24*30, "/", "", false, true);
            header("Location: dashboard.php"); exit;
        } else {
            $error = "Incorrect email or password.";
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in | CampusEvents</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="login-page"><main class="login-wrap"><div class="brand-mark">CE</div><p class="eyebrow">CAMPUS EVENTS</p><h1>Welcome back.</h1><p class="muted">Manage college events, registrations and participants in one place.</p>
<?php if ($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><?php show_flash(); ?>
<form method="post" class="panel form-stack" autocomplete="on">
<label>Email address<input type="email" name="email" placeholder="admin@college.edu" required></label>
<label>Password<input type="password" name="password" placeholder="Enter password" required></label>
<button class="btn primary full" type="submit">Sign in <span>→</span></button>
<div class="demo-note"><strong>Demo login</strong><br>admin@college.edu<br>Password: Admin@123</div>
</form><p class="tiny muted">PHP + MySQL academic project · College Event Management System</p></main></body></html>
