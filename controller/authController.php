<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class AuthController {
    private $modelo;
    private $bitacora;

    public function __construct() {
        $con      = new Conexion();
        $conexion = $con->conectar();
        $this->modelo   = new UsuarioModel($conexion);
        $this->bitacora = new BitacoraModel($conexion);
    }

    /** Muestra el formulario de login */
    public function showLogin(): void {
        if (isLoggedIn()) {
            header('Location: index.php?action=reservas');
            exit;
        }
        $error = null;
        require_once __DIR__ . '/../view/auth/login.php';
    }

    /** Procesa el POST del formulario de login */
    public function doLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $clave    = trim($_POST['clave']    ?? '');
        $usuario  = $this->modelo->login($username, $clave);

        if ($usuario) {
            $_SESSION['usuario_id']       = $usuario['id'];
            $_SESSION['usuario_nombre']   = $usuario['nombre'];
            $_SESSION['usuario_username'] = $usuario['username'];
            $_SESSION['usuario_rol']      = $usuario['rol'];

            $this->bitacora->registrar([
                'id_usuario'     => $usuario['id'],
                'usuario_nombre' => $usuario['nombre'],
                'accion'         => 'LOGIN',
                'modulo'         => 'auth',
                'descripcion'    => 'Inicio de sesión exitoso',
            ]);

            header('Location: index.php?action=reservas');
            exit;
        }

        $error = 'Usuario o contraseña incorrectos.';
        require_once __DIR__ . '/../view/auth/login.php';
    }

    /** Cierra la sesión y redirige al login */
    public function logout(): void {
        if (isLoggedIn()) {
            $usuario = getUsuarioSesion();
            $this->bitacora->registrar([
                'id_usuario'     => $usuario['id'],
                'usuario_nombre' => $usuario['nombre'],
                'accion'         => 'LOGOUT',
                'modulo'         => 'auth',
                'descripcion'    => 'Cierre de sesión',
            ]);
        }
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
