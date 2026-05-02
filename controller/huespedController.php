<?php
require_once __DIR__ . '/../models/Huesped.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class HuespedController {
    private $modelo;
    private $bitacora;

    public function __construct() {
        $con      = new Conexion();
        $conexion = $con->conectar();
        $this->modelo   = new HuespedModel($conexion);
        $this->bitacora = new BitacoraModel($conexion);
    }

    public function index(): void {
        requireRole(['admin', 'gerente', 'operativo']);
        $huespedes = $this->modelo->getHuespedes();
        require_once __DIR__ . '/../view/huespedes/index.php';
    }

    public function new(): void {
        requireRole(['admin']);
        require_once __DIR__ . '/../view/huespedes/new.php';
    }

    public function create(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sesion = getUsuarioSesion();
            $datos = [
                'nombre'       => trim($_POST['nombre']       ?? ''),
                'apellido'     => trim($_POST['apellido']     ?? ''),
                'dpi'          => trim($_POST['dpi']          ?? ''),
                'telefono'     => trim($_POST['telefono']     ?? ''),
                'email'        => trim($_POST['email']        ?? ''),
                'nacionalidad' => trim($_POST['nacionalidad'] ?? 'Guatemalteca'),
                'estado'       => trim($_POST['estado']       ?? 'A'),
                'id_usuario'   => $sesion['id'],
            ];
            $ok = $this->modelo->crearHuesped($datos);
            if ($ok) {
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'CREAR_HUESPED',
                    'modulo'         => 'huespedes',
                    'descripcion'    => "Huésped creado: {$datos['nombre']} {$datos['apellido']}",
                ]);
            }
            header("Location: index.php?action=huespedes");
            exit;
        }
    }

    public function editar(): void {
        requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $huesped = $this->modelo->getHuesped($id);
            if ($huesped) {
                require_once __DIR__ . '/../view/huespedes/show.php';
            } else {
                echo "Huésped no encontrado.";
            }
        }
    }

    public function update(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $datos = [
                    'nombre'       => trim($_POST['nombre']       ?? ''),
                    'apellido'     => trim($_POST['apellido']     ?? ''),
                    'dpi'          => trim($_POST['dpi']          ?? ''),
                    'telefono'     => trim($_POST['telefono']     ?? ''),
                    'email'        => trim($_POST['email']        ?? ''),
                    'nacionalidad' => trim($_POST['nacionalidad'] ?? 'Guatemalteca'),
                    'estado'       => trim($_POST['estado']       ?? 'A'),
                ];
                $ok = $this->modelo->actualizarHuesped($datos, $id);
                if ($ok) {
                    $sesion = getUsuarioSesion();
                    $this->bitacora->registrar([
                        'id_usuario'     => $sesion['id'],
                        'usuario_nombre' => $sesion['nombre'],
                        'accion'         => 'ACTUALIZAR_HUESPED',
                        'modulo'         => 'huespedes',
                        'descripcion'    => "Huésped id:{$id} actualizado",
                    ]);
                }
            }
            header("Location: index.php?action=huespedes");
            exit;
        }
    }

    public function eliminar(): void {
        requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $ok = $this->modelo->eliminarHuesped($id);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'ELIMINAR_HUESPED',
                    'modulo'         => 'huespedes',
                    'descripcion'    => "Huésped id:{$id} eliminado",
                ]);
            }
            header("Location: index.php?action=huespedes");
            exit;
        }
    }
}
?>
