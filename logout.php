<?php
	require_once 'common/functions.php';
	
	// Unset all of the session variables.
	$_SESSION = array();

	// Delete cookie
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}

	// Finally, destroy the session.
	session_destroy();

	// Redirect
	redirect("./");
?>