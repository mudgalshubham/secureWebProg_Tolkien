<?php

// Name: hw6/login.php
// Author: Shubham Mudgal
// Purpose: File to show login form
// Version: 1.0
// Date: 03/06/2016

session_start();
include_once('header.php');
if(!isset($_SESSION['authenticated']))							//Shows login form only if session is not authenticated(not already logged in)
{
	echo "<div align=center><table><tr><td>Login</td></tr>
		<form action=add.php method=post>
		<tr><td>Username</td><td><input type=\"text\" name=\"postUser\" required/></td></tr>
		<tr><td>Password</td><td><input type=\"password\" name=\"postPass\" required/></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		</form>
		</table>
		</div> ";
}
else 
	header("Location:/hw6/add.php");							// If user is already is already logged in then redirects to the add character page
		
?>
		
		