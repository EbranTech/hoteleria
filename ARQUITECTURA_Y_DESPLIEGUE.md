# BiblioSys: Arquitectura, Logica y Despliegue

Este documento resume dos cosas:

1. Como esta programado el sistema y como se comunican sus capas.
2. Como se desplego en Ubuntu Server, que se configuro en Apache y por que el proyecto no vive en `/var/www/html`.

## 1. Estructura general del proyecto

La carpeta raiz de trabajo es:

`C:\xampp\htdocs\launchbibliosys`

Sus carpetas principales son:

- `backend/`
- `frontend/`
- `database/`
- `docs/`

La idea del sistema es separar responsabilidades:

- `frontend/` renderiza HTML, maneja sesion y navegacion.
- `backend/` expone la API en JSON.
- `database/` contiene esquema, semillas y cargas de prueba.
- `docs/` guarda documentacion tecnica y de despliegue.

## 2. Que hace cada carpeta

### `backend/`

Es la API del sistema. No genera vistas HTML; responde JSON.

Subcarpetas:

- `app/Controllers`
  Expone endpoints de la API. Recibe la solicitud, valida entradas y llama a servicios.
- `app/Services`
  Contiene la logica de negocio. Aqui se decide, por ejemplo, si un libro puede prestarse o si una devolucion es valida.
- `app/Repositories`
  Ejecuta SQL y accede a MariaDB mediante PDO.
- `app/Validators`
  Valida que el body tenga los campos minimos requeridos.
- `app/Middlewares`
  Aplica reglas transversales: CORS, salida JSON, token y rol.
- `core/`
  Infraestructura base: `Router`, `Request`, `Response`, `Database`.
- `routes/api.php`
  Declara todos los endpoints `/api/...`.
- `public/`
  Punto de entrada publico del backend. Apache debe apuntar aqui.
- `config/`
  Carga variables `.env` y construye la configuracion de base de datos.

### `frontend/`

Es la aplicacion web que usa el usuario final. No consulta la base directamente; consume la API del backend.

Subcarpetas:

- `controllers/`
  Controladores de la interfaz. Orquestan formularios, sesion y consumo del backend.
- `models/`
  Modelos cliente que usan `ApiClient` para llamar a la API.
- `views/`
  Plantillas PHP/HTML de login, prestamos, devoluciones, usuarios, autores, libros, estudiantes, consulta e historial.
- `core/`
  Utilidades del frontend:
  - `Router.php`: enruta por `action`
  - `ApiClient.php`: hace peticiones HTTP al backend
  - `ResponseView.php`: renderiza header, contenido y footer
- `public/`
  Punto de entrada publico del frontend. Apache debe apuntar aqui.
- `assets/`
  Recursos visuales.
- `config/`
  Lectura de variables `.env`.

### `database/`

- `schema.sql`
  Crea tablas, llaves, indices y vistas.
- `seed.sql`
  Carga datos base como roles y usuarios iniciales.
- `demo_load.sql`
  Carga rapida de datos de prueba.
- `hash_generator.php`
  Utilidad para generar hashes de contrasenas.

### `docs/`

Documentacion de red, despliegue y pruebas.

## 3. Puntos de entrada reales

El sistema tiene dos entradas publicas distintas:

- Backend: `backend/public/index.php`
- Frontend: `frontend/public/index.php`

Esto significa que no existe un `router.php` unico en la raiz. Hay dos aplicaciones separadas:

- una web
- una API

## 4. Logica del frontend

Archivo principal:

- `frontend/public/index.php`

Flujo:

1. Arranca sesion con `session_start()`.
2. Carga variables desde `frontend/.env`.
3. Registra un autoloader para `Core`, `Controllers` y `Models`.
4. Si `action=logout`, destruye sesion y redirige.
5. Si `action=login`, manda `username` y `password` al backend usando `ApiClient`.
6. Si el login sale bien, guarda en sesion:
   - `user_nombre`
   - `user_username`
   - `user_rol`
7. Si no hay sesion, redirige a `index.php?action=login`.
8. Si hay sesion, resuelve la accion actual y ejecuta el controlador correspondiente.

