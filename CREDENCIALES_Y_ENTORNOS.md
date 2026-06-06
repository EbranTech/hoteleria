# Credenciales y Entornos de BiblioSys

Este documento concentra las credenciales, URLs y accesos conocidos de los dos despliegues de BiblioSys y de la base de datos compartida.

## 1. Proyecto fuente

Ruta del proyecto de despliegue:

- `C:\xampp\htdocs\launchbibliosys`

## 2. Base de datos compartida

Servidor:

- Host DB: `100.114.164.6`
- Puerto: `3306`
- Base de datos: `bibliosys`

Credenciales MariaDB:

- Usuario DB: `admon`
- Password DB: `12345`

Acceso SSH a la VM DB:

- IP: `100.114.164.6`
- Usuario SSH: `emabd`
- Password SSH: `12345`

## 3. Credenciales de la aplicacion

Estas credenciales funcionan sobre ambos despliegues porque ambos usan la misma base `bibliosys`.

Usuarios de aplicacion:

- `admin / admin123`
- `biblio / biblio123`
- `ebran / admin`
- `kgarcia / biblioteca`

Token de API:

- `123456`

## 4. Primer despliegue

### Frontend

- URL Frontend: `http://100.96.143.121`

Acceso SSH a la VM Frontend:

- IP: `100.96.143.121`
- Usuario SSH: `emafront`
- Password SSH: `12345`

### Backend

- URL Backend API: `http://100.118.139.101/api`

Acceso SSH a la VM Backend:

- IP: `100.118.139.101`
- Usuario SSH: `emaback`
- Password SSH: `12345`

### Configuracion logica del primer despliegue

- Frontend usa `API_BASE_URL=http://100.118.139.101/api`
- Backend usa `DB_HOST=100.114.164.6`
- Backend usa `CORS_ALLOWED_ORIGINS=http://100.96.143.121`

## 5. Segundo despliegue

### Frontend

- URL Frontend: `http://100.107.58.29`

Acceso SSH a la VM Frontend:

- IP: `100.107.58.29`
- Usuario SSH: `karla`
- Password SSH: `karla`

### Backend

- URL Backend API: `http://100.97.148.8/api`

Acceso SSH a la VM Backend:

- IP: `100.97.148.8`
- Usuario SSH: `hector`
- Password SSH: `admon`

### Configuracion logica del segundo despliegue

- Frontend usa `API_BASE_URL=http://100.97.148.8/api`
- Backend usa `DB_HOST=100.114.164.6`
- Backend usa `CORS_ALLOWED_ORIGINS=http://100.107.58.29`

## 6. Headers para consumir la API

Rutas protegidas:

- `Authorization: Bearer 123456`
- `Content-Type: application/json`

Para operaciones de `usuarios`:

- `X-User-Username: admin`
  o
- `X-User-Username: ebran`

## 7. Endpoints base

Primer backend:

- `http://100.118.139.101/api`

Segundo backend:

- `http://100.97.148.8/api`

Login:

- `POST /auth/login`

Prestamos:

- `GET /prestamos`
- `POST /prestamos`
- `POST /prestamos/return`

Libros:

- `GET /libros`
- `GET /libros/{id}`
- `GET /libros/lookup?isbn=...`

Estudiantes:

- `GET /estudiantes`
- `GET /estudiantes/{id}`
- `GET /estudiantes/lookup?carnet=...`

Autores:

- `GET /autores`
- `GET /autores/{id}`

Usuarios:

- `GET /usuarios`
- `GET /usuarios/{id}`

## 8. Nota operativa importante

Los dos despliegues comparten la misma base de datos:

- `bibliosys` en `100.114.164.6`

Eso significa que:

- si creas un prestamo en un entorno, aparecera en el otro
- si haces una devolucion en un entorno, impacta el otro
- las credenciales de aplicacion son las mismas en ambos

## 9. Resumen rapido

### Aplicacion

- `admin / admin123`
- `biblio / biblio123`
- `ebran / admin`
- `kgarcia / biblioteca`

### API

- Bearer token: `123456`

### DB

- Host: `100.114.164.6`
- DB: `bibliosys`
- Usuario: `admon`
- Password: `12345`

### SSH VMs

- DB VM: `emabd / 12345`
- Frontend 1 VM: `emafront / 12345`
- Backend 1 VM: `emaback / 12345`
- Frontend 2 VM: `karla / karla`
- Backend 2 VM: `hector / admon`
