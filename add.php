<?php
// Name: hw6/add.php
// Author: Shubham Mudgal
// Purpose: Adding characters to Tolkien database
// Version: 1.0
// Date: 03/06/2016

session_start();
include_once('header.php');
include_once('/var/www/html/hw6/hw6-lib.php');

isset($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset($_REQUEST['postUser'])?$postUser=strip_tags($_REQUEST['postUser']):$postUser="";
isset($_REQUEST['postPass'])?$postPass=strip_tags($_REQUEST['postPass']):$postPass="";
isset($_REQUEST['bid'])?$bid=strip_tags($_REQUEST['bid']):$bid="";
isset($_REQUEST['cid'])?$cid=strip_tags($_REQUEST['cid']):$cid="";
isset($_REQUEST['cname'])?$cname=strip_tags($_REQUEST['cname']):$cname="";
isset($_REQUEST['side'])?$side=strip_tags($_REQUEST['side']):$side="";
isset($_REQUEST['race'])?$race=strip_tags($_REQUEST['race']):$race="";
isset($_REQUEST['url'])?$url=strip_tags($_REQUEST['url']):$url="";
isset($_REQUEST['bookid'])?$bookid=strip_tags($_REQUEST['bookid']):$bookid="";
isset($_REQUEST['newuname'])?$newuname=strip_tags($_REQUEST['newuname']):$newuname="";
isset($_REQUEST['newpass'])?$newpass=strip_tags($_REQUEST['newpass']):$newpass="";
isset($_REQUEST['email'])?$email=strip_tags($_REQUEST['email']):$email="";

connect($db);
if(isset($_SESSION['authenticated']) && $_SESSION['authenticated']=="yes")
{
	//authenticate($db, $postUser, $postPass);
	addCharacterMenu($s);
}
else
{		
	if($postUser == null)
		{	
			header("Location:/hw6/login.php");
		}
		authenticate($db, $postUser, $postPass);
		addCharacterMenu($s);
}

function addCharacterMenu($s)
{	global $db, $cname, $side, $race, $cid,$url ;
	switch($s)
	{
		case 5:  if(is_numeric($s)) addCharacterForm(); break;

		case 6:	 if(is_numeric($s)) addCharacterAndPicturesForm(); break;

		case 7:  if(is_numeric($s)) addPicture(); break;

		case 8:  if(is_numeric($s)) addBookForm(); break;		// Here s=8 for re-entry

		case 25: if(is_numeric($s)) addBookForm(); break;		// Here s=25 for first time entry

		case 90: if(isAdmin()) 
					addUsersForm(); 
				 else
				 	echo "User not authorized to use this functionality";
				 break;
		
		case 91: if(isAdmin()) 
					addUsers(); 
				 else
				 	echo "User not authorized to use this functionality";
				 break;
		
		case 92: if(isAdmin()) 
					showUsers(); 
				 else
				 	echo "User not authorized to use this functionality";
				 break;
		
		case 93: if(isAdmin()) 
					updatePasswordForm(); 
				 else
				 	echo "User not authorized to use this functionality";
				 break;
		
		case 94: if(isAdmin()) 
					updatePassword(); 
				 else
				 	echo "User not authorized to use this functionality";
				 break;
				 
		case 95: // Logout
				 session_destroy();
				 header("Location: /hw6/login.php");
				 break;
		
		default: addCharacterForm(); break;
	}
	
	footer();
}

function updatePassword()
{
	global $db, $newuname, $newpass;
	connect($db);
	$newuname=mysqli_real_escape_string($db,$newuname);
	$newpass=mysqli_real_escape_string($db,$newpass);
				
	$salt = rand(50,10000);
	$hash_salt=hash('sha256',$salt);
	$hash_pass=hash('sha256',$newpass.$hash_salt);
	
	if($stmt = mysqli_prepare($db, "update users set salt =?, password=? where username=?"))
    {
            mysqli_stmt_bind_param($stmt, "sss", $hash_salt ,$hash_pass, $newuname);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "Password updated for user " . $newuname;
  	}
  	else
  		echo "Error in modification of password!";
  		
}

function updatePasswordForm()
{
	echo "<div align=center><table><tr><td>Update User's Password</td></tr>
		<form action=add.php method=post>
		<tr><td>Username</td><td><input type=\"text\" name=\"newuname\" required/></td></tr>
		<tr><td>New Password</td><td><input type=\"password\" name=\"newpass\" required/></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"94\"/></td></tr>
		</form>
		</table>
		</div> ";
}

function showUsers()
{	global $db;

	connect($db);
	if($stmt = mysqli_prepare($db, "select username from users"))
        {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $uname);
                while(mysqli_stmt_fetch($stmt))
				{
					$uname = htmlspecialchars($uname);
                	echo "<tr><td>$uname<br></td></tr>";
				}
				mysqli_stmt_close($stmt);
        }
}

