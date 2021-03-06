<!DOCTYPE html>
<html>
<head>
<link rel='stylesheet' type='text/css' href='css/beautify.css'/>

<title>O.C.A.G</title>

</head>

<body>
<div id='wrapper'>
<?php 
require('functions/checkidentity.php');
if(!(isloggedin("any")==true))
{
	header('Location: login.php');
}
else
{
	include('include/head.php');
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
	$username='%';
	$probcode='%';
	if(isset($_GET['username']))
		$username=$_GET['username'];
	$username=strtoupper($username);
	if(isset($_GET['pcode']))
		$probcode=$_GET['pcode'];
	if($probcode=='')
		$probcode='%';
	if($username=='')
		$username='%';
		
	$probcode=strtoupper($probcode);
	include('include/mysql_view.php');
	
	$query="select * from submissions where username like '$username' and problem_code like '$probcode' order by submissiontime desc ";//LIMIT $from,10 ";
	
	$res=mysqli_query($db,$query);
	$problem_link='';
	$user_link='';
	
	if($probcode=='%')
	{
		$problem_link="all problems";
	}
	else
	{
		$problem_link="<a href='problem.php?pcode=$probcode' style='text-decoration:none'>$probcode</a>";
	}
	if($username=='%')
	{
		$user_link='all users';
	}
	else
	{
		$user_link="<a href='user.php?userid=$username' style='text-decoration:none'>$username</a>";
	}
	echo "
	<div id='content'>
	<br>
	<br>
	<form action='usersubmit.php' method='get'>
	Problem Code : <input  type='text' style='width: 200px;' name='pcode' id='searchstr_problem' > 
	&nbsp;&nbsp;&nbsp;Username : <input type='text' name='username' style='width: 200px;' >
  	<input type='submit' value='Search for submissions' class='bbutton'>
  	</form>
	<h3 style='text-align:center';>
	All submissions of $problem_link by $user_link
	</h3>
	<table id='table'>
	<tr>
		<th><strong>ID</strong></th>
		<th><strong>Problem</strong></th>
		<th><strong>Username</strong></th>
		<th><strong>Date/Time</strong></th>
		<th><strong>Verdict</strong></th>
		<th><strong>Time</strong></th>
		<th><strong>Language</strong></th>
		<th><strong>Solution</strong></th>
	</tr>";

	$i=0;
	while($row=mysqli_fetch_array($res))
	{
	
		$submissionid=$row['submissionid'];
		$submission_user=$row['username'];
		$submissiontime=$row['submissiontime'];
		$verdict=$row['verdict'];
		$language=$row['language'];
		$executiontime=$row['executiontime'];
		$submission_problem_code=$row['problem_code'];
		if($verdict=="TLE")
			$executiontime='-';
		if($i%2==0)
			echo "<tr class='alt'>";
		else
			echo "<tr>";
		echo "
		<td>$submissionid</td>
		<td><a href='problem.php?pcode=$submission_problem_code'>$submission_problem_code</a></td>
		<td><a href='user.php?user=$submission_user'>$submission_user</a></td>
		<td>$submissiontime</td>
		<td><img src='media/$verdict.png'></td>
		<td>$executiontime</td>
		<td>$language</td>
		<td><a href='solution.php?submissionid=$submissionid'>View</a></td>
		</tr>
		";	
		$i++;
	}
	echo "
	</table>
	</div>
	";
	include('include/side.php');
	include('include/bottom.php'); 
}
?>
</div>

</body>

</html>
