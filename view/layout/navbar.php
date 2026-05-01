<?php
require_once __DIR__ . '/../../helpers/session.php';
$currentAction = $_GET['action'] ?? '';
$sesion        = getUsuarioSesion();
?>
<link rel="stylesheet" href="assets/css/style.css">
<nav class="main-nav">
    <span class="nav-brand">Hotelería</span>

    <?php if (tieneRol(['admin'])): ?>
        <a href="index.php?action=usuarios"
           class="nav-link <?php echo $currentAction === 'usuarios' ? 'active' : ''; ?>">Usuarios</a>
    <?php endif; ?>

    <a href="index.php?action=habitaciones"
       class="nav-link <?php echo $currentAction === 'habitaciones' ? 'active' : ''; ?>">Habitaciones</a>

    <a href="index.php?action=huespedes"
       class="nav-link <?php echo $currentAction === 'huespedes' ? 'active' : ''; ?>">Huéspedes</a>

    <a href="index.php?action=reservas"
       class="nav-link <?php echo $currentAction === 'reservas' ? 'active' : ''; ?>">Reservas</a>

    <a href="index.php?action=facturas"
       class="nav-link <?php echo $currentAction === 'facturas' ? 'active' : ''; ?>">Facturas</a>

    <div class="nav-right">
        <span class="nav-user"><?php echo htmlspecialchars($sesion['nombre']); ?> (<?php echo htmlspecialchars($sesion['rol']); ?>)</span>
        <a href="index.php?action=logout" class="nav-logout">Cerrar sesión</a>
    </div>
</nav>
