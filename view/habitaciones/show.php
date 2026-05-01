<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Habitación — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Editar Habitación</h2>
    <div class="form-card">
        <form action="index.php?action=habitacion_update" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($habitacion['id_habitacion']); ?>">

            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero"
                       value="<?php echo htmlspecialchars($habitacion['numero']); ?>" required maxlength="10">
            </div>
            <div class="form-group">
                <label for="piso">Piso</label>
                <input type="number" id="piso" name="piso"
                       value="<?php echo htmlspecialchars($habitacion['piso']); ?>" min="1" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <?php foreach (['Simple','Doble','Suite'] as $t): ?>
                        <option value="<?php echo $t; ?>" <?php echo $habitacion['tipo'] === $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="capacidad">Capacidad (personas)</label>
                <input type="number" id="capacidad" name="capacidad"
                       value="<?php echo htmlspecialchars($habitacion['capacidad']); ?>" min="1" required>
            </div>
            <div class="form-group">
                <label for="precio_noche">Precio por Noche (Q)</label>
                <input type="number" id="precio_noche" name="precio_noche"
                       value="<?php echo htmlspecialchars($habitacion['precio_noche']); ?>" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($habitacion['descripcion'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="D" <?php echo $habitacion['estado'] === 'D' ? 'selected' : ''; ?>>Disponible</option>
                    <option value="O" <?php echo $habitacion['estado'] === 'O' ? 'selected' : ''; ?>>Ocupada</option>
                    <option value="M" <?php echo $habitacion['estado'] === 'M' ? 'selected' : ''; ?>>Mantenimiento</option>
                </select>
            </div>
            <div class="form-actions">
                <input type="submit" value="Actualizar" class="btn btn-primary">
                <a href="index.php?action=habitaciones" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
