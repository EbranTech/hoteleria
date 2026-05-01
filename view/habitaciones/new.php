<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Habitación — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Nueva Habitación</h2>
    <div class="form-card">
        <form action="index.php?action=habitacion_create" method="POST">
            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero" required maxlength="10">
            </div>
            <div class="form-group">
                <label for="piso">Piso</label>
                <input type="number" id="piso" name="piso" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <option value="Simple">Simple</option>
                    <option value="Doble">Doble</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>
            <div class="form-group">
                <label for="capacidad">Capacidad (personas)</label>
                <input type="number" id="capacidad" name="capacidad" value="2" min="1" required>
            </div>
            <div class="form-group">
                <label for="precio_noche">Precio por Noche (Q)</label>
                <input type="number" id="precio_noche" name="precio_noche" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="D">Disponible</option>
                    <option value="O">Ocupada</option>
                    <option value="M">Mantenimiento</option>
                </select>
            </div>
            <div class="form-actions">
                <input type="submit" value="Guardar" class="btn btn-success">
                <a href="index.php?action=habitaciones" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
