<?php
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../models/Bitacora.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class FacturaController {
    private $modelo;
    private $bitacora;

    public function __construct() {
        $con      = new Conexion();
        $conexion = $con->conectar();
        $this->modelo   = new FacturaModel($conexion);
        $this->bitacora = new BitacoraModel($conexion);
    }

    public function index(): void {
        requireRole(['admin', 'gerente', 'operativo']);
        $facturas = $this->modelo->getFacturas();
        require_once __DIR__ . '/../view/facturas/index.php';
    }

    public function new(): void {
        requireRole(['admin', 'operativo']);
        $reservas  = $this->modelo->getReservasConfirmadas();
        $huespedes = $this->modelo->getHuespedesActivos();
        require_once __DIR__ . '/../view/facturas/new.php';
    }

    public function create(): void {
        requireRole(['admin', 'operativo']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sesion   = getUsuarioSesion();
            $subtotal = (float)($_POST['subtotal'] ?? 0);
            $impuesto = (float)($_POST['impuesto'] ?? 0);
            $datos = [
                'id_reserva'  => trim($_POST['id_reserva']  ?? ''),
                'id_huesped'  => trim($_POST['id_huesped']  ?? ''),
                'num_factura' => trim($_POST['num_factura'] ?? ''),
                'subtotal'    => $subtotal,
                'impuesto'    => $impuesto,
                'total'       => $subtotal + $impuesto,
                'metodo_pago' => trim($_POST['metodo_pago'] ?? 'EF'),
                'estado'      => trim($_POST['estado']      ?? 'P'),
                'id_usuario'  => $sesion['id'],
            ];
            $ok = $this->modelo->crearFactura($datos);
            if ($ok) {
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'CREAR_FACTURA',
                    'modulo'         => 'facturas',
                    'descripcion'    => "Factura {$datos['num_factura']} creada",
                ]);
            }
            header("Location: index.php?action=facturas");
            exit;
        }
    }

    public function editar(): void {
        requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $factura   = $this->modelo->getFactura($id);
            $reservas  = $this->modelo->getReservasConfirmadas();
            $huespedes = $this->modelo->getHuespedesActivos();
            if ($factura) {
                require_once __DIR__ . '/../view/facturas/show.php';
            } else {
                echo "Factura no encontrada.";
            }
        }
    }

    public function update(): void {
        requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $subtotal = (float)($_POST['subtotal'] ?? 0);
                $impuesto = (float)($_POST['impuesto'] ?? 0);
                $datos = [
                    'id_reserva'  => trim($_POST['id_reserva']  ?? ''),
                    'id_huesped'  => trim($_POST['id_huesped']  ?? ''),
                    'num_factura' => trim($_POST['num_factura'] ?? ''),
                    'subtotal'    => $subtotal,
                    'impuesto'    => $impuesto,
                    'total'       => $subtotal + $impuesto,
                    'metodo_pago' => trim($_POST['metodo_pago'] ?? 'EF'),
                    'estado'      => trim($_POST['estado']      ?? 'P'),
                ];
                $ok = $this->modelo->actualizarFactura($datos, $id);
                if ($ok) {
                    $sesion = getUsuarioSesion();
                    $this->bitacora->registrar([
                        'id_usuario'     => $sesion['id'],
                        'usuario_nombre' => $sesion['nombre'],
                        'accion'         => 'ACTUALIZAR_FACTURA',
                        'modulo'         => 'facturas',
                        'descripcion'    => "Factura id:{$id} actualizada",
                    ]);
                }
            }
            header("Location: index.php?action=facturas");
            exit;
        }
    }

    public function eliminar(): void {
        requireRole(['admin', 'gerente']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $ok = $this->modelo->eliminarFactura($id);
            if ($ok) {
                $sesion = getUsuarioSesion();
                $this->bitacora->registrar([
                    'id_usuario'     => $sesion['id'],
                    'usuario_nombre' => $sesion['nombre'],
                    'accion'         => 'ELIMINAR_FACTURA',
                    'modulo'         => 'facturas',
                    'descripcion'    => "Factura id:{$id} eliminada",
                ]);
            }
            header("Location: index.php?action=facturas");
            exit;
        }
    }
}
?>
