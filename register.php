<?php
require_once 'core/init.php';


if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		
		$validate = new Validation();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'name' => 'Nom d\'utilisateur',
				'required' => true,
				'min' => 2,
				'max' => 20,
				'2letters_min' => true,
				'unique' => 'users'
			),
			'passwords' => array(
				'name' => 'Mot de passe',
				'required' => true,
				'min' => 6
			),
			'password2' => array(
				'name' => 'Mot de passe',
				'required' => true,
				'matches' => 'passwords'
			),
			'first_name' => array(
				'name' => 'Prenom',
				'required' => true,
				'letters' =>true,
				'min' => 2,
				'max' => 50
			),
			'last_name' => array(
				'name' => 'Nom',
				'required' => true,
				'letters' => true,
				'min' => 2,
				'max' => 50
			),
			'email' => array(
				'name' => 'Email',
				'email_filter' => true,
				'required' => true,
				'unique' => 'users'
			)
		));
		if($validation->passed()){
			$user = new User();

			$salt = Hash::salt(20);

			try{
				$user->create(array(
					'username' => Input::get('username'),
					'passwords' => Hash::make(Input::get('passwords'), $salt),
					'salt' => $salt,
					'first_name' => Input::get('first_name'),
					'last_name' => Input::get('last_name'),
					'email' => Input::get('email'),
					'joined' => date('Y-m-d H:i:s'),
					'permission' => 1
				));
				Session::flash('home', 'Vous avez été enregistré. Maintenant vous pouvez vous connecter.');
				Redirect::to('index.php');

			} catch(Exception $e) {
				die($e->getMessage());
			}
		} else {
			foreach($validation->errors() as $error){
				echo $error."<br>";
			}
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<form action="" method="post">
		<div class="field">
			<label for="username">Nom d'utilisateur</label>
			<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off"><br><br>
		</div>
		<div class="field">
			<label for="password">Mot de passe</label>
			<input type="password" name="passwords" id="password" autocomplete="off"><br><br>
		</div>
		<div class="field">
			<label for="password2">Répéter le mot de passe</label>
			<input type="password" name="password2" id="password2" autocomplete="off"><br><br>
		</div>
		<div class="field">
			<label for="nom">Nom</label>
			<input type="text" name="last_name" id="nom" value="<?php echo escape(Input::get('last_name')); ?>" autocomplete="off"><br><br>
		</div>		
		<div class="field">
			<label for="prenom">Prénom</label>
			<input type="text" name="first_name" id="prenom" value="<?php echo escape(Input::get('first_name')); ?>" autocomplete="off"><br><br>
		</div>
		<div class="field">
			<label for="email">Email</label>
			<input type="email" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off"><br><br>
		</div>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		<input type="submit" value="Enregistrer">

	</form>

</body>
</html>