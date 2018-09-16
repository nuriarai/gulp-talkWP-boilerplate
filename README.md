# gulp-talkWP-boilerplate
Gulp 3.9.1 boilerplate para un starter theme de WP basado en underscores

Para empezar:

0) Instalar WP y copiar este tema en la carpeta themes

1) Des de el administrador de WP escoge nuestro tema.

2) Ves a gulpfile.js y modifica la línea 50 con la url del proxy local que tengas en tu máquina:

 proxy: "local.tusitio.com"

3) Ves a functions.php y modifica de la línea 89 a 91 con la denominación y extensión de tu dominio en local, test y producción:

 $
    /* environement */
    // Set your environment/url pairs
    $environments = array(
        'local' => 'local.tudominio.tuextension',
        'staging' => 'staging.tudominio.tuextension',
        'production' => 'tudominio.tuextension'
    );
 
4) Abre el terminal y situáte en la carpeta del tema y ejecuta:
 
  npm install
  
  para que instale todos los módulos.
  
5) aségurate que tienes gulp instalado correctamente con 
 
 gulp -v
 
 Tiene que aparecerte algo así:
 
 [13:25:02] CLI version 2.0.1
 [13:25:02] Local version 3.9.1

6) Para empezar a trabajar ejecuta:

  gulp watch
  
  Debe abrirse el navegador con la página de inicio básica.
