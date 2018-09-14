<?php

namespace Src\Models;
use \Src\DB\Sql;
use \Src\Model;

class User extends Model {

	const SESSION = "userSession";
	
	public static function login($username, $password) {
		$sql = new Sql();
		$results  = $sql->select("SELECT *FROM tb_users WHERE username = :USERNAME", array(
			"USERNAME" => $username
		));
		if (count($results) == 0) {
			throw new \Exception("Usuário inexistente ou senha inválida");
		}
		$data = $results[0];
		// password_hash(string, PASSWORD_DEFAULT) Gera um hash de uma string
		if (password_verify($password, $data["password"])) {
			$user = new User();
			$user->setData($data);
			$_SESSION[User::SESSION] = $user->getData();
			return $user;
		} else {
			throw new \Exception("Usuário inexistente ou senha inválida"); // Exception não existe neste namespace
		}
	}

	public static function verifyLogin($inadmin = true) {
		if (!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION] || (bool)$_SESSION[User::SESSION]["isadmin"] !== $inadmin) {
			header("Location: /admin/login");
			exit;
		}
	}

	public static function  logout() {
		$_SESSION[User::SESSION] = NULL;
	}

	public static function listAll() {
		$sql = new Sql();
		//$sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.nome");
		return $sql->select("SELECT * FROM tb_users");	
	}

	public function save() {
		$sql = new Sql();
		$result = $sql->query("INSERT INTO tb_users (username, password, isadmin) VALUES (:USERNAME, :PASSWORD, :ISADMIN)", array(
			":USERNAME" => $this->getusername(),
			":PASSWORD" => $this->getpassword(),
			":ISADMIN" => $this->getisadmin()
		));
	}

	public function  get($iduser) {
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users u INNER JOIN tb_persons p ON u.iduser = p.idperson", array(
			":IDUSER" => $iduser,
		));
		$this->setData($results[0]);
	}

	public function update() {
		$sql = new Sql();
		$result = $sql->query("UPDATE tb_users SET username = :USERNAME, password = :PASSWORD, isadmin = :ISADMIN WHERE iduser =  :IDUSER", array(
			":USERNAME" => $this->getusername(),
			":PASSWORD" => $this->getpassword(),
			":ISADMIN" => $this->getisadmin(),
			"IDUSER" => $this->getiduser()
		));
	}

	public function delete() {
		$sql = new Sql();
		$sql->query("DELETE FROM tb_users WHERE iduser = :IDUSER", array(
			":IDUSER" => $this->getiduser()
		));
	}

	public static function getForgot($email) {
		$sql = new Sql();
	}
}

?>