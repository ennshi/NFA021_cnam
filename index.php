<?php
require_once 'core/init.php';

if(Session::exists('home')) {
	echo "<p>" . Session::flash('home') . "<p>";
}

$user = new User();

if($user->isLoggedIn()) {
?>
	<p>Bonjour, <a href="#"><?php echo escape($user->data()->username); ?></a>!</p>
	<ul>
		<li><a href="logout.php">Log out</a></li>
	</ul>
<?php	
} else {
	echo '<p>Vous devez vous <a href="login.php">connecter</a> ou <a href="register.php">enregistrer</a></p>';
}