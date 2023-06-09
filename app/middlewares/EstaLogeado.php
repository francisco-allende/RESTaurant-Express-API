<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
require_once "./utils/AutentificadorJWT.php"; 

class EstaLogeado{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);

                if ($data->isAdmin == "si" || $data->isAdmin == "no") {
                    if ($data->isAdmin == "no") {
                        // Verificar token de la compra
                        AutentificadorJWT::VerificarToken($token);
                        $response = $handler->handle($request);
                    }else{
                        $response = $handler->handle($request);
                    }
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Solo usuarios logeados pueden acceder")));
                    $response = $response->withStatus(401);
                }
            } else {
                // Check if token is in query parameter
                $queryParams = $request->getQueryParams();
                if (isset($queryParams['token'])) {
                    $token = $queryParams['token'];
                    $queryParams = "";
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Token vacio")));
                    $response = $response->withStatus(401);
                    return $response->withHeader('Content-Type', 'application/json');
                }
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        // Set token in Authorization header
        $response = $response->withHeader('Authorization', 'Bearer ' . $token);
        return $response->withHeader('Content-Type', 'application/json');
    }
}