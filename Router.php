<?php
class Router {
    private $routes = [];
    public function add(string $route, callable $handler): void {
        $this->routes[$route] = $handler;
    }
public function dispatch(string $defaultRoute): void{
    $requestRoute = $_GET['action'] ?? $defaultRoute;
    
    if(isset($this->routes[$requestRoute])){
        call_user_func($this->routes[$requestRoute]);
        return;
    }
    http_response_code(404);
    echo "ruta no encontrada";
}
}
?>