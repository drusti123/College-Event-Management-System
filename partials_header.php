<?php
$page = basename($_SERVER['PHP_SELF']);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e(ucwords(str_replace(['.php','_'],' ', $page)))?> | CampusEvents</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="app-shell">
<aside class="sidebar"><a class="brand" href="dashboard.php"><span class="brand-mark small">CE</span><span>Campus<span class="brand-light">Events</span></span></a><div class="nav-label">WORKSPACE</div><nav>
<a class="<?=$page==='dashboard.php'?'active':''?>" href="dashboard.php"><span>◫</span> Dashboard</a>
<a class="<?=in_array($page,['events.php','event_form.php'])?'active':''?>" href="events.php"><span>▦</span> Events</a>
<a class="<?=in_array($page,['registrations.php','registration_form.php'])?'active':''?>" href="registrations.php"><span>♙</span> Registrations</a>
</nav><div class="sidebar-bottom"><div class="profile-dot"><?=e(strtoupper(substr($_SESSION['user_name']??'U',0,1)))?></div><div class="profile-info"><strong><?=e($_SESSION['user_name']??'User')?></strong><small><?=e(ucfirst($_SESSION['user_role']??'member'))?></small></div><a class="logout" href="logout.php" title="Sign out">↗</a></div></aside>
<main class="main-area"><header class="topbar"><div><span class="topbar-label">THAKUR COLLEGE · IP FINAL PROJECT</span></div><div class="topbar-right"><span class="online-dot"></span> System ready <span class="avatar-mini"><?=e(strtoupper(substr($_SESSION['user_name']??'U',0,1)))?></span></div></header><div class="page-content"><?php show_flash(); ?>
