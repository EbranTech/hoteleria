<?php
class ReservaModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /** Lista todas las reservas con datos de huésped y habitación */
    public function getReservas(): array {
        $query = "SELECT r.*,
                         CONCAT(h.nombre, ' ', h.apellido) AS nombre_huesped,
                         hab.numero AS numero_habitacion
                  FROM reservas r
                  INNER JOIN huespedes h   ON h.id_huesped     = r.id_huesped
                  INNER JOIN habitaciones hab ON hab.id_habitacion = r.id_habitacion
                  ORDER BY r.id_reserva ASC";
        $resultado = $this->conexion->query($query);
        $reservas = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $reservas[] = $fila;
            }
        }
        return $reservas;
    }

    /**
     * Obtiene una reserva por id
     * @param int $id
     * @return array|null
     */
    public function getReserva(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT * FROM reservas WHERE id_reserva = ? LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /** Obtiene lista de huéspedes activos para dropdown */
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

    /** Obtiene lista de habitaciones disponibles para dropdown */
    public function getHabitacionesDisponibles(): array {
        $resultado = $this->conexion->query(
            "SELECT id_habitacion, CONCAT('Hab. ', numero, ' - ', tipo, ' (Q', precio_noche, ')') AS descripcion FROM habitaciones WHERE estado = 'D' ORDER BY numero ASC"
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
     * Crea una reserva nueva
     * @param array $datos
     * @return bool
     */
    public function crearReserva(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO reservas (id_huesped, id_habitacion, fecha_entrada, fecha_salida, precio_acordado, estado, observaciones, id_usuario)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $id_huesped     = (int)($datos['id_huesped']     ?? 0);
        $id_habitacion  = (int)($datos['id_habitacion']  ?? 0);
        $fecha_entrada  = $datos['fecha_entrada']        ?? '';
        $fecha_salida   = $datos['fecha_salida']         ?? '';
        $precio_acordado= $datos['precio_acordado']      ?? 0.00;
        $estado         = $datos['estado']               ?? 'P';
        $observaciones  = $datos['observaciones']        ?? '';
        $id_usuario     = (int)($datos['id_usuario']     ?? 0);

        $stmt->bind_param("iissdssi", $id_huesped, $id_habitacion, $fecha_entrada, $fecha_salida, $precio_acordado, $estado, $observaciones, $id_usuario);
        return $stmt->execute();
    }

    /**
     * Actualiza una reserva
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarReserva(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE reservas SET id_huesped=?, id_habitacion=?, fecha_entrada=?, fecha_salida=?, precio_acordado=?, estado=?, observaciones=?
             WHERE id_reserva=?"
        );
        if (!$stmt) return false;

        $id_huesped     = (int)($datos['id_huesped']    ?? 0);
        $id_habitacion  = (int)($datos['id_habitacion'] ?? 0);
        $fecha_entrada  = $datos['fecha_entrada']       ?? '';
        $fecha_salida   = $datos['fecha_salida']        ?? '';
        $precio_acordado= $datos['precio_acordado']     ?? 0.00;
        $estado         = $datos['estado']              ?? 'P';
        $observaciones  = $datos['observaciones']       ?? '';

        $stmt->bind_param("iissdssi", $id_huesped, $id_habitacion, $fecha_entrada, $fecha_salida, $precio_acordado, $estado, $observaciones, $id);
        return $stmt->execute();
    }

    /**
     * Elimina una reserva
     * @param int $id
     * @return bool
     */
    public function eliminarReserva(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM reservas WHERE id_reserva = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
