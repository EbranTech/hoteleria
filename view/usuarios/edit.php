<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
</head>
<body>
    <h2>Crear Nuevo Usuario</h2>
    <form action="index.php?action=usuario_create" method="POST">
        <div style="margin-bottom: 10px;">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="clave">Clave (Contraseña):</label><br>
            <input type="password" id="clave" name="clave" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="estado">Estado:</label><br>
            <select id="estado" name="estado">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>

        <div style="margin-top: 15px;">
            <input type="submit" value="Guardar Usuario" style="padding: 8px 16px; background:#28a745; color:#fff; border:none; cursor:pointer;">
            <a href="index.php?action=usuarios" style="margin-left: 10px; text-decoration: none; color: #dc3545;">Cancelar</a>
        </div>
    </form>
</body>
</html>