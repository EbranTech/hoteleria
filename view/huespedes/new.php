<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Huésped</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Nuevo Huésped</h2>
    <form action="index.php?action=huesped_create" method="POST">

        <div style="margin-bottom: 10px;">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required maxlength="100">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="apellido">Apellido:</label><br>
            <input type="text" id="apellido" name="apellido" required maxlength="100">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="dpi">DPI:</label><br>
            <input type="text" id="dpi" name="dpi" required maxlength="20">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="telefono">Teléfono:</label><br>
            <input type="text" id="telefono" name="telefono" maxlength="20">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" maxlength="100">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="nacionalidad">Nacionalidad:</label><br>
            <input type="text" id="nacionalidad" name="nacionalidad" value="Guatemalteca" required maxlength="80">
        </div>

        <div style="margin-bottom: 10px;">
            <label for="estado">Estado:</label><br>
            <select id="estado" name="estado">
                <option value="A">Activo</option>
                <option value="I">Inactivo</option>
            </select>
        </div>

        <div class="form-actions">
            <input type="submit" value="Guardar" class="btn btn-success">
            <a href="index.php?action=huespedes" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
