<?php
session_start();

if(isset($_SESSION['uname']) && isset($_SESSION['pass']))
	header("Location:board.php");
echo'<html><head><title>Message Board - Login/Signup</title></head>
<body>
<div name="logdiv" align="center"><header><h1>Message Board</h1></header>
<form action="login.php" method="POST" autocomplete="off">
<label><b>Username: </b></label><input type="text" name="uname" required><br/><br/>
<label><b>Password: </b><input type="text" name="passwd" required><br/><br/>
<input type="submit" name="login" value="Login">
</form></div>
</body></html>';
try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//last one is array of driver options passed to the PDO constructor
  $dbh->beginTransaction();
  $stmt = $dbh->prepare('select password from users where username = :username'); 
  $stmt -> bindParam(':username', $_POST['uname']);
  $stmt->execute();
  print "<pre>";
  //$row = $stmt->fetchAll();
   while ($row = $stmt->fetch()) 
  {
	if (md5($_POST['passwd']) == $row['password'])
   {
	if (empty($_SESSION['uname']) && empty($_SESSION['pass']))
	{
		$_SESSION['uname'] = $_POST['uname'];
		$_SESSION['pass'] = $_POST['passwd'];
	}		
	header("Location:board.php");
   }
   else
   {
	   echo "Invalid Login Credentials";
   }
  }
  //if (!empty($row)) {
  print "</pre>";
  //$dbh = null;
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
?>