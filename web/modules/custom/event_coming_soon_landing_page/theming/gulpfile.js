'use strict';
const gulp = require('gulp');
const sass = require('gulp-sass');
const sassGlob = require('gulp-sass-glob');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const concat = require('gulp-concat');

const settings = {
  // Sources
  sassSrcFiles: ['src/sass/**/*.scss'],
  jsSrcFiles: ['src/js/**/*.js'],
  copySrcFiles: ['src/*img/**/*'],

  // Destinations.
  destDir: 'dist',
};

/**
 * Compiles SASS files.
 */
const jsTask = () => {
  return gulp.src(settings.jsSrcFiles)
    .pipe(concat('landing-page.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(settings.destDir));
};

/**
 * Compiles SASS files.
 */
const sassTask = () => {
  return gulp.src(settings.sassSrcFiles)
    .pipe(sourcemaps.init())
    .pipe(sassGlob())
    .pipe(sass({outputStyle: 'expanded'}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(settings.destDir));
};

// Copies static files to dist.
const copyTask = () => {
  return gulp
    .src(settings.copySrcFiles)
    .pipe(gulp.dest(settings.destDir));
};

/**
 * Build task.
 */
const buildTask = gulp.parallel(
  sassTask,
  jsTask,
  copyTask
);

/**
 * Watcher.
 */
function watcherTask() {
  buildTask();
  gulp.watch(settings.sassSrcFiles, buildTask);
  gulp.watch(settings.jsSrcFiles, buildTask);
}


exports.build = buildTask;
exports.watch = watcherTask;
