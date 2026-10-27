<?php
require_once "config.php"; require_login();
if(isset($_GET['delete'])){$id=filter_input(INPUT_GET,'delete',FILTER_VALIDATE_INT);if($id){$stmt=$conn->prepare("DELETE FROM registrations WHERE id=?");$stmt->bind_param("i",$id);$stmt->execute();flash("Registration deleted.");}header("Location: registrations.php");exit;}
$q=trim($_GET['q']??'');
$sql="SELECT r.*, e.title event_title, e.event_date FROM registrations r JOIN events e ON e.id=r.event_id";
if($q!==''){$like="%$q%";$stmt=$conn->prepare($sql." WHERE r.participant_name LIKE ? OR r.email LIKE ? OR e.title LIKE ? ORDER BY r.registered_at DESC");$stmt->bind_param("sss",$like,$like,$like);$stmt->execute();$rows=$stmt->get_result();}
else{$rows=$conn->query($sql." ORDER BY r.registered_at DESC");}
include "partials_header.php";
?>
<section class="welcome-row"><div><p class="eyebrow">PARTICIPANT DIRECTORY</p><h1>Registrations</h1><p class="muted">Review participants registered for college events.</p></div><a class="btn primary" href="registration_form.php">＋ Add registration</a></section>
<section class="panel section-panel"><form class="search-row" method="get"><input name="q" value="<?=e($q)?>" placeholder="Search participant, email or event…"><button class="btn secondary">Search</button><?php if($q!==''): ?><a class="text-link" href="registrations.php">Clear</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>Participant</th><th>Event</th><th>Email</th><th>Phone</th><th>Registered on</th><th>Actions</th></tr></thead><tbody>
<?php if($rows->num_rows):while($r=$rows->fetch_assoc()): ?><tr><td><div class="event-title"><?=e($r['participant_name'])?></div></td><td><?=e($r['event_title'])?></td><td><?=e($r['email'])?></td><td><?=e($r['phone']?:'—')?></td><td><?=e(date('d M Y',strtotime($r['registered_at'])))?></td><td class="actions"><a href="registration_form.php?id=<?=(int)$r['id']?>">Edit</a><a class="danger-link" href="registrations.php?delete=<?=(int)$r['id']?>" onclick="return confirm('Delete this registration?')">Delete</a></td></tr><?php endwhile;else:?><tr><td colspan="6" class="empty">No registrations found.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php include "partials_footer.php"; ?>