function addUsers()
{
	global $db, $newuname, $newpass, $email;
	connect($db);
	$newuname=mysqli_real_escape_string($db,$newuname);
	$newpass=mysqli_real_escape_string($db,$newpass);
	$email=mysqli_real_escape_string($db,$email);
				
	$salt = rand(50,10000);
	$hash_salt=hash('sha256',$salt);
	$hash_pass=hash('sha256',$newpass.$hash_salt);
	
	if($stmt = mysqli_prepare($db, "insert into users set userid='', username=?, password=?, email=?, salt=?"))
    {
            mysqli_stmt_bind_param($stmt, "ssss", $newuname,$hash_pass, $email, $hash_salt);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "Added new user " . $newuname;
  	}
  	else
  		echo "Error in insertion!";
}       

function addUsersForm()
{
	echo "<div align=center><table><tr><td>Add New User</td></tr>
		<form action=add.php method=post>
		<tr><td>Username</td><td><input type=\"text\" name=\"newuname\" required/></td></tr>
		<tr><td>Password</td><td><input type=\"password\" name=\"newpass\" required/></td></tr>
		<tr><td>Email ID</td><td><input type=\"email\" name=\"email\" required/></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"91\"/></td></tr>
		</form>
		</table>
		</div> ";
}

function footer()
{
	if(isAdmin())
	{
	echo "<div align=center><a href=add.php?s=90>Add New User|</a>
 			<a href=add.php?s=92>Show Users List|</a>
 			<a href=add.php?s=93>Update Password</a><br>
 			<a href=add.php?s=95>Logout</a></div>";
 	}
 	else
 		echo "<a href=add.php?s=95>Logout</a></div>";
}
 			

function isAdmin()
{
	if ( isset($_SESSION['userid']) && $_SESSION['userid'] == 1)
	{
		return true;
	}
	else
		return false;
}


function addCharacterForm()
{
	echo "<div align=center><table><tr><td>Add Character to Books</td></tr>
		<form action=add.php method=post>
		<tr><td>Character Name</td><td><input type=\"text\" name=\"cname\" required/></td></tr>
		<tr><td>Race</td><td><input type=\"text\" name=\"race\" required/></td></tr>
		<tr><td>Side</td><td><input type=\"radio\" name=\"side\" value=\"good\"/>Good<input type=\"radio\" name=\"side\" value=\"evil\"/>Evil</td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"6\"/></td></tr>
		</form>
		</table>
		</div> ";
}

function addCharacterAndPicturesForm()
{
	
	global $db,$cname,$side,$race;
	connect($db);
	$cname = mysqli_real_escape_string($db, $cname);
	$side = mysqli_real_escape_string($db, $side);
	$race = mysqli_real_escape_string($db, $race);
	
	if($stmt = mysqli_prepare($db, "insert into characters set characterid='', name=?, race=?, side=?"))
        {
                 mysqli_stmt_bind_param($stmt, "sss", $cname,$race, $side);
                 mysqli_stmt_execute($stmt);
                 mysqli_stmt_close($stmt);
  	}       
	
	
	if($stmt = mysqli_prepare($db, "select characterid from characters where name=? and race=? and side=? order by characterid desc limit 1"))
	{
		mysqli_stmt_bind_param($stmt, "sss", $cname,$race,$side);
                mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $cid);
                while(mysqli_stmt_fetch($stmt))
                {
			$cid = $cid;
                }
        	mysqli_stmt_close($stmt);
       

	echo "<div align=center><table><tr><td>Add Picture to Character ".$cname." </td></tr>
                <form action=add.php method=post>
                <tr><td>Character Picture URL</td><td><input type=\"text\" name=\"url\" size=\"35\" required/></td></tr>
                <tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"s\" value=\"7\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"cid\" value=\"$cid\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"cname\" value=\"$cname\"/></td></tr>
		</form>
		</table>
                </div> ";
	}
	else
		echo "Error in query";

}

