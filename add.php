<?php
start_session();


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
