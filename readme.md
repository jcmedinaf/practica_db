# Práctica DB - Aplicación Web Modulada (PHP & AJAX)

Proyecto web modular para la gestión de registros (CRUD) con una arquitectura ligera y tradicional. Implementa un flujo de trabajo limpio que separa la interfaz de usuario de la lógica del servidor, utilizando PHP procedural (con mysqli), peticiones AJAX, JavaScript / jQuery y bases de datos MySQL.

---

## 📁 Estructura del Proyecto

```text
practica_db/
│
├── index.html          # Interfaz principal de la aplicación
├── esquema.md          # Documentación del diseño o arquitectura
├── readme.md           # Archivo de Documentación de la estructura del proyecto
├── database.sql        # Script para la creación de la base de datos y tablas
├── assets/
│   ├── js/
│   │   └── app.js      # Peticiones AJAX y manipulación del DOM
│   ├── css/            # Hojas de estilo personalizadas
│   └── img/            # Recursos gráficos e imágenes
│       └── index.png   # Imagen de la vista previa del index
└── php/
    ├── conexion.php    # Configuración de base de datos y conexión mysqli
    ├── listar.php      # Consulta y obtención de registros
    ├── agregar.php     # Inserción de nuevos datos
    ├── editar.php      # Actualización de registros existentes
    ├── eliminar.php    # Eliminación de registros
    └── seleccionar.php # Consulta individual por ID
```

![Gestión de usuarios](assets/img/index.png)


🛠️ Tecnologías Utilizadas
Frontend:

    * HTML5
    * CSS3
    * JavaScript (Vanilla JS / jQuery)
    * AJAX (Peticiones asíncronas)

Backend:

    * PHP (Programación Estructurada / Procedural)
    * MySQL (mysqli)

🚀 Configuración e Instalación
1. Base de Datos
Abre tu gestor de base de datos (MySQL Workbench, phpMyAdmin, DBeaver, etc.).

Importa y ejecuta el archivo database.sql incluido en la raíz del proyecto para crear la base de datos practica_db y sus respectivas tablas.

2. Configuración del Servidor
Asegúrate de configurar las credenciales de acceso a MySQL en el archivo php/conexion.php:

PHP
$host     = 'localhost';
$user     = 'root';
$password = ''; // Tu contraseña local
$dbname   = 'practica_db';

3. Ejecución en Servidor Local
Puedes ejecutar el servidor local integrado de PHP desde la terminal en la raíz del proyecto:

Bash
php -S localhost:8000
Accede desde tu navegador web a:
http://localhost:8000


⚙️ Flujo de Trabajo (Arquitectura)
Cliente (index.html + app.js): El usuario interactúa con la interfaz. Las acciones capturadas (como enviar un formulario o presionar un botón) lanzan peticiones asíncronas vía AJAX.

Servidor (php/): Los archivos PHP reciben los datos por $_POST o $_GET, ejecutan las consultas correspondientes usando mysqli (estilo procedural) y devuelven una respuesta en formato JSON.

Actualización: El archivo app.js procesa la respuesta en JSON y actualiza dinámicamente la vista sin necesidad de recargar la página.