En frontend no se usan URLs amigables del tipo `/prestamos`; el enrutamiento se hace por query string:

- `index.php?action=prestamos`
- `index.php?action=devoluciones`
- `index.php?action=usuarios`

Por eso el frontend no necesita `.htaccess` para redirigir rutas. Su entrada es siempre `index.php`.

## 5. Logica del backend

Archivo principal:

- `backend/public/index.php`

Flujo:

1. Define `BASE_PATH`.
2. Carga variables desde `backend/.env`.
3. Registra autoloader de `Core` y `App`.
4. Construye un objeto `Request`.
5. Ejecuta `CorsMiddleware` globalmente.
6. Crea el `Router`.
7. Carga las rutas de `backend/routes/api.php`.
8. Hace `dispatch()` de la solicitud.

El backend si usa `.htaccess` en `backend/public/.htaccess` para que cualquier solicitud termine entrando a `backend/public/index.php`.

## 6. Como se comunican frontend y backend

La comunicacion la hace:

- `frontend/core/ApiClient.php`

Este cliente:

- lee `API_BASE_URL` desde `frontend/.env`
- manda `Authorization: Bearer <API_TOKEN>`
- manda `Content-Type: application/json`
- manda `Accept: application/json`
- manda `X-User-Username` con el usuario guardado en sesion

Entonces la cadena completa es:

1. El usuario interactua con el navegador.
2. El navegador abre una ruta del frontend.
3. El controlador del frontend llama a `ApiClient`.
4. `ApiClient` consume `http://IP_BACKEND/api/...`
5. El backend valida token, cuerpo y rol.
6. El backend consulta MariaDB.
7. El backend responde JSON.
8. El frontend interpreta la respuesta y renderiza HTML.

## 7. Autenticacion y autorizacion

### Login

La ruta publica es:

- `POST /api/auth/login`

Implementacion principal:

- `backend/app/Controllers/AuthController.php`
- `backend/app/Services/AuthService.php`

La autenticacion:

1. busca el usuario por `username`
2. verifica si esta activo
3. valida `password_verify(...)`
4. devuelve datos minimos de sesion:
   - `id_usuario`
   - `nombre`
   - `username`
   - `rol`

### Token de API

Las rutas protegidas no usan JWT ni sesiones del backend. Usan un token fijo:

- `API_TOKEN`

Lo valida:

- `backend/app/Middlewares/AuthMiddleware.php`

Si el header `Authorization` no existe o no coincide con el token esperado, responde `401`.

### Control por rol

Lo aplica:

- `backend/app/Middlewares/RoleMiddleware.php`

Para saber el rol:

1. el frontend envia `X-User-Username`
2. el middleware busca el usuario en base
3. consulta el nombre del rol
4. compara con la lista permitida

Ejemplo:

- `/api/usuarios` solo permite `ADMIN`

## 8. Logica de negocio principal

### Prestamos

Flujo backend:

- `PrestamoController` recibe el request
- `PrestamoValidator` valida campos minimos
- `PrestamoService` ejecuta reglas de negocio
- `PrestamoRepository` inserta el prestamo

Reglas de negocio importantes:

- el estudiante debe existir
- el estudiante debe estar `ACTIVO`
- el libro debe existir
- el libro no puede estar en `MANTENIMIENTO`
- el libro debe tener `cantidad_disponible >= 1`
- la fecha esperada no puede ser menor a la fecha del prestamo

Despues de registrar un prestamo:

- se crea un registro en `prestamos`
- se reduce `cantidad_disponible` del libro
- si el libro queda en `0`, su `estado` pasa a `PRESTADO`

### Devoluciones

Flujo backend:

- se valida `id_prestamo` y `fecha_devolucion`
- se verifica que el prestamo exista
- se verifica que siga `ACTIVO`
- se actualiza a `DEVUELTO`
- se guarda `fecha_devolucion_real`
- se incrementa `cantidad_disponible` del libro
- si vuelve a haber existencias, el libro regresa a `DISPONIBLE`

### Devoluciones en frontend

