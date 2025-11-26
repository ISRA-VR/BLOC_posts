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
│   └── posts/          # CRUD de Publicaciones
├── assets/             # 🎨 CSS y Recursos estáticos
├── index.php           # 🚦 Front Controller & Router
└── setup_admin.php     # 🛠️ Script de instalación Admin