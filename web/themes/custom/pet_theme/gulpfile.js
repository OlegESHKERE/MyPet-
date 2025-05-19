const gulp = require('gulp');
const postcss = require('gulp-postcss');
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const browserSync = require('browser-sync').create();

const paths = {
  css: {
    src: 'src/css/**/*.css',
    dest: 'dist/css'
  },
  js: {
    src: 'src/js/**/*.js',
    dest: 'dist/js'
  }
};

function styles() {
  return gulp.src(paths.css.src)
    .pipe(sourcemaps.init())
    .pipe(postcss([tailwindcss('./tailwind.config.js'), autoprefixer()]))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.css.dest))
    .pipe(browserSync.stream());
}

function scripts() {
  return gulp.src(paths.js.src)
    .pipe(sourcemaps.init())
    .pipe(concat('script.js'))
    .pipe(uglify())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.js.dest))
    .pipe(browserSync.stream());
}

function watch() {
  browserSync.init({
    proxy: "http://my-pet-web-platform.ddev.site/", 
    open: false
  });
  gulp.watch(paths.css.src, styles);
  gulp.watch(paths.js.src, scripts);
  gulp.watch('templates/**/*.twig').on('change', browserSync.reload);
}

exports.styles = styles;
exports.scripts = scripts;
exports.watch = watch;
exports.default = gulp.series(styles, scripts, watch);
