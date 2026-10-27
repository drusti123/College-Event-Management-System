<?php
require_once "config.php"; require_login();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)?:0;
$form=['event_id'=>'','participant_name'=>'','email'=>'','phone'=>'','notes'=>''];$error='';
if($id){$stmt=$conn->prepare("SELECT * FROM registrations WHERE id=?");$stmt->bind_param("i",$id);$stmt->execute();$f=$stmt->get_result()->fetch_assoc();if(!$f){flash("Registration not found.","error");header("Location: registrations.php");exit;}$form=$f;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $form['event_id']=(int)($_POST['event_id']??0);$form['participant_name']=trim($_POST['participant_name']??'');$form['email']=trim($_POST['email']??'');$form['phone']=trim($_POST['phone']??'');$form['notes']=trim($_POST['notes']??'');
 if(!$form['event_id']||$form['participant_name']===''||!filter_var($form['email'],FILTER_VALIDATE_EMAIL))$error="Choose an event, enter the participant name and a valid email.";
 else{
  $check=$conn->prepare("SELECT capacity,(SELECT COUNT(*) FROM registrations WHERE event_id=events.id AND id<>?) AS used FROM events WHERE id=?");$check->bind_param("ii",$id,$form['event_id']);$check->execute();$ev=$check->get_result()->fetch_assoc();
  if(!$ev)$error="Selected event does not exist.";
  elseif((int)$ev['used'] >= (int)$ev['capacity'] && !$id)$error="This event has reached its registration capacity.";
  else{
   if($id){$stmt=$conn->prepare("UPDATE registrations SET event_id=?,participant_name=?,email=?,phone=?,notes=? WHERE id=?");$stmt->bind_param("issssi",$form['event_id'],$form['participant_name'],$form['email'],$form['phone'],$form['notes'],$id);$stmt->execute();flash("Registration updated.");}
   else{$stmt=$conn->prepare("INSERT INTO registrations(event_id,participant_name,email,phone,notes) VALUES(?,?,?,?,?)");$stmt->bind_param("issss",$form['event_id'],$form['participant_name'],$form['email'],$form['phone'],$form['notes']);$stmt->execute();flash("Participant registered successfully.");}
   header("Location: registrations.php");exit;
  }
 }
}
$eventOptions=$conn->query("SELECT id,title,event_date FROM events ORDER BY event_date DESC");
include "partials_header.php";
?>
<section class="welcome-row"><div><p class="eyebrow">PARTICIPANT DETAILS</p><h1><?=$id?'Edit registration':'Register a participant'?></h1><p class="muted">Link a student or guest to an event.</p></div><a class="btn secondary" href="registrations.php">← Back to registrations</a></section>
<section class="panel form-panel"><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post" class="form-grid">
<label class="span-2">College event *<select name="event_id" required><option value="">Select an event</option><?php while($ev=$eventOptions->fetch_assoc()): ?><option value="<?=(int)$ev['id']?>" <?=((int)$form['event_id']===(int)$ev['id']?'selected':'')?>><?=e($ev['title'])?> — <?=e(date('d M Y',strtotime($ev['event_date'])))?></option><?php endwhile; ?></select></label>
<label>Participant full name *<input name="participant_name" value="<?=e($form['participant_name'])?>" maxlength="120" required></label>
<label>Email address *<input type="email" name="email" value="<?=e($form['email'])?>" maxlength="190" required></label>
<label>Phone number<input type="tel" name="phone" value="<?=e($form['phone'])?>" maxlength="25" pattern="[0-9+() -]{7,25}" placeholder="+91 98765 43210"></label>
<label class="span-2">Notes<textarea name="notes" rows="3" maxlength="1000" placeholder="Class, department or special requirements…"><?=e($form['notes'])?></textarea></label>
<div class="form-actions span-2"><button class="btn primary"><?=$id?'Save changes':'Complete registration'?></button><a class="btn secondary" href="registrations.php">Cancel</a></div>
</form></section>
<?php include "partials_footer.php"; ?>
