<?php
class HabitacionModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /** Lista todas las habitaciones */
    public function getHabitaciones(): array {
        $query = "SELECT * FROM habitaciones ORDER BY id_habitacion ASC";
        $resultado = $this->conexion->query($query);
        $habitaciones = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $habitaciones[] = $fila;
            }
        }
        return $habitaciones;
    }

    /**
     * Obtiene una habitación por id
     * @param int $id
     * @return array|null
     */
    public function getHabitacion(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT * FROM habitaciones WHERE id_habitacion = ? LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Crea una habitación nueva
     * @param array $datos
     * @return bool
     */
    public function crearHabitacion(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO habitaciones (numero, piso, tipo, capacidad, precio_noche, descripcion, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $numero       = $datos['numero']       ?? '';
        $piso         = (int)($datos['piso']   ?? 1);
        $tipo         = $datos['tipo']         ?? 'Simple';
        $capacidad    = (int)($datos['capacidad'] ?? 2);
        $precio_noche = $datos['precio_noche'] ?? 0.00;
        $descripcion  = $datos['descripcion']  ?? '';
        $estado       = $datos['estado']       ?? 'D';

        $stmt->bind_param("siisdss", $numero, $piso, $tipo, $capacidad, $precio_noche, $descripcion, $estado);
        return $stmt->execute();
    }

    /**
     * Actualiza una habitación
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarHabitacion(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE habitaciones SET numero=?, piso=?, tipo=?, capacidad=?, precio_noche=?, descripcion=?, estado=?
             WHERE id_habitacion=?"
        );
        if (!$stmt) return false;

        $numero       = $datos['numero']       ?? '';
        $piso         = (int)($datos['piso']   ?? 1);
        $tipo         = $datos['tipo']         ?? 'Simple';
        $capacidad    = (int)($datos['capacidad'] ?? 2);
        $precio_noche = $datos['precio_noche'] ?? 0.00;
        $descripcion  = $datos['descripcion']  ?? '';
        $estado       = $datos['estado']       ?? 'D';

        $stmt->bind_param("siisdssi", $numero, $piso, $tipo, $capacidad, $precio_noche, $descripcion, $estado, $id);
        return $stmt->execute();
    }

    /**
     * Elimina una habitación
     * @param int $id
     * @return bool
     */
    public function eliminarHabitacion(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM habitaciones WHERE id_habitacion = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
