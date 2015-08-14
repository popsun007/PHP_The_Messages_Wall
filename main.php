<?php 
session_start();
require_once("connection.php");
$query = "SELECT CONCAT(users.first_name, ' ', users.last_name) AS name, message, messages.id AS msg_id, messages.updated_at FROM messages
          JOIN users
          ON users.id = messages.user_id
          ORDER BY updated_at DESC";
$msgs = fetch($query);
$query_com = "SELECT CONCAT(users.first_name, ' ', users.last_name) AS name, comments.messages_id, comment, comments.updated_at FROM messages
              JOIN comments
              ON messages.id = comments.messages_id
              JOIN users
              ON users.id = comments.user_id 
              WHERE messages.id = comments.messages_id
              ORDER BY comments.updated_at ASC";
$coms = fetch($query_com);
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
            <span class="name_date"><?= $msg['name']."----" . $msg['updated_at'] ?>  </span>
            <div class="msg">
               <?= $msg['message']; ?>
  <?php 
            foreach($coms as $com) {
              if ($_SESSION['msg_id'] ==$com['messages_id']){
  ?>
              <div class="comment_title">
                <?=  $com['name'] . "-----" . $com['updated_at']; ?>
              </div>
              <div class="comment_text">
                <?=  $com['comment']; ?>
              </div>
  <?php
              }
            }

   ?>
            <form action="process.php" method="post">
              <textarea class="comment" name="comment"; ?></textarea>
              <input type="hidden" name="action" value="comment">
              <input type="hidden" name="msg_id" value= <?= $_SESSION['msg_id'] ?>>
              <br>
              <input type="submit" value="Post a comment">
            </form>
          </div>
            <div class="break"></div>
          </div>
<?php 
      }
 ?>
    </body>
</html>













