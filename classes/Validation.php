<?php
class Validation {
	private $_passed = false,
			$_errors = array(),
			$_db = null;
	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			$name_item = '';
			foreach ($rules as $rule => $rule_value) {
				if($rule === 'name') {
					$name_item = escape($rule_value);
				}

				$value = trim($source[$item]);

				if($rule === 'required' && empty($value)){
					$this->addError("Le champ \"{$name_item}\" est obligatoire.");
				} else if(!empty($value)) {
					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value){
								$this->addError("{$name_item} doit comporter au moins {$rule_value} caractères.");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value){
								$this->addError("{$name_item} doit comporter au maximum {$rule_value} caractères.");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("Les mots de passe doivent correspondre.");
							}
						break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->addError("Ce {$name_item} est déjà enregistré.");
							}
						break;
						case 'email_filter':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
								$this->addError("{$name_item} n'est pas valide.");
							}
						case 'letters':
							if(!ctype_alpha($value)) {
								$this->addError("{$name_item} ne doit contenir que des lettres.");
							}
						case '2letters_min':
							if(!preg_match('/^[a-zA-Z][a-zA-Z]+/', $value)){
								$this->addError("{$name_item} doit commencer par une lettre et contenir au moins deux lettres.");
							}
						default:

						break;
					}
				}
			}

		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}
	public function passed() {
		return $this->_passed;
	}
}