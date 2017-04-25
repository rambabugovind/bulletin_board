<?php
session_start();

if(!isset($_SESSION['uname']) || !isset($_SESSION['pass']))
	header("Location:login.php");
if(isset($_POST['logout']))
{
	session_unset();
	session_destroy();
	header("Location:login.php");
}
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors','On');

echo'<html><head><title>Message Board</title></head>
<body>
<div name="bdiv">
<form action="board.php" id="form1" method="POST">
<div align="right"><input type="submit" name="logout" value="Logout"></div>
<textarea name="txtarea" rows="5" cols="50"></textarea>
<input type="submit" name="postmsg" value="New Post"></form></div>';

$dbi = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbi->beginTransaction();


if(isset($_POST['postmsg']) && $_POST['txtarea']!='')
{
	try {
  $dbi->exec('insert into posts values("'.uniqid().'","","'.$_SESSION['uname'].'",NOW(),"'.$_POST['txtarea'].'")')
        or die(print_r($dbi->errorInfo(), true));
  $dbi->commit();
  header("Location:board.php");
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
elseif(isset($_POST['reply']) && $_POST['txtarea']!='')
{
	try
	{
		$dbi->exec('insert into posts values("'.uniqid().'","'.$_POST['reply'].'","'.$_SESSION['uname'].'",NOW(),"'.$_POST['txtarea'].'")')
           or die(print_r($dbi->errorInfo(), true));
		$dbi->commit();
		header("Location:board.php");
	}catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
else
{
	echo '<p><b>ERROR:</b> Text Area cant be empty</p>';
}
try {
  $stmt = $dbi->prepare('select posts.id, posts.postedby, users.fullname, posts.datetime, posts.replyto, posts.message from posts join users on posts.postedby=users.username order by datetime DESC');
  $stmt->execute();
  //print "<pre>";
  echo($_POST['reply']);
  while ($row = $stmt->fetch()) {
	 //print_r ($row);
	 if($row['replyto']!='')
	 {
		 echo '<fieldset><legend> <b>Reply</b> to Msg ID: '.$row['replyto'].' by 	<b>'.$row['fullname'].' "</b>aka" '.$row['postedby'].' at '.$row['datetime'].'</legend><pre>Message ID: '.$row['id'].'</pre><i>'.$row['message'].'</i><br/>
	 <button type="submit" name="reply" value="'.$row['id'].'" form="form1" formaction="board.php?replyto='.$row['id'].'">Reply</button></fieldset>';
	 }
	 else
	 {
		 echo '<fieldset><legend><b>'.$row['fullname'].' "</b>aka" '.$row['postedby'].' at '.$row['datetime'].'</legend><pre>Message ID: '.$row['id'].'</pre><i>'.$row['message'].'</i><br/>
	 <button type="submit" name="reply" value="'.$row['id'].'" form="form1" formaction="board.php?replyto='.$row['id'].'">Reply</button></fieldset>';
	 }	 
  }
  //print "</pre>";
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
echo '</body></html>';
?>

