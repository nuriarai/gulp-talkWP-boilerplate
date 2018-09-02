// variables
var gulp = require('gulp');

// plugins CSS
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifycss = require('gulp-uglifycss'); // Minifica archivos CSS
var minmediaqueries = require('gulp-merge-media-queries'); // Combine matching media queries into one media query definition.

// plugins JS
var concat = require('gulp-concat'); // Concatena archivos JS
var uglify = require('gulp-uglify'); // Minifica archivos JS

// plugins imágenes
var imagemin = require('gulp-imagemin'); // Minimiza imágenes PNG, JPEG, GIF y SVG

// plugins utilidades
var rename = require('gulp-rename'); // Renames files E.g. style.css -> style.min.css
var browserSync = require('browser-sync').create();
var sourcemaps = require('gulp-sourcemaps');
var notify = require('gulp-notify'); // Sends message notification to you
var del = require('del');

//variables directorios
var dirs = {
    devStyles: {
        src: 'resources/assets/sass',
        dist: 'resources/assets/css'
    },
    devAssets: {
        src: 'resources/assets',
        dist: 'resources/assets'
    },
    prod: {
        dist: 'dist/assets'
    }
};

/* Tareas para entorno de desarrollo:

- live reload y sincronización de dispositivos con browserSync
- compilación de estilos
- watch de cambios para el reload

*/

// inicializar browsersync
gulp.task('browserSync', function () {
    browserSync.init({
        proxy: "local.xerradavcbcn2018.com",
        open: true,
        injectChanges: true,
        files: [
            'resources/**/*.php',
            dirs.devStyles.src + '/**/*.scss',
            dirs.devAssets.src + '/js/**/*.js'],
        notify: true
    });
});

// compilar sass
gulp.task('dev:styles', function () {
    return gulp.src(dirs.devStyles.src + '/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'nested',
            precision: 10,
            includePaths: ['.']
        }))
        .on('error', console.error.bind(console))
        .pipe(sourcemaps.write({ includeContent: false }))
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(autoprefixer({
            browsers: [
                'last 2 versions',
                'android 4',
                'opera 12',
                'iOS >= 7']
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dirs.devStyles.dist))
        .pipe(browserSync.reload({ stream: true }))
    //notification message, comment if you don't like
    //.pipe(notify({ message: 'Tarea "Styles" terminada! ', onLast: true }))
});

// vigilar los cambios en los ficheros css, js y php y refrescar el browser
gulp.task('watch', ['dev:styles', 'browserSync'], function () {
    gulp.watch(dirs.devStyles.src + '/**/*.scss', ['dev:styles']);
});

gulp.task('default', ['watch'], function (done) {
    done();
});

/* tareas para entorno de test o producción:

- vaciar directorio dist
- minimizar imágenes en dist
- concatenar y minimizar css y js en dist
- crear sourcemaps de css

*/

// vaciar directori dist
gulp.task('prod:clean', function () {
    return del(dirs.prod.dist);
});

// minimizar imágenes en dist
gulp.task('prod:images', function (done) {
    return gulp.src(dirs.devAssets.src + '/images/**/*')
        .pipe(imagemin([
            imagemin.jpegtran({ progressive: true }),
            imagemin.gifsicle({ interlaced: true }),
            imagemin.optipng({ optimizationLevel: 5 }),
            imagemin.svgo({ plugins: [{ removeUnknownsAndDefaults: false }, { cleanupIDs: false }] })
        ]))
        .pipe(gulp.dest(dirs.prod.dist + '/images'))
        .pipe(notify({ message: 'TASK: "prod:images" Completed!', onLast: true }));
    done();
});

// concatenar y minimizar js en dist
gulp.task('prod:scripts', function (done) {
    gulp.src(dirs.devAssets.src + '/js/*.js')
        .pipe(concat('main.js'))
        .pipe(gulp.dest(dirs.prod.dist + '/js/'))
        .pipe(rename({
            basename: 'main',
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest(dirs.prod.dist + '/js/'))
        .pipe(notify({ message: 'TASK: "prod:scripts" Completed!', onLast: true }));
    done();
});

// concatenar y minimizar css y crear sourcemaps en dist
gulp.task('prod:styles', function (done) {
    gulp.src(dirs.devAssets.src + '/sass/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'compact',
            precision: 10
        }))
        .on('error', console.error.bind(console))
        .pipe(sourcemaps.write({ includeContent: false }))
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(autoprefixer({
            browsers: [
                'last 2 versions',
                'android 4',
                'opera 12',
                'iOS >= 7']
        }))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(dirs.prod.dist + '/css/'))
        .pipe(minmediaqueries({ log: true })) // Merge Media Queries only for.min.css
        .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss({ maxLineLen: 0 }))
        .pipe(gulp.dest(dirs.prod.dist + '/css/'))
        .pipe(notify({ message: 'TASK: "prod:Styles" Completed! ', onLast: true }))
    done();
});

/* Tarea para ejecutar cuando queremos crear entorno de test o producción que llama a las otra tareas */
gulp.task('deploy', ['prod:clean', 'prod:images', 'prod:styles', 'prod:scripts'], function (done) {
    done();
});

