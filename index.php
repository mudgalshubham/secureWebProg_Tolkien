<?php
// Name: hw6/index.php
// Author: Shubham Mudgal
// Purpose: Home page for Tolkien application
// Version: 1.0
// Date: 03/06/2016

session_start();
include_once('header.php');
include_once('/var/www/html/hw6/hw6-lib.php');

isset($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset($_REQUEST['sid'])?$sid=strip_tags($_REQUEST['sid']):$sid="";
isset($_REQUEST['bid'])?$bid=strip_tags($_REQUEST['bid']):$bid="";
isset($_REQUEST['cid'])?$cid=strip_tags($_REQUEST['cid']):$cid="";
isset($_REQUEST['cname'])?$cname=strip_tags($_REQUEST['cname']):$cname="";
isset($_REQUEST['side'])?$side=strip_tags($_REQUEST['side']):$side="";
isset($_REQUEST['race'])?$race=strip_tags($_REQUEST['race']):$race="";
isset($_REQUEST['url'])?$url=strip_tags($_REQUEST['url']):$url="";
isset($_REQUEST['bookid'])?$bookid=strip_tags($_REQUEST['bookid']):$bookid="";

connect($db);

switch($s){
	case 1: if(is_numeric($s)) books($db,$sid);break;
	
	case 2: if(is_numeric($s)) characters($db,$bid);break;
	
	case 3: if(is_numeric($s)) appears($db,$cid); break;

	case 4: if(is_numeric($s)) pictures($db); break;

	default :  
	
		echo "<div align=center><table><tr><td><u><b>Stories</b></u><br></td></tr>";
		if($stmt = mysqli_prepare($db, "select storyid,story from stories"))
        {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $sid, $story);
                while(mysqli_stmt_fetch($stmt))
		{
			$sid = htmlspecialchars($sid);
                	$story= htmlspecialchars($story);
                	echo "<tr><td><a href=index.php?s=1&sid=$sid>$story</a><br></td></tr>";
		}
		mysqli_stmt_close($stmt);
        }

	echo "</table></div>";
	break;

}
function books($db,$sid)
{

if(is_numeric($sid))
{
	echo "<div align=center><table><tr><td><u><b>Books</b></u></td></tr>";
	$sid = mysqli_real_escape_string($db, $sid);
	if($stmt = mysqli_prepare($db, "select bookid,title from books where storyid = ?"))
        {
               	mysqli_stmt_bind_param($stmt, "i", $sid);
		mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $bid, $title);
                while(mysqli_stmt_fetch($stmt))
		{
			$bid = htmlspecialchars($bid);
                	$title= htmlspecialchars($title);
                	echo "<tr><td><a href=index.php?s=2&bid=$bid>$title</a><br></td></tr>";
		}
	mysqli_stmt_close($stmt);
        }


	echo "</table></div>";
}
else 
	echo "Not a valid story ID";
}

function characters($db,$bid)
{
 
	if(is_numeric($bid))
	{
		echo "<div align=center><table><tr><td><u><b>Characters</b></u></td></tr>";
		$bid = mysqli_real_escape_string($db, $bid);
		if($stmt = mysqli_prepare($db, "select c.characterid,c.name from characters as c,appears as a where a.characterid=c.characterid and a.bookid= ?"))
        {
               	mysqli_stmt_bind_param($stmt, "i", $bid);
				mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $cid, $name);
                while(mysqli_stmt_fetch($stmt))
				{
					$cid = htmlspecialchars($cid);
                	$name= htmlspecialchars($name);
                	echo "<tr><td><a href=index.php?s=3&cid=$cid>$name</a><br></td></tr>";
				}
				mysqli_stmt_close($stmt);
         }    
		echo "</table></div>";
	}
	else 
        echo "Not a valid Book ID";
}

function appears($db,$cid)
{

if(is_numeric($cid))
{
	echo "<div align=center><table><tr><td><u><b>Appearances</b></u></td></tr>
	<tr><td>Character</td><td>Book</td><td>Story</td></tr>"; 
	$cid = mysqli_real_escape_string($db, $cid);
	if($stmt = mysqli_prepare($db, "select c.name,b.title,s.story from characters as c,appears as a, books as b, stories as s where c.characterid=a.characterid and b.bookid=a.bookid and b.storyid=s.storyid and c.characterid = ?"))
    {
               	mysqli_stmt_bind_param($stmt, "i", $cid);
				mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $name, $title, $story);
                while(mysqli_stmt_fetch($stmt))
				{
					$name= htmlspecialchars($name);
                	$title= htmlspecialchars($title);
					$story = htmlspecialchars($story);
					echo "<tr> <td> <a href=index.php>$name</a></td>
                		<td><a href=index.php>$title</a></td>
                		<td> <a href=index.php>$story</a></td></tr>";
                }
				mysqli_stmt_close($stmt);
        }

	echo "</table></div>";	
}
else
    echo "Not a valid character ID";
}

function pictures($db)
{
	echo "<div align=center><table><tr><td>Characters</td></tr>";
	if($stmt = mysqli_prepare($db, "select c.name,p.url,c.characterid from pictures as p,characters as c where c.characterid=p.characterid "))
        {
		mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $name, $url, $cid);
                while(mysqli_stmt_fetch($stmt))
		{
			$name = htmlspecialchars($name);
			$url = htmlspecialchars($url);
                	$cid= htmlspecialchars($cid);
                	echo "<tr><td><a href=index.php?s=3&cid=$cid>$name</a></td>";
			echo "<td><img src=\"".$url."\"></td></tr>";
		}
	mysqli_stmt_close($stmt);
        }

	echo "</table></div>";	
}


?>
