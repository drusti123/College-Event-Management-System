<?php
require_once "config.php"; require_login();
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
        flash("Event and its registrations deleted."); 
    }
    header("Location: events.php"); exit;
}
$search = trim($_GET['q'] ?? "");
if ($search !== "") {
    $like = "%".$search."%"; $stmt=$conn->prepare("SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) registrations_count FROM events e WHERE e.title LIKE ? OR e.category LIKE ? OR e.venue LIKE ? ORDER BY e.event_date DESC");
    $stmt->bind_param("sss",$like,$like,$like); $stmt->execute(); $events=$stmt->get_result();
} else {
    $events=$conn->query("SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) registrations_count FROM events e ORDER BY e.event_date DESC");
}
include "partials_header.php";
?>
<section class="welcome-row"><div><p class="eyebrow">EVENT DIRECTORY</p><h1>Manage events</h1><p class="muted">Create, update and track activities happening on campus.</p></div><a class="btn primary" href="event_form.php">＋ Add new event</a></section>
<section class="panel section-panel"><form class="search-row" method="get"><input name="q" value="<?=e($search)?>" placeholder="Search title, category or venue…"><button class="btn secondary" type="submit">Search</button><?php if($search!==""): ?><a class="text-link" href="events.php">Clear</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>Event details</th><th>Date</th><th>Venue</th><th>Capacity</th><th>Registrations</th><th>Actions</th></tr></thead><tbody>
<?php if($events->num_rows): while($ev=$events->fetch_assoc()): ?><tr><td><div class="event-title"><?=e($ev['title'])?></div><small class="muted"><?=e($ev['category'])?></small></td><td><?=e(date('d M Y',strtotime($ev['event_date'])))?></td><td><?=e($ev['venue'])?></td><td><?= (int)$ev['capacity']?></td><td><span class="count-pill"><?= (int)$ev['registrations_count']?></span></td><td class="actions"><a href="event_form.php?id=<?=(int)$ev['id']?>">Edit</a><a class="danger-link" href="events.php?delete=<?=(int)$ev['id']?>" onclick="return confirm('Delete this event and all related registrations?')">Delete</a></td></tr><?php endwhile; else: ?><tr><td colspan="6" class="empty">No matching events found.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php include "partials_footer.php"; ?>
