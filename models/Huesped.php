<?php
class HuespedModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /** Lista todos los huéspedes */
    public function getHuespedes(): array {
        $query = "SELECT h.*, u.username as registrado_por
                  FROM huespedes h
                  INNER JOIN usuarios u ON u.id = h.id_usuario
                  ORDER BY h.id_huesped ASC";
        $resultado = $this->conexion->query($query);
        $huespedes = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $huespedes[] = $fila;
            }
        }
        return $huespedes;
    }

    /**
     * Obtiene un huésped por id
     * @param int $id
     * @return array|null
     */
    public function getHuesped(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT * FROM huespedes WHERE id_huesped = ? LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Crea un huésped nuevo
     * @param array $datos
     * @return bool
     */
    public function crearHuesped(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO huespedes (nombre, apellido, dpi, telefono, email, nacionalidad, estado, id_usuario)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $nombre       = $datos['nombre']       ?? '';
        $apellido     = $datos['apellido']     ?? '';
        $dpi          = $datos['dpi']          ?? '';
        $telefono     = $datos['telefono']     ?? '';
        $email        = $datos['email']        ?? '';
        $nacionalidad = $datos['nacionalidad'] ?? 'Guatemalteca';
        $estado       = $datos['estado']       ?? 'A';
        $id_usuario   = (int)($datos['id_usuario'] ?? 0);

        $stmt->bind_param("sssssssi", $nombre, $apellido, $dpi, $telefono, $email, $nacionalidad, $estado, $id_usuario);
        return $stmt->execute();
    }

    /**
     * Actualiza un huésped
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarHuesped(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE huespedes SET nombre=?, apellido=?, dpi=?, telefono=?, email=?, nacionalidad=?, estado=?
             WHERE id_huesped=?"
        );
        if (!$stmt) return false;

        $nombre       = $datos['nombre']       ?? '';
        $apellido     = $datos['apellido']     ?? '';
        $dpi          = $datos['dpi']          ?? '';
        $telefono     = $datos['telefono']     ?? '';
        $email        = $datos['email']        ?? '';
        $nacionalidad = $datos['nacionalidad'] ?? 'Guatemalteca';
        $estado       = $datos['estado']       ?? 'A';

        $stmt->bind_param("ssssssi", $nombre, $apellido, $dpi, $telefono, $email, $nacionalidad, $estado, $id);
        return $stmt->execute();
    }

    /**
     * Elimina un huésped
     * @param int $id
     * @return bool
     */
    public function eliminarHuesped(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM huespedes WHERE id_huesped = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