function addPicture()
{
global $db,$cid,$url,$cname;
connect($db);
if(is_numeric($cid))
{
	$cid = mysqli_real_escape_string($db, $cid);
        $url = mysqli_real_escape_string($db, $url);
	$cname = mysqli_real_escape_string($db, $cname);	
	if($stmt = mysqli_prepare($db, "insert into pictures set pictureid='', url=?, characterid=?"))
        {
                mysqli_stmt_bind_param($stmt, "si", $url,$cid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
        	
		$bookid = "";
		$s = "";
		
		echo "<div align=center><table><tr><td>Added Picture for ".$cname." </td></tr>
                <form action=add.php method=post>
                <tr><td><input type=\"submit\" name=\"submit\" value=\"Add Character to Books\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"s\" value=\"25\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"cid\" value=\"$cid\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"cname\" value=\"$cname\"/></td></tr>
                </form>
                </table>
                </div> ";

	}
        else
                echo "Error in query";
		
}
else
	echo "Not a valid Character ID";
}

function addBookForm()
{	
global $db,$cid,$cname,$bookid,$s;
connect($db);
if(is_numeric($cid))
{
	$cid = mysqli_real_escape_string($db, $cid);

/*	echo "cid = ".$cid;
	echo "cname = ".$cname;
	echo "bookid = ".$bookid;
	echo "s = ".$s;
*/
	if($s==8)
	{
 	 	$bookid = mysqli_real_escape_string($db, $bookid);
		if($stmt = mysqli_prepare($db, "insert into appears set appearsid='', bookid=?, characterid=?"))
       		{
	                mysqli_stmt_bind_param($stmt, "ii", $bookid,$cid);
	                mysqli_stmt_execute($stmt);
	                mysqli_stmt_close($stmt);
		}
	}
	
	$bookIdArray = array();
	$bookTitleArray = array();

	if($stmt = mysqli_prepare($db, "select bookid,title from books where bookid not in (select b.bookid from books as b,appears as a  where b.bookid = a.bookid and a.characterid= ?)"))
                {       
                        mysqli_stmt_bind_param($stmt, "i", $cid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $bid,$title);
                        while(mysqli_stmt_fetch($stmt))
                        {	
				array_push($bookIdArray, htmlspecialchars($bid));
				array_push($bookTitleArray, htmlspecialchars($title));
			}				
			mysqli_stmt_close($stmt);
		     
		if(sizeof($bookIdArray) ==0)
		{	
			header('Location: hw6/index.php?s=3&cid='.$cid);
	//		appears($db,$cid);
		}
		 
		
			echo "<div align=center><table>";	
		     if($s == 8)
		     {
			echo "<tr><td>Added ".$cname." to Book ".$bid." </td></tr>";
		     }
			echo " <tr><td>Add ".$cname." to Books </td></tr>
                         <form action=add.php method=post>
                         <tr><td>Select Book</td>
			 <td><select name=\"bookid\">";
				for($i=0; $i < sizeof($bookIdArray); $i++)
				{
					echo "<option value=\"$bookIdArray[$i]\">$bookTitleArray[$i]</option>";
				}	
		 	echo "</td>
                         <tr><td><input type=\"submit\" name=\"submit\" value=\"Add to Book\"/></td>";
		     if($s == 8)
                     {	
			echo " <td><a href=index.php?s=3&cid=$cid>Done</a></td>";
		     }
                        echo "</tr><tr><td><input type=\"hidden\" name=\"s\" value=\"8\"/></td></tr>
                         <tr><td><input type=\"hidden\" name=\"cid\" value=\"$cid\"/></td></tr>
			 <tr><td><input type=\"hidden\" name=\"cname\" value=\"$cname\"/></td></tr>
			 
                         </form>
                         </table>
                         </div> ";
		
		}
	else 
		echo "Error in Query1";

}
else 
	echo "Not a valid Character ID";
}


function authenticate()
{
	global $db,$postUser,$postPass;
	connect($db);
	$query="select userid, email, password, salt from users where username=?";
	if($stmt = mysqli_prepare($db, $query))	
	{
		mysqli_stmt_bind_param($stmt, "s", $postUser);	
		mysqli_stmt_execute($stmt);	
  		mysqli_stmt_bind_result($stmt, $userid, $email, $password, $salt);
  		while(mysqli_stmt_fetch($stmt))	
  		{
  			$userid=$userid;
  			$password=$password;
  			$salt=$salt;
  			$email=$email;
  		}
  		mysqli_stmt_close($stmt);
  		$epass=hash('sha256', $postPass.$salt);
  		if($epass == $password)	
  		{	
  			$_SESSION['userid']=$userid;
  			$_SESSION['email']=$email;
  			$_SESSION['authenticated']="yes";
  			$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
  		}	
  		else	
  		{	
  			echo "Failed to Login";
  			header("Location:/hw6/login.php");
  			exit;
  		}
  	}
}

?>
