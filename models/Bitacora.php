<?php
class BitacoraModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Registra una acción en la bitácora.
     * @param array $datos  [id_usuario, usuario_nombre, accion, modulo, descripcion]
     * @return bool
     */
    public function registrar(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO bitacora (id_usuario, usuario_nombre, accion, modulo, descripcion, ip)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $id_usuario     = $datos['id_usuario']     ?? null;
        $usuario_nombre = $datos['usuario_nombre'] ?? '';
        $accion         = $datos['accion']         ?? '';
        $modulo         = $datos['modulo']         ?? '';
        $descripcion    = $datos['descripcion']    ?? '';
        $ip             = $_SERVER['REMOTE_ADDR']  ?? '';

        $stmt->bind_param("isssss", $id_usuario, $usuario_nombre, $accion, $modulo, $descripcion, $ip);
        return $stmt->execute();
    }
}
