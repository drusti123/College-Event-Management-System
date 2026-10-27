<?php
require_once "config.php"; require_login();
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: 0;
$event=['title'=>'','category'=>'Workshop','description'=>'','event_date'=>'','venue'=>'','capacity'=>100,'organizer'=>$_SESSION['user_name'],'poster_path'=>null];
$error='';
if($id){$stmt=$conn->prepare("SELECT * FROM events WHERE id=?");$stmt->bind_param("i",$id);$stmt->execute();$found=$stmt->get_result()->fetch_assoc();if(!$found){flash("Event not found.","error");header("Location: events.php");exit;}$event=$found;}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $event['title']=trim($_POST['title']??''); $event['category']=trim($_POST['category']??'');
    $event['description']=trim($_POST['description']??''); $event['event_date']=$_POST['event_date']??'';
    $event['venue']=trim($_POST['venue']??''); $event['capacity']=(int)($_POST['capacity']??0); $event['organizer']=trim($_POST['organizer']??'');
    if($event['title']===''||$event['category']===''||$event['event_date']===''||$event['venue']===''||$event['organizer']===''||$event['capacity']<1){$error="Please complete all required fields and use a capacity of at least 1.";}
    elseif(strtotime($event['event_date'])===false){$error="Please enter a valid event date.";}
    else{
      $posterPath = $event['poster_path'] ?? null;
      if (!empty($_FILES['poster']['name'])) {
        if ($_FILES['poster']['error'] !== UPLOAD_ERR_OK || $_FILES['poster']['size'] > 2*1024*1024) {
          $error = "Poster upload failed. Choose an image under 2 MB.";
        } else {
          $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['poster']['tmp_name']);
          $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
          if (!isset($allowed[$mime])) {
            $error = "Poster must be a JPG, PNG or WEBP image.";
          } else {
            if (!is_dir(__DIR__.'/uploads')) mkdir(__DIR__.'/uploads', 0755, true);
            $filename = bin2hex(random_bytes(12)).'.'.$allowed[$mime];
            if (move_uploaded_file($_FILES['poster']['tmp_name'], __DIR__.'/uploads/'.$filename)) {
              $posterPath = 'uploads/'.$filename;
            } else { $error = "Could not save the poster. Check the uploads folder permissions."; }
          }
        }
      }
      if ($error === '') {
        if($id){
          $stmt=$conn->prepare("UPDATE events SET title=?,category=?,description=?,event_date=?,venue=?,capacity=?,organizer=?,poster_path=? WHERE id=?");
          $stmt->bind_param("sssssissi",$event['title'],$event['category'],$event['description'],$event['event_date'],$event['venue'],$event['capacity'],$event['organizer'],$posterPath,$id);
          $stmt->execute();flash("Event updated successfully.");
        } else {
          $stmt=$conn->prepare("INSERT INTO events(title,category,description,event_date,venue,capacity,organizer,poster_path,created_by) VALUES(?,?,?,?,?,?,?,?,?)");
          $uid=(int)$_SESSION['user_id'];
          $stmt->bind_param("sssssissi",$event['title'],$event['category'],$event['description'],$event['event_date'],$event['venue'],$event['capacity'],$event['organizer'],$posterPath,$uid);
          $stmt->execute();flash("Event created successfully.");
        }
        header("Location: events.php");exit;
      }
    }
}
include "partials_header.php";
?>
<section class="welcome-row"><div><p class="eyebrow">EVENT DETAILS</p><h1><?=$id?'Edit event':'Create an event'?></h1><p class="muted">Add the information participants need to know.</p></div><a class="btn secondary" href="events.php">← Back to events</a></section>
<section class="panel form-panel"><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><form method="post" enctype="multipart/form-data" class="form-grid">
<label class="span-2">Event title *<input name="title" maxlength="150" value="<?=e($event['title'])?>" required placeholder="e.g. TechFest 2026"></label>
<label>Category *<select name="category" required><?php foreach(['Workshop','Cultural','Technical','Sports','Seminar','Competition','Other'] as $cat): ?><option <?=($event['category']===$cat?'selected':'')?> value="<?=e($cat)?>"><?=e($cat)?></option><?php endforeach; ?></select></label>
<label>Event date *<input type="date" name="event_date" value="<?=e($event['event_date'])?>" required></label>
<label>Venue *<input name="venue" maxlength="150" value="<?=e($event['venue'])?>" required placeholder="Auditorium / Lab 204"></label>
<label>Participant capacity *<input type="number" name="capacity" min="1" max="100000" value="<?=e($event['capacity'])?>" required></label>
<label>Organizer name *<input name="organizer" maxlength="120" value="<?=e($event['organizer'])?>" required></label>
<label class="span-2">Event poster (optional, JPG/PNG/WEBP, max 2 MB)<input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"></label>
<label class="span-2">Description<textarea name="description" rows="4" maxlength="2000" placeholder="Describe the event, activities and requirements…"><?=e($event['description'])?></textarea></label>
<div class="form-actions span-2"><button class="btn primary" type="submit"><?=$id?'Save changes':'Create event'?></button><a class="btn secondary" href="events.php">Cancel</a></div>
</form></section>
<?php include "partials_footer.php"; ?>
