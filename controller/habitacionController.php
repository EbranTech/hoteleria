<?php
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class HabitacionController {
    private $modelo;
    private $bitacora;

    public function __construct() {
        $con      = new Conexion();
        $conexion = $con->conectar();
        $this->modelo   = new HabitacionModel($conexion);
        $this->bitacora = new BitacoraModel($conexion);
    }

    public function index(): void {
        requireRole(['admin', 'gerente', 'operativo']);
        $habitaciones = $this->modelo->getHabitaciones();
        require_once __DIR__ . '/../view/habitaciones/index.php';
    }

    public function new(): void {
        requireRole(['admin', 'gerente', 'operativo']);
        require_once __DIR__ . '/../view/habitaciones/new.php';
    }

    public function create(): void {
        requireRole(['admin', 'gerente', 'operativo']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'numero'       => trim($_POST['numero']       ?? ''),
                'piso'         => trim($_POST['piso']         ?? '1'),
                'tipo'         => trim($_POST['tipo']         ?? 'Simple'),
                'capacidad'    => trim($_POST['capacidad']    ?? '2'),
                'precio_noche' => trim($_POST['precio_noche'] ?? '0'),
                'descripcion'  => trim($_POST['descripcion']  ?? ''),
                'estado'       => trim($_POST['estado']       ?? 'D'),
            ];
            $ok = $this->modelo->crearHabitacion($datos);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'CREAR_HABITACION',
                    'modulo'         => 'habitaciones',
                    'descripcion'    => "Habitación #{$datos['numero']} creada",
                ]);
            }
            header("Location: index.php?action=habitaciones");
            exit;
        }
    }

    public function editar(): void {
        requireRole(['admin', 'gerente']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $habitacion = $this->modelo->getHabitacion($id);
            if ($habitacion) {
                require_once __DIR__ . '/../view/habitaciones/show.php';
            } else {
                echo "Habitación no encontrada.";
            }
        }
    }

    public function update(): void {
        requireRole(['admin', 'gerente']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $datos = [
                    'numero'       => trim($_POST['numero']       ?? ''),
                    'piso'         => trim($_POST['piso']         ?? '1'),
                    'tipo'         => trim($_POST['tipo']         ?? 'Simple'),
                    'capacidad'    => trim($_POST['capacidad']    ?? '2'),
                    'precio_noche' => trim($_POST['precio_noche'] ?? '0'),
                    'descripcion'  => trim($_POST['descripcion']  ?? ''),
                    'estado'       => trim($_POST['estado']       ?? 'D'),
                ];
                $ok = $this->modelo->actualizarHabitacion($datos, $id);
                if ($ok) {
                    $sesion = getUsuarioSesion();
                    $this->bitacora->registrar([
                        'id_usuario'     => $sesion['id'],
                        'usuario_nombre' => $sesion['nombre'],
                        'accion'         => 'ACTUALIZAR_HABITACION',
                        'modulo'         => 'habitaciones',
                        'descripcion'    => "Habitación id:{$id} actualizada",
                    ]);
                }
            }
            header("Location: index.php?action=habitaciones");
            exit;
        }
    }

    public function eliminar(): void {
        requireRole(['admin', 'gerente']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $ok = $this->modelo->eliminarHabitacion($id);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'ELIMINAR_HABITACION',
                    'modulo'         => 'habitaciones',
                    'descripcion'    => "Habitación id:{$id} eliminada",
                ]);
            }
            header("Location: index.php?action=habitaciones");
            exit;
        }
    }
}
?>