En `frontend/controllers/PrestamoController.php`:

- la pantalla `devoluciones` obtiene todos los prestamos
- filtra localmente por:
  - carnet
  - nombre del estudiante
  - titulo del libro
- solo muestra prestamos en estado `ACTIVO`

## 9. Modelo de datos

Tablas base:

- `roles`
- `usuarios`
- `autores`
- `estudiantes`
- `libros`
- `prestamos`

Relaciones:

- `usuarios.id_rol -> roles.id_rol`
- `libros.id_autor -> autores.id_autor`
- `prestamos.id_estudiante -> estudiantes.id_estudiante`
- `prestamos.id_libro -> libros.id_libro`

Vistas importantes:

- `vw_usuarios_acceso`
- `vw_libros_disponibles`
- `vw_prestamos_detalle`
- `vw_historial_prestamos`

Estas vistas ayudan a reportes y listados ya enriquecidos.

## 10. Variables de entorno clave

### Frontend

Archivo:

- `frontend/.env`

Variables clave:

- `APP_URL`
- `API_BASE_URL`
- `API_TOKEN`

### Backend

Archivo:

- `backend/.env`

Variables clave:

- `APP_URL`
- `API_TOKEN`
- `CORS_ALLOWED_ORIGINS`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_CHARSET`

## 11. Despliegue real en Ubuntu Server

Se desplego en tres VMs separadas:

- Base de datos:
  - `100.114.164.6`
- Primer despliegue:
  - Frontend `100.96.143.121`
  - Backend `100.118.139.101`
- Segundo despliegue:
  - Frontend `100.107.58.29`
  - Backend `100.97.148.8`

Todas usan la misma base `bibliosys`.

## 12. Donde quedo el proyecto en Ubuntu

No se dejo en `/var/www/html` como proyecto principal.

Se dejo en:

- Frontend: `/var/www/launchbibliosys/frontend`
- Backend: `/var/www/launchbibliosys/backend`

Y Apache apunta solo a las carpetas publicas:

- Frontend: `/var/www/launchbibliosys/frontend/public`
- Backend: `/var/www/launchbibliosys/backend/public`

## 13. Por que en `/var/www/html` solo aparece `index.html`

Porque `/var/www/html` es el contenido por defecto de Apache.

Eso no significa que el sistema este sirviendose desde ahi.

Lo que se hizo fue:

1. dejar el proyecto real en `/var/www/launchbibliosys/...`
2. crear un sitio personalizado en Apache
3. cambiar el `DocumentRoot` del sitio personalizado hacia `.../public`
4. deshabilitar `000-default` en las VMs donde quedo el sitio nuevo

Entonces:

- `/var/www/html/index.html` puede seguir existiendo
- pero el trafico real entra al VirtualHost nuevo

## 14. Que se configuro en Apache

Para cada servidor web se creo un VirtualHost.

### Backend

Archivo de sitio:

- `/etc/apache2/sites-available/launchbibliosys-backend.conf`

Estructura usada:

```apache
<VirtualHost *:80>
    ServerName IP_BACKEND
    DocumentRoot /var/www/launchbibliosys/backend/public

    <Directory /var/www/launchbibliosys/backend/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/launchbibliosys-backend-error.log
    CustomLog ${APACHE_LOG_DIR}/launchbibliosys-backend-access.log combined
</VirtualHost>
```

### Frontend

Archivo de sitio:

- `/etc/apache2/sites-available/launchbibliosys-frontend.conf`

Estructura usada:

```apache
<VirtualHost *:80>
    ServerName IP_FRONTEND
    DocumentRoot /var/www/launchbibliosys/frontend/public

    <Directory /var/www/launchbibliosys/frontend/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/launchbibliosys-frontend-error.log
    CustomLog ${APACHE_LOG_DIR}/launchbibliosys-frontend-access.log combined
