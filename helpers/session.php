<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si hay un usuario logueado en sesión.
 */
function isLoggedIn(): bool {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Redirige al login si no hay sesión activa.
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit;
    }
}

/**
 * Verifica que el usuario tenga uno de los roles permitidos.
 * Redirige a reservas con mensaje si no tiene acceso.
 */
function requireRole(array $roles): void {
    requireLogin();
    if (!in_array($_SESSION['usuario_rol'] ?? '', $roles, true)) {
        header('Location: index.php?action=reservas&msg=acceso_denegado');
        exit;
    }
}

/**
 * Retorna los datos del usuario en sesión.
 */
function getUsuarioSesion(): array {
    return [
        'id'       => $_SESSION['usuario_id']       ?? null,
        'nombre'   => $_SESSION['usuario_nombre']   ?? '',
        'username' => $_SESSION['usuario_username'] ?? '',
        'rol'      => $_SESSION['usuario_rol']      ?? '',
    ];
}

/**
 * Verifica si el usuario actual tiene uno de los roles dados
 * (sin redirigir — útil en vistas para mostrar/ocultar elementos).
 */
function tieneRol(array $roles): bool {
    return in_array($_SESSION['usuario_rol'] ?? '', $roles, true);
}
