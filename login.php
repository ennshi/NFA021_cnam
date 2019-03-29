<?php
require_once 'core/init.php';
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$validate = new Validation();
		$validation = $validate->check($_POST, array(
			'email' => array(
				'name' => 'Email',
				'required' => true),
			'passwords' => array(
				'name' => 'Mot de passe',
				'required' => true)
		));
		if($validation->passed()) {
			$user = new User();
			$login = $user->login(Input::get('email'), Input::get('passwords'));
			if($login) {
				Redirect::to('index.php');
			} else {
				echo 'La connexion a échoué';
			}

		} else {
			foreach($validation->errors() as $error) {
				echo $error, '<br>';
			}
		}
	}
}

?>
<form action="" method="post">
	<div class="field">
			<label for="email">Email</label>
			<input type="email" name="email" id="email" value="" autocomplete="off"><br><br>
	</div>
	<div class="field">
			<label for="password">Mot de passe</label>
			<input type="password" name="passwords" id="password" value="" autocomplete="off"><br><br>
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Connexion">
</form>
