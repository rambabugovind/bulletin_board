<?php
session_start();

if(isset($_SESSION['uname']) && isset($_SESSION['pass']))
	header("Location:board.php");
echo'<html><head><title>Message Board - Login/Signup</title><style><link rel="stylesheet" href="style.css" type="text/css">div{background-color: lightblue;}</style></head>
<body>
<div name="logdiv" align="center" ><header><h1 style="color:white;background:#313F8C;">Message Board</h1></header>
<form action="login.php" method="POST" autocomplete="off">
<div id="login" style="float:left; width:50%;"><div class="left"><header><h3><u>Existing Users</u></h3></header><label><b>Username: </b></label><input type="text" name="uname"></div><br/><br/>
<div class="left"><label><b>Password: </b></label><input type="text" name="passwd" ></div><br/><br/>
<div class="right"><input type="submit" name="login" value="Login"></div></div>
<div id="signup" style="float:left; width:50%;"><div class="left"><header><h3><u>New Users</u></h3></header><label><b>Full name: </b></label><input type="text" name="n_name"></div><br/><br/><div class="left"><label><b>User name: </b></label><input type="text" name="n_uname"></div><br/><br/><div class="left" style="margin-left:8px"><label><b>Password: </b></label><input type="text" name="n_passwd"></div><br/><br/><div class="left" style="margin-left:30px"><label><b>Email: </b></label><input type="text" name="n_email"></div><br/><br/><div class="right"><input type="submit" name="signup" value="Signup"></div></div>
</form></div>
</body></html>';

$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//last one is array of driver options passed to the PDO constructor
$dbh->beginTransaction();
if(isset($_POST['login']) && !empty($_POST['uname']) && !empty($_POST['passwd']))
{
	try {
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
}
elseif(isset($_POST['signup']) && !empty($_POST['n_uname']) && !empty($_POST['n_name']) && !empty($_POST['n_passwd']) && !empty($_POST['n_email']))
{
	try {
  $dbh->exec('insert into users values("'.$_POST['n_uname'].'","'.md5($_POST['n_passwd']).'","'.$_POST['n_name'].'","'.$_POST['n_email'].'")')
        or die(print_r($dbi->errorInfo(), true));
  $dbh->commit();
  header("Location:login.php");
  echo"Signup successful!";
} catch (PDOException $e) {
	if($e->getCode() == 23000)
		echo "Username already exists!";
	else
		print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
else
{
	echo "Type in all the fields";
}
?>