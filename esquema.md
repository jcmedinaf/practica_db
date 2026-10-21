```text

/**
 * =====================================================================
 * ARCHIVO: esquema.md
 * DESCRIPCIÓN: Documentación de la estructura de directorios y arquitectura
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */
 
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
│   │   └── app.css     # Estilos personalizados de la aplicación
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