<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Nueva Reserva</h2>
    <form action="index.php?action=reserva_create" method="POST">

        <div style="margin-bottom: 10px;">
            <label for="id_huesped">Huésped:</label><br>
            <select id="id_huesped" name="id_huesped" required>
                <option value="">-- Seleccione un huésped --</option>
                <?php foreach ($huespedes as $h): ?>
                    <option value="<?php echo htmlspecialchars($h['id_huesped']); ?>">
                        <?php echo htmlspecialchars($h['nombre_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="id_habitacion">Habitación:</label><br>
            <select id="id_habitacion" name="id_habitacion" required>
                <option value="">-- Seleccione una habitación --</option>
                <?php foreach ($habitaciones as $hab): ?>
                    <option value="<?php echo htmlspecialchars($hab['id_habitacion']); ?>">
                        <?php echo htmlspecialchars($hab['descripcion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="fecha_entrada">Fecha de Entrada:</label><br>
            <input type="date" id="fecha_entrada" name="fecha_entrada" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="fecha_salida">Fecha de Salida:</label><br>
            <input type="date" id="fecha_salida" name="fecha_salida" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="precio_acordado">Precio Acordado (Q):</label><br>
            <input type="number" id="precio_acordado" name="precio_acordado" step="0.01" min="0" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="estado">Estado:</label><br>
            <select id="estado" name="estado">
                <option value="P">Pendiente</option>
                <option value="C">Confirmada</option>
                <option value="X">Cancelada</option>
                <option value="T">Completada</option>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="observaciones">Observaciones:</label><br>
            <textarea id="observaciones" name="observaciones" rows="3" cols="40"></textarea>
        </div>

        <div class="form-actions">
            <input type="submit" value="Guardar" class="btn btn-success">
            <a href="index.php?action=reservas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
