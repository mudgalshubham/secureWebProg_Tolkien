<?php

// Name: hw6/login.php
// Author: Shubham Mudgal
// Purpose: File to show login form
// Version: 1.0
// Date: 03/06/2016

include_once('header.php');

echo "<div align=center><table><tr><td>Login</td></tr>
		<form action=add.php method=post>
		<tr><td>Username</td><td><input type=\"text\" name=\"postUser\" required/></td></tr>
		<tr><td>Password</td><td><input type=\"password\" name=\"postPass\" required/></td></tr>
		<tr><td><input type=\"submit\" name=\"submit\" value=\"submit\"/></td></tr>
		</form>
		</table>
		</div> ";
		
		
?>
		
		