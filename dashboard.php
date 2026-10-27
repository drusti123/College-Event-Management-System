<?php
require_once "config.php"; require_login();
$eventCount = (int)$conn->query("SELECT COUNT(*) n FROM events")->fetch_assoc()['n'];
$registrationCount = (int)$conn->query("SELECT COUNT(*) n FROM registrations")->fetch_assoc()['n'];
$participantCount = (int)$conn->query("SELECT COUNT(DISTINCT email) n FROM registrations")->fetch_assoc()['n'];
$upcomingCount = (int)$conn->query("SELECT COUNT(*) n FROM events WHERE event_date >= CURDATE()")->fetch_assoc()['n'];
$events = $conn->query("SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) AS registrations_count FROM events e ORDER BY e.event_date ASC LIMIT 6");
include "partials_header.php";
?>
<section class="welcome-row"><div><p class="eyebrow">OVERVIEW</p><h1>Good to see you, <?=e($_SESSION['user_name'])?>.</h1><p class="muted">Here’s what’s happening across your campus.</p></div><a class="btn primary" href="event_form.php">＋ Create event</a></section>
<div class="stats-grid">
<div class="stat-card"><span class="stat-icon violet">✦</span><p>Total events</p><strong><?=$eventCount?></strong><small>Events in your system</small></div>
<div class="stat-card"><span class="stat-icon blue">▦</span><p>Registrations</p><strong><?=$registrationCount?></strong><small>All participant sign-ups</small></div>
<div class="stat-card"><span class="stat-icon pink">♙</span><p>Participants</p><strong><?=$participantCount?></strong><small>Unique email addresses</small></div>
<div class="stat-card"><span class="stat-icon green">◷</span><p>Upcoming events</p><strong><?=$upcomingCount?></strong><small>Today and future dates</small></div>
</div>
<section class="panel section-panel"><div class="section-heading"><div><h2>Events at a glance</h2><p class="muted">Recent and upcoming campus activities</p></div><a class="text-link" href="events.php">View all events →</a></div>
<div class="table-wrap"><table><thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Registrations</th><th>Status</th></tr></thead><tbody>
<?php if ($events->num_rows): while($ev=$events->fetch_assoc()): ?>
<tr><td><div class="event-title"><?=e($ev['title'])?></div><small class="muted"><?=e($ev['category'])?></small></td><td><?=e(date("d M Y",strtotime($ev['event_date'])))?></td><td><?=e($ev['venue'])?></td><td><span class="count-pill"><?= (int)$ev['registrations_count']?></span></td><td><span class="status <?=strtotime($ev['event_date']) < strtotime(date('Y-m-d'))?'past':'upcoming'?>"><?=strtotime($ev['event_date']) < strtotime(date('Y-m-d'))?'Completed':'Upcoming'?></span></td></tr>
<?php endwhile; else: ?><tr><td colspan="5" class="empty">No events yet. Create your first event to get started.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php include "partials_footer.php"; ?>
