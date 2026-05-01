<?php
class UsuarioModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    public function getUsuarios(): array {
        $query = "SELECT * FROM usuarios ORDER BY id ASC";
        $resultado = $this->conexion->query($query);
        $usuarios = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $usuarios[] = $fila;
            }
        }
        return $usuarios;
    }

    /**
     * Obtiene un usuario por id
     * @param int $id
     * @return array|null
     */
    public function getUsuario(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE id = ? LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Valida credenciales de login.
     * @return array|null  Usuario si es válido y está activo, null si no.
     */
    public function login(string $username, string $clave): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuarios WHERE username = ? AND estado = '1' LIMIT 1"
        );
        if (!$stmt) return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        if ($usuario && password_verify($clave, $usuario['clave'])) {
            return $usuario;
        }
        return null;
    }

    /**
     * Crear un nuevo usuario (con rol)
     * @param array $datos
     * @return bool
     */
    public function crearUsuario(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO usuarios (nombre, username, clave, estado, rol) VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $nombre   = $datos['nombre']   ?? '';
        $username = $datos['username'] ?? '';
        $clave    = password_hash($datos['clave'] ?? '', PASSWORD_DEFAULT);
        $estado   = $datos['estado']   ?? '1';
        $rol      = $datos['rol']      ?? 'operativo';

        $stmt->bind_param("sssss", $nombre, $username, $clave, $estado, $rol);
        return $stmt->execute();
    }

    /**
     * Actualizar usuario (nombre, username, estado, rol — sin clave)
     */
    public function actualizarUsuario(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE usuarios SET nombre = ?, username = ?, estado = ?, rol = ? WHERE id = ?"
        );
        if (!$stmt) return false;

        $nombre   = $datos['nombre']   ?? '';
        $username = $datos['username'] ?? '';
        $estado   = $datos['estado']   ?? '1';
        $rol      = $datos['rol']      ?? 'operativo';

        $stmt->bind_param("ssssi", $nombre, $username, $estado, $rol, $id);
        return $stmt->execute();
    }

    /** Eliminar usuario */
    public function eliminarUsuario(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
