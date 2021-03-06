<?php 

namespace Src;

class Model {

	private $values = [];

	public function __call($name, $args) {
		// A cada chamada de método __call é chamado Ex: setiduser
		$methodName = substr($name, 0, 3);
		$attrName = substr($name, 3, strlen($name));
		switch($methodName) {
			case "get":
				return $this->values[$attrName];
			case "set":
				$this->values[$attrName] = $args[0];
				break;
		}
	}

	public function setData($data = array()) {
		// Seta os atributos de forma dinânmica
		foreach ($data as $key => $value) {
			$this->{"set" . $key}($value);
		}
	}

	public function getData() {
		// Retorna os valores
		return $this->values;
	}
}

?>