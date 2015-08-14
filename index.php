<?php 
session_start();
 ?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="utf-8">
        <title>The Wall</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <style type="text/css">
    	body {
    		width: 970px;
    		margin: 0 auto;
    		background-color: lightgrey; 
    		font-family: Arial;
    	}
    	p {
    		color:red;
    	}
    	.success {
    		color:green;
    	}
    </style>
    <body>
        <h1>Welcom to The Wall!</h1>
    	<?php 
    		if(!isset($_SESSION['log_in'])){
    	 ?>
    <form action="process.php" method="post">
    	<table>
    		<th>
    			<td><h2>Registration</h2></td>
    		</th>
        	<tr>
        		<td>Email:</td>
        		<td><input type="text" name="email"></td>
        	</tr>
        	<tr>
        		<td>First Name:</td>
        		<td><input type="text" name="first_name"></td>
        	</tr>
        	<tr>
        		<td>Last Name:</td>
        		<td><input type="text" name="last_name"></td>
        	</tr>
        	<tr>
        		<td>Password:</td>
        		<td><input type="password" name="password"></td>
        	</tr>
        	<tr>
        		<td>Comfirm <br>Password</td>
        		<td><input type="password" name="com_password"></td>
        	</tr>
        	<tr>
        		<input type="hidden" name="action" value="register">
        		<td><input type="submit" value="done!"></td>
        	</tr>
        </table>
    </form>
    <?php 
    		}
    		else {
    			echo "<h1 class='success'>Successfully register!</h1>";
    			unset($_SESSION['log_in']);
    		}
     ?>
<?php 
	if(isset($_SESSION['errors'])){
		for($i=0; $i<count($_SESSION['errors']);$i++) {
?>
			<div>
				<p><?= $_SESSION['errors'][$i]; ?></p>
			</div>
<?php
		}
		unset($_SESSION['errors']);
	}
 ?>
<br><br>
    <form action="process.php" method="post">
    	<table>
    		<th>
    			<td><h2>Login</h2></td>
    		</th>
    		<tr>
    			<td>Your email</td>
    			<td><input type="text" name="log_email"></td>
    		</tr>
    		<tr>
    			<td>Password</td>
    			<td><input type="password" name="log_password"></td>
    		</tr>
    		<tr>
    			<input type="hidden" name="action" value="log_in">
    			<td><input type="submit" value="Go"></td>
    		</tr>	
    	</table>
    </form>
    </body>
</html>