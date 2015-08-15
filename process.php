<?php 
session_start();
require_once("connection.php");
//-------------------------user register and login-----------------------//
if (isset($_POST['action']) && ($_POST['action'] == "register")){
	register($_POST);
}
if (isset($_POST['action']) && ($_POST['action'] == "log_in")){
	login($_POST);
}
if (isset($_POST['log_out']) && ($_POST['log_out'] == "Log Out!")){
	session_destroy();
	header("location: index.php");
}
if (isset($_SESSION['errors'])){
	unset($_SESSION['log_in']);

	header("location: index.php");
	die();
}
//----------------------------------end register and login------------------------------//

//-----------------------------begin wall post and comment page-------------------------//
if (isset($_POST['action']) && ($_POST['action'] == "message")){
	post_message($_POST);

}
if (isset($_POST['action']) && ($_POST['action']) == "comment"){
	comment($_POST);
}
if (isset($_POST['action']) && ($_POST['action']) == "delete_post"){
	delete_post($_POST);
}
if (isset($_POST['action']) && ($_POST['action'] == "delete_comment")){
	delete_comment($_POST);
}
if (isset($_SESSION['main_errors'])){
	header("location: main.php");
}





//===========================below all methods===========================//
function has_number($str){
	for($i=0; $i<strlen($str); $i++){
		if(is_numeric($str[$i])){
			return true;
		}
	}
	return false;
}

function register($post){
	//------------begin validation----------------//
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$_SESSION['errors'][] = "Email format is NOT validate!";
	}
	if (empty($_POST['first_name']) || has_number($_POST['first_name'])){
		$_SESSION['errors'][] = "First name must be no number and not empty.";
	}
	if (empty($_POST['last_name']) || has_number($_POST['last_name'])){
		$_SESSION['errors'][] = "Last name must be no number and not empty.";
	}
	if (empty($_POST['password']) || ($_POST['password'] != $_POST['com_password'])){
		$_SESSION['errors'][] = "Password can not be empty and remain same password.";
	}

	//-------------end validation-------------------//
	
	//--------------communicate with Database------------//

	global $connection;
	$esc_email = mysqli_real_escape_string($connection, $_POST['email']);
	$esc_first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
	$esc_last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
	$esc_password = mysqli_real_escape_string($connection, $_POST['password']);
	$query = "INSERT INTO users (email, first_name, last_name, password) 
			VALUES ('{$esc_email}','{$esc_first_name}','{$esc_last_name}','{$esc_password}');";
	run_mysql_query($query);
	$_SESSION['log_in'] = true;
	header("location: index.php");
}

function login($post) {
	global $connection;
	$esc_log_email = mysqli_real_escape_string($connection, $_POST['log_email']);
	$esc_log_password = mysqli_real_escape_string($connection, $_POST['log_password']);
	if (empty($_POST['log_email'])||empty($_POST['log_password'])){
		$_SESSION['errors'][] = "User name or password can not be empty!";
	}
	$query = "SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM users 
			WHERE email = '{$esc_log_email}' AND password = '{$esc_log_password}';" ;
	$infos = fetch($query);
	if($infos){
		$_SESSION['user_id'] = $infos[0]['id'];
		$_SESSION['user_name'] = $infos[0]['name'];
		header("location: main.php"); 
	}
	else{
		$_SESSION['errors'][] = "User name and password don't match!";
	}
}

function post_message($post) {
	global $connection;
	$esc_message = mysqli_real_escape_string($connection, $_POST['message']);
	if (empty($_POST['message'])){
		$_SESSION['main_errors'][] = "You can NOT post blank message!";
	}
	else{
		$query = "INSERT INTO messages (message, created_at, updated_at, user_id)
				 VALUES('{$esc_message}', now(), now(), '{$_SESSION['user_id']}');";
		run_mysql_query($query);
	}
	header("location: main.php");
	
}
function comment($post) {
	global $connection;
	$esc_comment = mysqli_real_escape_string($connection, $_POST['comment']);
	if (empty($_POST['comment'])){
		$_SESSION['main_errors'][] = "You can NOT comment blank message!";
	}
	else{
		$query = "INSERT INTO comments (comment, created_at, updated_at, messages_id, user_id) 
				VALUES('{$esc_comment}',now(), now(), '{$_POST['msg_id']}', '{$_SESSION['user_id']}');";
		run_mysql_query($query);
	}
	header("location: main.php");
}

function delete_post($post) {
	$query = "DELETE FROM comments
			WHERE messages_id = " . $_POST['del_post_id'];
	run_mysql_query($query);
	$query = "DELETE FROM messages 
			WHERE id = " . $_POST['del_post_id'];
	run_mysql_query($query);
	header("location: main.php");
}

function delete_comment($post) {
	$query = "DELETE FROM comments
			WHERE id = " . $_POST['del_com_id'];
	run_mysql_query($query);
	header("location: main.php");
}











?>