//Función para eliminar la foto y mostrar la opción de insertar una foto nueva
function eliminarImagen() {
    // Ocultar la imagen y el botón de eliminar
    document.querySelector('.div_image img').style.display = 'none';
    document.querySelector('.button_eliminar').style.display = 'none';

    // Mostrar el input para cargar nueva imagen
    document.querySelector('input[name="nuevaImagen"]').style.display = 'block';
  }