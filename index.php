<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'motus';

require 'vendor/autoload.php';


$app = new \Slim\App;
$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';charset=utf8;dbname=' . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="motus";
    $dbh = new PDO("mysql:host=$dbhost;charset=utf8;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}


$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

//Usando objetos, requiere crear Mapper y Entity
$app->get('/assessment', function (Request $request, Response $response) {
    $mapper = new AssessmentMapper($this->db);
    $assessments = $mapper->getAssessments();

    return json_encode($assessments, JSON_PRETTY_PRINT);
});


//Sin usar objetos... más simple, menos mantenible
$app->get('/assessments', function (Request $request, Response $response) {
    $sql = "SELECT * FROM assessment";
    try {
        $stmt = getConnection()->query($sql);
        $assessments = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return json_encode($assessments, JSON_PRETTY_PRINT);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
});

$app->get('/assessment/{id}', function (Request $request, Response $response, array $args) {
    $mapper = new AssessmentMapper($this->db);
    $id = $args['id'];
    $assessment = $mapper->getAssessmentById($id);
    print_r ($assessment);
});


$app->run();
