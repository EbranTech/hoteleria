<?php
class FacturaModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /** Lista todas las facturas con datos de reserva y huésped */
    public function getFacturas(): array {
        $query = "SELECT f.*,
                         CONCAT(h.nombre, ' ', h.apellido) AS nombre_huesped,
                         r.fecha_entrada, r.fecha_salida
                  FROM facturas f
                  INNER JOIN huespedes h ON h.id_huesped = f.id_huesped
                  INNER JOIN reservas  r ON r.id_reserva = f.id_reserva
                  ORDER BY f.id_factura ASC";
        $resultado = $this->conexion->query($query);
        $facturas = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $facturas[] = $fila;
            }
        }
        return $facturas;
    }

    /**
     * Obtiene una factura por id
     * @param int $id
     * @return array|null
     */
    public function getFactura(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT * FROM facturas WHERE id_factura = ? LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /** Obtiene reservas confirmadas para dropdown */
    public function getReservasConfirmadas(): array {
        $resultado = $this->conexion->query(
            "SELECT r.id_reserva,
                    CONCAT('#', r.id_reserva, ' - ', h.nombre, ' ', h.apellido, ' (', r.fecha_entrada, ' → ', r.fecha_salida, ')') AS descripcion
             FROM reservas r
             INNER JOIN huespedes h ON h.id_huesped = r.id_huesped
             WHERE r.estado IN ('P','C')
             ORDER BY r.id_reserva DESC"
        );
        $lista = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $lista[] = $fila;
            }
        }
        return $lista;
    }

    /** Obtiene huéspedes activos para dropdown */
    public function getHuespedesActivos(): array {
        $resultado = $this->conexion->query(
            "SELECT id_huesped, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM huespedes WHERE estado = 'A' ORDER BY nombre ASC"
        );
        $lista = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $lista[] = $fila;
            }
        }
        return $lista;
    }

    /**
     * Crea una factura nueva
     * @param array $datos
     * @return bool
     */
    public function crearFactura(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO facturas (id_reserva, id_huesped, num_factura, subtotal, impuesto, total, metodo_pago, estado, id_usuario)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $id_reserva  = (int)($datos['id_reserva']  ?? 0);
        $id_huesped  = (int)($datos['id_huesped']  ?? 0);
        $num_factura = $datos['num_factura']        ?? '';
        $subtotal    = $datos['subtotal']           ?? 0.00;
        $impuesto    = $datos['impuesto']           ?? 0.00;
        $total       = $datos['total']              ?? 0.00;
        $metodo_pago = $datos['metodo_pago']        ?? 'EF';
        $estado      = $datos['estado']             ?? 'P';
        $id_usuario  = (int)($datos['id_usuario']   ?? 0);

        $stmt->bind_param("iisdddssi", $id_reserva, $id_huesped, $num_factura, $subtotal, $impuesto, $total, $metodo_pago, $estado, $id_usuario);
        return $stmt->execute();
    }

    /**
     * Actualiza una factura
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarFactura(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE facturas SET id_reserva=?, id_huesped=?, num_factura=?, subtotal=?, impuesto=?, total=?, metodo_pago=?, estado=?
             WHERE id_factura=?"
        );
        if (!$stmt) return false;

        $id_reserva  = (int)($datos['id_reserva']  ?? 0);
        $id_huesped  = (int)($datos['id_huesped']  ?? 0);
        $num_factura = $datos['num_factura']        ?? '';
        $subtotal    = $datos['subtotal']           ?? 0.00;
        $impuesto    = $datos['impuesto']           ?? 0.00;
        $total       = $datos['total']              ?? 0.00;
        $metodo_pago = $datos['metodo_pago']        ?? 'EF';
        $estado      = $datos['estado']             ?? 'P';

        $stmt->bind_param("iisdddssi", $id_reserva, $id_huesped, $num_factura, $subtotal, $impuesto, $total, $metodo_pago, $estado, $id);
        return $stmt->execute();
    }

    /**
     * Elimina una factura
     * @param int $id
     * @return bool
     */
    public function eliminarFactura(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM facturas WHERE id_factura = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
