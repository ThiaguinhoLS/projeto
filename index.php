<?php 
session_start();
require_once("vendor/autoload.php");

define("DIR_ROOT", dirname(__FILE__));

use \Slim\Slim;
use \Src\Page;
use \Src\PageAdmin;
use \Src\Models\User;

$app = new Slim();
$app->config("debug", true);

$app->get("/teste", function() {
	$page = new Page();
	$page->setTpl("teste");
});

$app->get("/", function() {
	// Rota da página inicial
	$page = new Page();
	$page->setTpl("index");	
});

$app->get("/admin", function() {
	// Rota da página de administração
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("index");
});

$app->get("/admin/login", function() {
	// Rota da página de login
	$page = new PageAdmin([
		"header" => true,
		"footer" => true
	]);
	$page->setTpl("login");
});

$app->post("/admin/login", function() {
	// Rota de validação do login
	User::login($_POST["username"], $_POST["password"]);
	header("Location: /admin");
	exit;
});

$app->get("/admin/logout", function() {
	// Rota de logout
	User::logout();
	header("Location: /admin/login");
	exit;
});

$app->get("/quem-somos", function() {
	// Rota para página de quem somos
	$page = new Page();
	$page->setTpl("quem-somos");
});

$app->get("/produtos", function() {
	// Rota para listagem de todos os produtos
	echo "Produtos";
});

$app->get("/contato", function() {
	// Rota da página de contato
	$page = new Page();
	$page->setTpl("contato");
});

$app->get("/admin/users", function() {
	// Rota para listagem de todos os usuários
	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users" => $users
	));
});

$app->get("/admin/users/create", function() {
	User::verifyLogin();
	$page = new PageAdmin([
		"header" => true,
		"footer" => true
	]);
	$page->setTpl("users-create");
});

$app->get("/admin/users/:iduser/delete", function($iduser) {
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;
});

$app->get("/admin/users/:iduser", function($iduser) {
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin([
		"header" => true,
		"footer" => true
	]);
	$page->setTpl("users-update", array(
		"user" => $user->getData()
	));
});

$app->post("/admin/users/create", function() {
	User::verifyLogin();
	$_POST["isadmin"]  = isset($_POST["isadmin"]) ? 1 : 0;
	$user = new User();
	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;
});

$app->post("/admin/users/:iduser", function($iduser) {
	User::verifyLogin();
	$_POST["isadmin"] = isset($_POST["isadmin"]) ? 1 : 0;
	$user = new User();
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();
	header("Location: /admin/users");
	exit;
});

$app->get("/admin/esqueceu-a-senha", function() {
	$page = new PageAdmin([
		"header" => true,
		"footer" => true
	]);
	$page->setTpl("esqueceu_a_senha");

});

$app->post("/admin/esqueceu-a-senha", function() {
	
});

$app->run();

?>