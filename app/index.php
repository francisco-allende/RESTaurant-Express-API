<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/isAdmin.php';
require_once './middlewares/isMozo.php';
require_once './middlewares/EstaLogeado.php';
require_once './middlewares/EstaLogeado_init.php';

require_once './controllers/TrabajadorController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/rest_aurant/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


// Routes
$app->group('sesion/', function (RouteCollectorProxy $group) {
  $group->post('/registrar', \TrabajadorController::class . ':Registrar');
  $group->post('/login', \TrabajadorController::class . ':Verificar');
  $group->get('[/]', \TrabajadorController::class . ':TraerTodos'); 
});

//TRABAJADORES
$app->group('/trabajador', function (RouteCollectorProxy $group) {
  $group->get('[/]', \TrabajadorController::class . ':TraerTodos'); 
  $group->get('/search_by_id/{id}', \TrabajadorController::class . ':TraerUno');
  $group->get('/leer/csv', \TrabajadorController::class . ':LeerCsv');
  $group->get('/download/csv', \TrabajadorController::class . ':DescargarCsv');
  $group->put('/modificar', \TrabajadorController::class . ':ModificarUno');
  $group->post('/upload/csv', \TrabajadorController::class . ':CrearCsv');
  $group->delete('/borrar', \TrabajadorController::class . ':BorrarUno')->add(new isAdmin());
});//->add(new EstaLogeado());

$app->group('/descargas', function (RouteCollectorProxy $group) {
  $group->get('/csv', \TrabajadorController::class . ':DescargarCsv');
});

  //PRODUCTOS
  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \ProductoController::class . ':TraerUno'); 
    $group->post('/alta', \ProductoController::class . ':CargarUno');
    $group->put('/preparar', \ProductoController::class . ':ModificarStatus');
    $group->put('/listo', \ProductoController::class . ':ModificarStatus');
    $group->put('/servir', \ProductoController::class . ':Servir')->add(new isMozo());
    $group->delete('/borrar', \ProductoController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

  //PEDIDOS
  $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \PedidoController::class . ':TraerUno'); 
    $group->get('/cuanto_falta/{id_mesa}/{id_pedido}', \PedidoController::class . ':CuantoFalta');
    $group->get('/cuanto_falta_por_pedido/{id_pedido}', \PedidoController::class . ':CuantoFaltaPorPedido');
    $group->get('/mostrar_productos/{id}', \PedidoController::class . ':TraerProductos'); 
    $group->post('/alta', \PedidoController::class . ':CargarUno')->add(new isMozo());
    $group->put('/modificar', \PedidoController::class . ':ModificarUno')->add(new isMozo());
    $group->put('/cobrar', \PedidoController::class . ':Cobrar')->add(new isMozo());
    $group->delete('/borrar', \PedidoController::class . ':BorrarUno')->add(new isMozo());
  })->add(new EstaLogeado());

  //MESAS
  $app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos'); 
    $group->get('/search_by_id/{id}', \MesaController::class . ':TraerUno'); 
    $group->post('/alta', \MesaController::class . ':CargarUno')->add(new isAdmin());
    $group->put('/modificar_status', \MesaController::class . ':ModificarStatus')->add(new isMozo());
    $group->put('/levantar', \MesaController::class . ':LevantarMesa')->add(new isMozo());
    $group->put('/cerrar', \MesaController::class . ':CerrarMesa')->add(new isAdmin());
    $group->put('/cliente_cambia_mesa', \MesaController::class . ':ClienteCambiaMesa')->add(new isMozo());
    $group->delete('/borrar', \MesaController::class . ':BorrarUno')->add(new isAdmin());
  });

  //Encuestas
  $app->group('/encuestas', function (RouteCollectorProxy $group) {
    $group->get('/mostrar', \EncuestaController::class . ':TraerTodos'); 
    $group->get('/mostrar/mejores_comentarios', \EncuestaController::class . ':TraerMejoresComentarios'); 
    $group->get('/mostrar/peores_comentarios', \EncuestaController::class . ':TraerPeoresComentarios'); 
    $group->post('/alta', \EncuestaController::class . ':CargarUno');
    $group->put('/modificar', \EncuestaController::class . ':Modificar');
    $group->delete('/borrar', \EncuestaController::class . ':BorrarUno')->add(new isAdmin());
  })->add(new EstaLogeado());

$app->get('/login', function (Request $request, Response $response) { 
  $response->getBody()->write('
  <!DOCTYPE html>
  <html>
    <head>
      <title>Login</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link rel="stylesheet" href="./css/login.css" /> 
    </head>
    <body>
      <div class="container">
        <h1>Login</h1>
        <form>
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <div class="buttons">
            <button type="submit" class="btn btn-success" id="login">Sing in</button>
            <button type="submit" class="btn btn-primary" id="registrar">Create Account</button>
          </div>
          <div id="message"></div>
        </form>
      </div>
      <script src="./js/login/login.js" type="module"></script>
    </body>
  </html>
  ');
    return $response;
});


$app->post('[/]', function (Request $request, Response $response) { 
  // Obtener el token del campo "token" del formulario
  $token = trim($request->getParsedBody()['token']);
  // Set the token in the "Authorization" header
  $request = $request->withHeader('Authorization', "Bearer $token");
  $response->getBody()->write('
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styles_index.css" /> 
  </head>
  <head>
  <body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg justify-content-around" style="background-color: transparent;">
        <a href="./index.html" class="navbar-brand">
            <img src="./imagenes/logo.png" alt="logo" id="img_logo">
        </a>
        <ul class="list-unstyled list-inline" id="menu">
            <li class="list-inline-item"><a href="#" class="href_non_decoration">Nosotros</a></li>
            <li class="list-inline-item"><a href="./administracion.html" class="href_non_decoration" target="_blank">Menu</a></li>
            <li class="list-inline-item"><a href="#" class="href_non_decoration">Envíos</a></li>
            <li class="list-inline-item"><a href="#" class="href_non_decoration">Contacto</a></li>
        </ul>
    </nav>

    <div class="jumbotron">
        <header>
            <h1 class="title">Restaurant Express</h1>
            <h2 class="subtitle"> API de uso interno. Permite chequear, cargar, modificar y borrar pedidos y mesas </h2>
        </header>
    </div>

    
    <!--Spinner de carga-->
    <div id="containerSpinner">   
    </div>

    <div class="container">
      <p id="pbtnPhp"><button class="btn btn-success" id="btnCards"> Build cards </button></p>
    </div>


    <!--Cards-->
    <div class="container cardContainer" id="cardContainer">
    </div>

  <div id="divTabla"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
  <script src="./js/app.js" type="module"></script>
  </body>
</html>');
  return $response;
})->add(new EstaLogeado_init());

$app->run();


