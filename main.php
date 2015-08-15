<?php 
session_start();
//------------database communication--------------//
require_once("connection.php");
$query = "SELECT CONCAT(users.first_name, ' ', users.last_name) AS name, message, messages.id AS msg_id, user_id, messages.updated_at FROM messages
          JOIN users
          ON users.id = messages.user_id
          ORDER BY updated_at DESC";
$msgs = fetch($query);
$query_com = "SELECT CONCAT(users.first_name, ' ', users.last_name) AS name, comments.messages_id, comments.id, comments.user_id, comment, comments.updated_at FROM messages
              JOIN comments
              ON messages.id = comments.messages_id
              JOIN users
              ON users.id = comments.user_id 
              WHERE messages.id = comments.messages_id
              ORDER BY comments.updated_at ASC";
$coms = fetch($query_com);

//-------------track how long has been submitted------------------//
date_default_timezone_set('America/Los_Angeles');
function different_time($date){
  $create_date = new DateTime($date);
            $strip = $create_date -> format('YmdHis'); //strip the update time to same format
            $time_now = date("YmdHis"); //get time for right now
           return $diff = $time_now - $strip;//check time differece between now and the post time;
}

 ?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset="utf-8">
    <title>The Wall</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="header">The Message Wall</div>
    <div class="profile">Hi~ <?= $_SESSION['user_name'] ?></div>
    <div class="log_out">
     	<form action="process.php" method="post">
     		<input type="submit" class="log_out" name="log_out" value="Log Out!">
       	</form>
    </div>
    <div class="head_break"></div>
      <br>
    <h2>Post a message</h2>
    <form action="process.php" method="post">
      <textarea class="message" name="message"></textarea>
      <br>
      <input type="hidden" name="action" value="message">
      <input type="submit" name="msg_btn" value="Post a message">
<?php 
        if (isset($_SESSION['main_errors'])){
          for($i=0; $i<count($_SESSION['main_errors']);$i++){
?>
              <h4 style="color: red"> <?= $_SESSION['main_errors'][$i];?> </h4>
<?php
          }
        unset($_SESSION['main_errors']);
        }
?>
      </form>
      <br><br>
<?php 
      foreach($msgs as $msg){
        $_SESSION['msg_id'] = $msg['msg_id'];
 ?>
        <div>
        <div class="name_date"><?= $msg['name']."----" . $msg['updated_at'] ?></div>
<?php
        $diff = different_time($msg['updated_at']);
        if(($msg['user_id']==$_SESSION['user_id']) && ($diff < 3000)){ //3000 means 30 mins;
 ?>
        <div class="delete_post">
          <form action="process.php" method="post">
            <input type="hidden" name="action" value="delete_post">
            <input type="hidden" name="del_post_id" value= <?= $_SESSION['msg_id'] ?>>
            <input type="submit" class="button_red" value="remove">
          </form>
        </div>
<?php 
            }
?>
        <div class="msg">
        <?= $msg['message']; ?>
        </div>
  <?php 
        foreach($coms as $com) {
          if ($_SESSION['msg_id'] ==$com['messages_id']){
  ?>
             <div class="comment">
             <div class="comment_title">
             <?=  $com['name'] . "-----" . $com['updated_at']; ?>
        </div>
<?php 

             $diff = different_time($com['updated_at']);
             if(($_SESSION['user_id'] == $com['user_id']) && ($diff < 3000)){ //3000 means 30 mins;
?>
              <div class="delete_comment">
                <form action="process.php" method="post">
                  <input type="hidden" name="action" value="delete_comment">
                  <input type="hidden" name="del_com_id" value= <?= $com['id'] ?>>
                  <input type="submit" class="button_red" value="remove">
                </form>
              </div>
<?php 
                }
?>
              <div class="comment_text">
                <?=  $com['comment']; ?>
              </div>
    </div>
  <?php
              }
            }

   ?>
            <form action="process.php" method="post">
              <textarea class="com_textarea" name="comment"; ?></textarea>
              <input type="hidden" name="action" value="comment">
              <input type="hidden" name="msg_id" value= <?= $_SESSION['msg_id'] ?>>
              <br>
              <input type="submit" value="Post a comment">
            </form>
            <br>
            <div class="break"></div>
          </div>
<?php 
      }
 ?>
      <center><h3>Welcome to The Wall</h3></center>
    </body>
</html>