</VirtualHost>
```

Comandos usados:

```bash
sudo a2enmod rewrite
sudo a2dissite 000-default
sudo a2ensite launchbibliosys-frontend.conf
sudo a2ensite launchbibliosys-backend.conf
sudo systemctl restart apache2
sudo ufw allow 80/tcp
```

## 15. Que se configuro en MariaDB

En la VM de base:

1. Se uso la base `bibliosys`.
2. Se cargaron:
   - `database/schema.sql`
   - `database/seed.sql`
3. Se dejo MariaDB escuchando en `3306`.
4. Se habilito conectividad remota.
5. Se abrio firewall:

```bash
sudo ufw allow 3306/tcp
```

Archivo relevante de MariaDB:

- `/etc/mysql/mariadb.conf.d/50-server.cnf`

Cambio importante:

```ini
bind-address = 0.0.0.0
```

## 16. Paso a paso del despliegue

### A. Preparacion local

1. Se trabajo desde:
   - `C:\xampp\htdocs\launchbibliosys`
2. Se ajustaron:
   - `frontend/.env`
   - `backend/.env`
3. Se prepararon paquetes del proyecto para subirlos a las VMs.

### B. VM de base de datos

1. Verificar MariaDB instalada.
2. Permitir conexiones remotas.
3. Reiniciar MariaDB.
4. Crear o reutilizar `bibliosys`.
5. Importar `schema.sql`.
6. Importar `seed.sql`.
7. Abrir `3306/tcp`.
8. Verificar con:

```bash
mysql -u admon -p12345 -D bibliosys -e "SELECT COUNT(*) FROM usuarios;"
```

### C. VM de backend

1. Instalar:

```bash
sudo apt update
sudo apt install apache2 php php-mysql php-curl -y
```

2. Subir carpeta `backend/`.
3. Colocarla en:

```text
/var/www/launchbibliosys/backend
```

4. Escribir `.env` del backend con:

```ini
APP_ENV=production
APP_URL=http://IP_BACKEND
API_TOKEN=123456
CORS_ALLOWED_ORIGINS=http://IP_FRONTEND
DB_HOST=100.114.164.6
DB_PORT=3306
DB_DATABASE=bibliosys
DB_USERNAME=admon
DB_PASSWORD=12345
DB_CHARSET=utf8mb4
```

5. Crear VirtualHost.
6. Habilitar `mod_rewrite`.
7. Habilitar sitio.
8. Reiniciar Apache.
9. Probar:

```bash
curl -X POST http://IP_BACKEND/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"kgarcia","password":"biblioteca"}'
```

### D. VM de frontend

1. Instalar:

```bash
sudo apt update
sudo apt install apache2 php php-curl -y
```

2. Subir carpeta `frontend/`.
3. Colocarla en:

```text
/var/www/launchbibliosys/frontend
```

4. Escribir `.env` del frontend con:

```ini
APP_ENV=production
APP_URL=http://IP_FRONTEND
API_BASE_URL=http://IP_BACKEND/api
API_TOKEN=123456
```

5. Crear VirtualHost del frontend.
6. Habilitar sitio.
7. Reiniciar Apache.
8. Probar login en navegador.

## 17. Ajustes tecnicos importantes hechos durante la puesta en produccion

### Backend en Linux

Se corrigio el autoloader de:

- `backend/public/index.php`

Motivo:

En Windows el separador `\` no generaba problema, pero en Linux habia que convertir namespaces a rutas con `/`.

## 18. Resumen de URLs finales

### Primer despliegue

- Frontend: `http://100.96.143.121`
- Backend: `http://100.118.139.101/api`

### Segundo despliegue

- Frontend: `http://100.107.58.29`
- Backend: `http://100.97.148.8/api`

### Base de datos compartida

- Host: `100.114.164.6`
- Base: `bibliosys`

## 19. Conclusiones practicas

- El sistema no es un solo PHP plano; son dos aplicaciones separadas.
- El frontend no toca MariaDB.
- El backend es el unico que accede a base de datos.
- Apache no necesita que el proyecto viva en `/var/www/html`.
- Lo correcto es publicar solo la carpeta `public` de cada capa.
- Que exista `/var/www/html/index.html` no contradice el despliegue; solo significa que el contenido por defecto de Apache sigue ahi, aunque el VirtualHost activo apunte a otro lado.
