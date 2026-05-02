<?php
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class ReservaController {
    private $modelo;
    private $bitacora;

    public function __construct() {
        $con      = new Conexion();
        $conexion = $con->conectar();
        $this->modelo   = new ReservaModel($conexion);
        $this->bitacora = new BitacoraModel($conexion);
    }

    public function index(): void {
        requireRole(['admin', 'gerente', 'operativo']);
        $reservas = $this->modelo->getReservas();
        require_once __DIR__ . '/../view/reservas/index.php';
    }

    public function new(): void {
        requireRole(['admin', 'operativo']);
        $huespedes    = $this->modelo->getHuespedesActivos();
        $habitaciones = $this->modelo->getHabitacionesDisponibles();
        $error        = $_GET['error'] ?? null;
        require_once __DIR__ . '/../view/reservas/new.php';
    }

    public function create(): void {
        requireRole(['admin', 'operativo']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sesion        = getUsuarioSesion();
            $id_habitacion = (int)trim($_POST['id_habitacion'] ?? 0);

            // Validar que la habitación esté disponible antes de reservar
            $estadoHab = $this->modelo->getEstadoHabitacion($id_habitacion);
            if ($estadoHab !== 'D') {
                header("Location: index.php?action=reserva_new&error=habitacion_ocupada");
                exit;
            }

            $datos = [
                'id_huesped'      => trim($_POST['id_huesped']      ?? ''),
                'id_habitacion'   => $id_habitacion,
                'fecha_entrada'   => trim($_POST['fecha_entrada']   ?? ''),
                'fecha_salida'    => trim($_POST['fecha_salida']    ?? ''),
                'precio_acordado' => trim($_POST['precio_acordado'] ?? '0'),
                'estado'          => trim($_POST['estado']          ?? 'P'),
                'observaciones'   => trim($_POST['observaciones']   ?? ''),
                'id_usuario'      => $sesion['id'],
            ];

            $ok = $this->modelo->crearReserva($datos);
            if ($ok) {
                // Marcar la habitación como ocupada
                $this->modelo->actualizarEstadoHabitacion($id_habitacion, 'O');

                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'CREAR_RESERVA',
                    'modulo'         => 'reservas',
                    'descripcion'    => "Reserva creada para huésped id:{$datos['id_huesped']}, habitación id:{$id_habitacion}",
                ]);
            }
            header("Location: index.php?action=reservas");
            exit;
        }
    }

    public function editar(): void {
        requireRole(['admin','gerente']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $reserva      = $this->modelo->getReserva($id);
            $huespedes    = $this->modelo->getHuespedesActivos();
            $habitaciones = $this->modelo->getHabitacionesDisponibles();
            if ($reserva) {
                require_once __DIR__ . '/../view/reservas/show.php';
            } else {
                echo "Reserva no encontrada.";
            }
        }
    }

    public function update(): void {
        requireRole(['admin', 'gerente']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $datos = [
                    'id_huesped'      => trim($_POST['id_huesped']      ?? ''),
                    'id_habitacion'   => trim($_POST['id_habitacion']   ?? ''),
                    'fecha_entrada'   => trim($_POST['fecha_entrada']   ?? ''),
                    'fecha_salida'    => trim($_POST['fecha_salida']    ?? ''),
                    'precio_acordado' => trim($_POST['precio_acordado'] ?? '0'),
                    'estado'          => trim($_POST['estado']          ?? 'P'),
                    'observaciones'   => trim($_POST['observaciones']   ?? ''),
                ];
                $ok = $this->modelo->actualizarReserva($datos, $id);
                if ($ok) {
                    $sesion = getUsuarioSesion();
                    $this->bitacora->registrar([
                        'id_usuario'     => $sesion['id'],
                        'usuario_nombre' => $sesion['nombre'],
                        'accion'         => 'ACTUALIZAR_RESERVA',
                        'modulo'         => 'reservas',
                        'descripcion'    => "Reserva id:{$id} actualizada",
                    ]);
                }
            }
            header("Location: index.php?action=reservas");
            exit;
        }
    }

    public function eliminar(): void {
        requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $ok = $this->modelo->eliminarReserva($id);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'ELIMINAR_RESERVA',
                    'modulo'         => 'reservas',
                    'descripcion'    => "Reserva id:{$id} eliminada",
                ]);
            }
            header("Location: index.php?action=reservas");
            exit;
        }
    }
}
?>
