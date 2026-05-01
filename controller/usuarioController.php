<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class UsuarioController {
    private $modelo;
    private $bitacora;

    public function __construct() {
        $con      = new Conexion();
        $conexion = $con->conectar();
        $this->modelo   = new UsuarioModel($conexion);
        $this->bitacora = new BitacoraModel($conexion);
    }

    public function index(): void {
        requireRole(['admin']);
        $usuarios = $this->modelo->getUsuarios();
        require_once __DIR__ . '/../view/usuarios/index.php';
    }

    public function new(): void {
        requireRole(['admin']);
        require_once __DIR__ . '/../view/usuarios/new.php';
    }

    public function create(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre'   => trim($_POST['nombre']   ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'clave'    => trim($_POST['clave']    ?? ''),
                'estado'   => trim($_POST['estado']   ?? '1'),
                'rol'      => trim($_POST['rol']      ?? 'operativo'),
            ];
            $ok = $this->modelo->crearUsuario($datos);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'CREAR_USUARIO',
                    'modulo'         => 'usuarios',
                    'descripcion'    => "Usuario creado: {$datos['username']} (rol: {$datos['rol']})",
                ]);
            }
            header("Location: index.php?action=usuarios");
            exit;
        }
    }

    public function editar(): void {
        requireRole(['admin']);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $usuario = $this->modelo->getUsuario((int)$id);
            if ($usuario) {
                require_once __DIR__ . '/../view/usuarios/show.php';
            } else {
                echo "Usuario no encontrado.";
            }
        }
    }

    public function update(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $datos = [
                    'nombre'   => trim($_POST['nombre']   ?? ''),
                    'username' => trim($_POST['username'] ?? ''),
                    'estado'   => trim($_POST['estado']   ?? '1'),
                    'rol'      => trim($_POST['rol']      ?? 'operativo'),
                ];
                $ok = $this->modelo->actualizarUsuario($datos, (int)$id);
                if ($ok) {
                    $sesion = getUsuarioSesion();
                    $this->bitacora->registrar([
                        'id_usuario'     => $sesion['id'],
                        'usuario_nombre' => $sesion['nombre'],
                        'accion'         => 'ACTUALIZAR_USUARIO',
                        'modulo'         => 'usuarios',
                        'descripcion'    => "Usuario id:{$id} actualizado",
                    ]);
                }
            }
            header("Location: index.php?action=usuarios");
            exit;
        }
    }

    public function eliminar(): void {
        requireRole(['admin']);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $ok = $this->modelo->eliminarUsuario((int)$id);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'ELIMINAR_USUARIO',
                    'modulo'         => 'usuarios',
                    'descripcion'    => "Usuario id:{$id} eliminado",
                ]);
            }
            header("Location: index.php?action=usuarios");
            exit;
        }
    }
}
?>