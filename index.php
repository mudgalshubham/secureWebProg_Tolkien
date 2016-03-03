<?php
include_once('/var/www/html/hw5/hw5-lib.php');
isset($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset($_REQUEST['sid'])?$sid=strip_tags($_REQUEST['sid']):$sid="";
isset($_REQUEST['bid'])?$bid=strip_tags($_REQUEST['bid']):$bid="";
isset($_REQUEST['cid'])?$cid=strip_tags($_REQUEST['cid']):$cid="";
isset($_REQUEST['cname'])?$cname=strip_tags($_REQUEST['cname']):$cname="";
isset($_REQUEST['side'])?$side=strip_tags($_REQUEST['side']):$side="";
isset($_REQUEST['race'])?$race=strip_tags($_REQUEST['race']):$race="";
isset($_REQUEST['url'])?$url=strip_tags($_REQUEST['url']):$url="";
isset($_REQUEST['bookid'])?$bookid=strip_tags($_REQUEST['bookid']):$bookid="";


echo "Test Hello";

connect($db);

//echo "<html><head</head><body>";
echo "<div align=center><a href=index.php>Story List</a>
| <a href=index.php?s=4>Character List</a>
| <a href=add.php?s=5>Add Characters</a></div><hr>";


switch($s){
	case 1: if(is_numeric($s)) books($db,$sid);break;
	
	case 2: if(is_numeric($s)) characters($db,$bid);break;
	
	case 3: if(is_numeric($s)) appears($db,$cid); break;

	case 4: if(is_numeric($s)) pictures($db); break;

	case 5:  if(is_numeric($s)) addCharacterForm(); break;

	case 6:	 if(is_numeric($s)) addCharacterAndPicturesForm($db,$cname,$side,$race); break;

	case 7:  if(is_numeric($s)) addPicture($db,$cid,$url,$cname); break;

	case 8:  if(is_numeric($s)) addBookForm($db,$cid,$cname,$bookid,$s); break;		// Here s=8 for re-entry

	case 25: if(is_numeric($s)) addBookForm($db,$cid,$cname,$bookid,$s); break;		// Here s=25 for first time entry

	default :
//	$query = "select storyid,story from stories";
//	$result = mysqli_query($db, $query);  
	
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

/*	while($row = mysqli_fetch_row($result)){
		echo "<tr><td><a href=index.php?s=1&sid=$row[0]>$row[1]</a><br></td></tr>";
	}
*/
	echo "</table></div>";
	break;

}
function books($db,$sid)
{
//$query = "select bookid,title from books where storyid = ".$sid;
//$result = mysqli_query($db, $query);  
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

/*	while($row = mysqli_fetch_row($result)){
		echo "<tr><td><a href=index.php?s=2&bid=$row[0]>$row[1]</a></td></tr>";
	}
*/
	echo "</table></div>";
}
else 
	echo "Not a valid story ID";
}

function characters($db,$bid)
{
//$query = "select c.characterid,c.name from characters as c,appears as a where a.characterid=c.characterid and a.bookid=".$bid;
//$result = mysqli_query($db, $query);  
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
         //       	echo "<tr><td><a href=index.php?s=3&cid=$cid>$name</a><br></td></tr>";
		}
	mysqli_stmt_close($stmt);
        

/*	while($row = mysqli_fetch_row($result)){
		echo "<tr><td><a href=index.php?s=3&cid=$row[0]>$row[1]</a></td></tr>";
	}
*/
	echo "</table></div>";
}
else 
        echo "Not a valid Book ID";
}

function appears($db,$cid)
{
//$query = "select c.name,b.title,s.story from characters as c,appears as a, books as b, stories as s where c.characterid=a.characterid and b.bookid=a.bookid and b.storyid=s.storyid and c.characterid = ".$cid;

//$result = mysqli_query($db, $query); 
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

/*	while($row = mysqli_fetch_row($result)){
		echo "<tr> <td> <a href=index.php>$row[0]</a></td>";
		echo " <td><a href=index.php>$row[1]</a></td>";
		echo "<td> <a href=index.php>$row[2]</a></td></tr>";
		
	}
*/
	echo "</table></div>";	
}
else
        echo "Not a valid character ID";
}

function pictures($db)
{
//	$query = "select c.name,p.url,c.characterid from pictures as p,characters as c where c.characterid=p.characterid ";
//	$result = mysqli_query($db, $query); 
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

/*	while($row = mysqli_fetch_row($result)){
		echo "<tr> <td> <a href=index.php?s=3&cid=$row[2]>$row[0]</a></td>";
		echo " <td><img src=\"".$row[1]."\"></td></tr>";
	}
*/
	echo "</table></div>";	
}

function addCharacterForm()
{
	echo "<div align=center><table><tr><td>Add Character to Books</td></tr>
		<form action=index.php method=post>
		<tr><td>Character Name</td><td><input type=\"text\" name=\"cname\"/></td></tr>
		<tr><td>Race</td><td><input type=\"text\" name=\"race\"/></td></tr>
		<tr><td>Side</td><td><input type=\"radio\" name=\"side\" value=\"good\"/>Good<input type=\"radio\" name=\"side\" value=\"evil\"/>Evil</td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		<tr><td><input type=\"hidden\" name=\"s\" value=\"6\"/></td></tr>
		</form>
		</table>
		</div> ";
}

function addCharacterAndPicturesForm($db,$cname,$side,$race)
{
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
                <form action=index.php method=post>
                <tr><td>Character Picture URL</td><td><input type=\"text\" name=\"url\" size=\"35\"/></td></tr>
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

function addPicture($db,$cid,$url,$cname)
{
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
                <form action=index.php method=post>
                <tr><td><input type=\"submit\" name=\"submit\" value=\"Add Character to Books\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"s\" value=\"25\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"cid\" value=\"$cid\"/></td></tr>
                <tr><td><input type=\"hidden\" name=\"cname\" value=\"$cname\"/></td></tr>
                </form>
                </table>
                </div> ";


//		addBookForm($db,$cid,$cname,$bookid,$s);

	}
        else
                echo "Error in query";
		
}
else
	echo "Not a valid Character ID";
}

function addBookForm($db,$cid,$cname,$bookid,$s)
{	
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
			header('Location: index.php?s=3&cid='.$cid);
	//		appears($db,$cid);
		}
		 
		
			echo "<div align=center><table>";	
		     if($s == 8)
		     {
			echo "<tr><td>Added ".$cname." to Book ".$bid." </td></tr>";
		     }
			echo " <tr><td>Add ".$cname." to Books </td></tr>
                         <form action=index.php method=post>
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

?>
