// jshint ignore: start
'use strict';
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var sassGlob = require('gulp-sass-glob');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

// Some configs:

// Here we define which sass dirs we should process/watch and which exclude.
var sassSrcDirs = ['sass/**/*.scss'];

// Compiles sass files and moves the result to dist.
gulp.task('sass', function () {
  return gulp.src(sassSrcDirs)
    .pipe(sassGlob())
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'expanded',
      includePaths: ['./node_modules/breakpoint-sass/stylesheets']
    }).on('error', sass.logError))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('styles'));
});

// -------------------------------------------------

// Build.
gulp.task('build', ['sass']);

// Watch changes in general and detect when something relevant gets changed.
gulp.task('watch', ['build'], function () {
  gulp.watch(sassSrcDirs, ['build']);
});
