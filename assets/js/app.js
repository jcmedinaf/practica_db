/**
 * =====================================================================
 * ARCHIVO: app.js
 * DESCRIPCIÓN: Controlador principal de la interfaz mediante jQuery y AJAX.
 * Gestiona el ciclo de vida del CRUD (Listar, Agregar, Editar, Eliminar)
 * conectándose de forma asíncrona con los scripts backend en PHP.
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */

// Espera a que todo el documento HTML esté completamente cargado antes de ejecutar jQuery
$(document).ready(function() {

    // Oculta el contenedor de alertas al iniciar la página
    $('#result').hide();

    // Bandera de control: indica si el formulario está en modo inserción (false) o edición (true)
    let editar = false;

    // Ejecuta la función listar inmediatamente al cargar la página para mostrar los registros existentes
    listar();

    /**
     * =================================================================
     * FUNCIÓN: listar()
     * Realiza una petición AJAX GET para obtener todos los registros 
     * de la base de datos y renderizarlos dinámicamente en la tabla HTML.
     * =================================================================
     */

    function listar2(){
        $.ajax({

        });
    }

    function listar() {
        $.ajax({
            url: 'php/listar.php', // Script backend que retorna todos los usuarios en formato JSON
            type: 'GET',
            
            // Evento que se ejecuta antes de enviar la petición (muestra un mensaje de carga)
            beforeSend: function() {
                $('#result').show();
                let template = '<div class="alert alert-dark alert-dismissible fade show" role="alert">';
                template += 'Espere, cargando los datos...';
                template += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                template += '<span aria-hidden="true">&times;</span>';
                template += '</button>';
                template += '</div>';
                $('#result').html(template);
            },
            
            // Evento que se ejecuta cuando el servidor responde exitosamente
            success: function(response) {
                // Validación de seguridad: Asegura que la respuesta sea un arreglo analizando si es string
                let users = typeof response === 'string' ? JSON.parse(response) : response;
                let template = '';
                
                // Recorre cada usuario recibido y construye las filas de la tabla HTML
                if (Array.isArray(users)) {
                    users.users = users; // Referencia interna segura   
                    users.forEach(user => { // apostrofe inverso para realizar interpolaciones combinacion de teclas alt+096 ``
                        template += `   
                            <tr id="${user.id}">
                                <td>${user.id}</td>
                                <td>${user.nombre}</td>
                                <td>${user.apellido}</td>
                                <td>${user.correo}</td>
                                <td class="text-center">
                                    <button class="edit btn btn-warning id btn-sm">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button class="delete btn btn-danger btn-sm">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                // Inyecta las filas generadas dentro del cuerpo de la tabla (tbody id="listado")
                $('#listado').html(template);

                // Muestra una alerta verde indicando que los datos cargaron con éxito
                let alertTemplate = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                alertTemplate += 'Datos cargados correctamente';
                alertTemplate += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                alertTemplate += '<span aria-hidden="true">&times;</span>';
                alertTemplate += '</button>';
                alertTemplate += '</div>';

                $('#result').show().html(alertTemplate);
                
                // Restablece la bandera de edición a falso por seguridad
                editar = false;
            },
            
            // Evento que se ejecuta si ocurre un error de comunicación con el servidor
            error: function() {
                $('#result').show();
                let alertTemplate = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                alertTemplate += 'Error con los datos o la conexión';
                alertTemplate += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                alertTemplate += '<span aria-hidden="true">&times;</span>';
                alertTemplate += '</button>';
                alertTemplate += '</div>';
                $('#result').html(alertTemplate);
            }
        });
    }

    /**
     * =================================================================
     * EVENTO: Eliminar Registro (.delete)
     * Detecta clics en los botones de eliminar, solicita confirmación
     * al usuario y envía una petición POST al servidor para borrar el registro.
     * =================================================================
     */
    $(document).on('click', '.delete', function() {
        if(confirm("¿Estás seguro de eliminar el registro?")) {
            // Captura el ID del usuario buscando el atributo id en la fila <tr> padre
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('id');
            
            let url = "php/eliminar.php";
            
            // Petición POST enviando el ID a eliminar
            $.post(url, {id}, function(response) {
                listar(); // Refresca la tabla automáticamente

                // Muestra alerta roja de eliminación exitosa
                $('#result').show();
                let template = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                template += 'Registro eliminado correctamente';
                template += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                template += '<span aria-hidden="true">&times;</span>';
                template += '</button>';
                template += '</div>';
                $('#result').html(template);
            });
        }
    });

    /**
     * =================================================================
     * EVENTO: Seleccionar para Editar (.id / .edit)
     * Obtiene los datos del usuario seleccionado desde la base de datos
     * y los carga en los inputs del formulario lateral para su modificación.
     * =================================================================
     */
    $(document).on('click', '.id', function() {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('id');$.get('php/seleccionar.php', {id}, function(response) {
            let user = typeof response === 'string' ? JSON.parse(response) : response;
            
            // Rellena los campos del formulario con los valores actuales del usuario
            $('#nombre').val(user.nombre);
            $('#apellido').val(user.apellido);
            $('#correo').val(user.correo);
            $('#clave').val(''); // Limpia el campo de contraseña por seguridad
            $('#id').val(user.id); // Almacena el ID oculto para la actualización
            
            // Cambia el estado de la bandera a modo edición
            editar = true;
        });
    });

    /**
     * =================================================================
     * EVENTO: Enviar Formulario (#form submit)
     * Controla el comportamiento al presionar el botón Guardar.
     * Evalúa si debe registrar un usuario nuevo (agregar.php)
     * o actualizar uno existente (editar.php) basándose en la variable 'editar'.
     * =================================================================
     */
    $('#form').submit(function(e) {
        e.preventDefault(); // Evita que la página se recargue de forma predeterminada al enviar el formulario

        if(editar == false) {
            // --- MODO INSERCIÓN (Crear nuevo usuario) ---
            const postData = {
                nombre: $('#nombre').val(),
                apellido: $('#apellido').val(),
                correo: $('#correo').val(),
                clave: $('#clave').val()
            };
            
            let url = 'php/agregar.php';

            $.post(url, postData, function(response) {
                $('#form').trigger('reset'); // Limpia los campos del formulario
                listar(); // Recarga la tabla

                // Muestra alerta verde de inserción exitosa
                $('#result').show();
                let template = '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                template += 'Usuario agregado exitosamente';
                template += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                template += '<span aria-hidden="true">&times;</span>';
                template += '</button>';
                template += '</div>';
                $('#result').html(template);
            });

        } else {
            // --- MODO ACTUALIZACIÓN (Editar usuario existente) ---
            const postData1 = {
                id: $('#id').val(),
                nombre: $('#nombre').val(),
                apellido: $('#apellido').val(),
                correo: $('#correo').val(),
                clave: $('#clave').val()
            };
            
            let url = 'php/editar.php';

            $.post(url, postData1, function(response) {
                $('#form').trigger('reset'); // Limpia el formulario
                editar = false; // Restablece la bandera al modo normal
                listar(); // Recarga la tabla

                // Muestra alerta amarilla de actualización exitosa
                $('#result').show();
                let template = '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                template += 'Usuario actualizado correctamente';
                template += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                template += '<span aria-hidden="true">&times;</span>';
                template += '</button>';
                template += '</div>';
                $('#result').html(template);
            });
        }
    });

});