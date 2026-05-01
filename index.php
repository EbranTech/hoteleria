<?php
session_start();
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/helpers/session.php';

// Controladores
require_once __DIR__ . '/controller/authController.php';
require_once __DIR__ . '/controller/usuarioController.php';
require_once __DIR__ . '/controller/habitacionController.php';
require_once __DIR__ . '/controller/huespedController.php';
require_once __DIR__ . '/controller/reservaController.php';
require_once __DIR__ . '/controller/facturaController.php';

$router = new Router();

// ---- Instancias ----
$authController       = new AuthController();
$usuarioController    = new UsuarioController();
$habitacionController = new HabitacionController();
$huespedController    = new HuespedController();
$reservaController    = new ReservaController();
$facturaController    = new FacturaController();

// ---- Rutas: Auth (públicas) ----
$router->add('login',      [$authController, 'showLogin']);
$router->add('login_post', [$authController, 'doLogin']);
$router->add('logout',     [$authController, 'logout']);

// ---- Rutas: Usuarios ----
$router->add('usuarios',       [$usuarioController, 'index']);
$router->add('usuario_new',    [$usuarioController, 'new']);
$router->add('usuario_create', [$usuarioController, 'create']);
$router->add('editar',         [$usuarioController, 'editar']);
$router->add('usuario_update', [$usuarioController, 'update']);
$router->add('eliminar',       [$usuarioController, 'eliminar']);

// ---- Rutas: Habitaciones ----
$router->add('habitaciones',        [$habitacionController, 'index']);
$router->add('habitacion_new',      [$habitacionController, 'new']);
$router->add('habitacion_create',   [$habitacionController, 'create']);
$router->add('habitacion_editar',   [$habitacionController, 'editar']);
$router->add('habitacion_update',   [$habitacionController, 'update']);
$router->add('habitacion_eliminar', [$habitacionController, 'eliminar']);

// ---- Rutas: Huéspedes ----
$router->add('huespedes',       [$huespedController, 'index']);
$router->add('huesped_new',     [$huespedController, 'new']);
$router->add('huesped_create',  [$huespedController, 'create']);
$router->add('huesped_editar',  [$huespedController, 'editar']);
$router->add('huesped_update',  [$huespedController, 'update']);
$router->add('huesped_eliminar',[$huespedController, 'eliminar']);

// ---- Rutas: Reservas ----
$router->add('reservas',        [$reservaController, 'index']);
$router->add('reserva_new',     [$reservaController, 'new']);
$router->add('reserva_create',  [$reservaController, 'create']);
$router->add('reserva_editar',  [$reservaController, 'editar']);
$router->add('reserva_update',  [$reservaController, 'update']);
$router->add('reserva_eliminar',[$reservaController, 'eliminar']);

// ---- Rutas: Facturas ----
$router->add('facturas',        [$facturaController, 'index']);
$router->add('factura_new',     [$facturaController, 'new']);
$router->add('factura_create',  [$facturaController, 'create']);
$router->add('factura_editar',  [$facturaController, 'editar']);
$router->add('factura_update',  [$facturaController, 'update']);
$router->add('factura_eliminar',[$facturaController, 'eliminar']);

// Ruta por defecto → login
$routeDefault = 'login';
$router->dispatch($routeDefault);
?>