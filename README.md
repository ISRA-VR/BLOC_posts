# 📘 BLOC_posts — Blog MVC Nativo

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-000000?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Security](https://img.shields.io/badge/Security-CSRF%20%26%20PDO-green?style=for-the-badge)

> **BLOC_posts** es una implementación educativa de un sistema de blog utilizando arquitectura **MVC (Modelo-Vista-Controlador)** sin frameworks, enfocándose en la seguridad y la gestión de roles.

---

## 🚀 Características Principales

* 🏗️ **Arquitectura MVC:** Separación clara entre lógica, datos y vistas.
* 👥 **Roles de Usuario:**
    * **Autor:** Gestiona (crea, edita, borra) sus propios posts.
    * **Admin:** Control total (CRUD) sobre todos los posts de la plataforma.
* 🔒 **Seguridad Implementada:**
    * Protección **CSRF** en formularios.
    * **Prepared Statements** (PDO) anti SQL Injection.
    * Manejo seguro de Sesiones y Password Hashing (`bcrypt`).
* 🎨 **Frontend:** HTML5 + CSS3 con integración opcional de Bootstrap.

---

## 📂 Estructura del Proyecto

```text
/BLOC_posts
├── config/             # 🔌 Conexión a Base de Datos
├── controllers/        # 🧠 Lógica (Auth, Posts)
├── models/             # 💾 Modelos de Datos (User, Post)
├── views/              # 👀 Interfaz de Usuario
│   ├── auth/           # Login & Registro
│   ├── layouts/        # Estructura de html (footer, header...)
│   └── posts/          # CRUD de Publicaciones
└── index.php           # 🚦 Front Controller & Router
```

---

## 🧰 Requisitos

- PHP 7.4+ con extensiones: `pdo`, `pdo_mysql`.
- MySQL 5.7+ (o MariaDB compatible).
- Servidor web (Apache/Nginx). Recomendado en Windows: Laragon/WAMP.
- Permisos de escritura en `uploads/`.

---

## ⚙️ Instalación y Ejecución

1) Clona o copia el proyecto en tu servidor web (p. ej. Laragon):
     - Ruta típica en Windows: `C:\\laragon\\www\\BLOC_posts`

2) Base de datos:
     - Crea la BD e importa `config/database.sql`.
     - Configura credenciales en config/Database.php (host, db, usuario, contraseña).

3) Inicia Apache/MySQL y visita: `http://localhost/BLOC_posts/`

4) Crea un usuario administrador:
     - Promover usuario existente:
         ```sql
         UPDATE usuarios SET rol = 'admin', suspendido = 0 WHERE email = 'tu_correo@ejemplo.com';
         ```
     - O insertar uno nuevo (genera el hash en consola y pégalo):
         ```powershell
         php -r "echo password_hash('TuContraseñaSegura123!', PASSWORD_DEFAULT), PHP_EOL;"
         ```
         ```sql
         INSERT INTO usuarios (nombre, email, password, rol, suspendido)
         VALUES ('Administrador', 'admin@local.test', 'PEGAR_HASH', 'admin', 0);
         ```

---

## 🔐 Seguridad y Buenas Prácticas

- CSRF en formularios sensibles (token por sesión y validación en servidor).
- PDO con prepared statements (sin emulación) y manejo de errores por excepciones.
- `password_hash()`/`password_verify()` con política de contraseña fuerte:
    - Mínimo 8 caracteres, 1 mayúscula, 1 número y 1 símbolo.
- Sesión con `httponly`, `samesite=Lax` y regeneración en login.
- Subidas: extensiones permitidas `jpg, jpeg, png, gif, webp`, nombres únicos y reemplazo seguro al editar.
- Admin: no se puede suspender/eliminar admins ni auto-suspenderse; suspendidos no pueden iniciar sesión.

---

## 🔎 Búsqueda y Paginación (Admin)

- Parámetros: `q` (consulta), `page` (página), `per` (10/20/30/50).
- Evita `HY093`: placeholders distintos en cada columna (`:q1, :q2, ...`) y `LIMIT/OFFSET` como enteros validados.

---

## 🧩 Rutas (principales)

- Auth: `?controller=auth&action=login|register|logout`
- Posts: `?controller=posts&action=index`, `?controller=post&action=create|edit|delete`
- Admin: `?controller=admin&action=index|users|posts|setrole|suspend|deleteuser|deletepost`

---

Actualizado: 12/12/2025