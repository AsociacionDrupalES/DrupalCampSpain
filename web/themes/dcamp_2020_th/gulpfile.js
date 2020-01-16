// jshint ignore: start
'use strict';
const gulp = require('gulp');
const sass = require('gulp-sass');
const sassGlob = require('gulp-sass-glob');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const debug = require('gulp-debug');
const through = require('through2');
const fs = require('fs');
const replace = require('replace-in-file');

const settings = {
  // Sources
  sassSrcFiles: ['src/sass/**/*.scss'],
  jsSrcFiles: [
    'src/js/**/*.js',
    '!src/js/blocks/**',
    '!src/js/regions/**'],
  copySrcFiles: [
    'src/js/*blocks/**/*',
    'src/js/*regions/**/*',
    'src/*img/**/*'
  ],

  // Destinations.
  destDir: 'dist',
};

/**
 * Compiles SASS files.
 */
const jsTask = () => {
  return gulp.src(settings.jsSrcFiles)
    .pipe(concat('theme.js'))
    // .pipe(uglify())
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

const pipeGenComponentFiles = () => {
  return through.obj((file, enc, cb) => {
    const jsDestPath = "./src/js/blocks/block-types/";
    const scssDestPath = "./src/sass/blocks/block-types/";
    const templatesDir = "./src/file-templates/block-types/";

    const baseName = file.basename.split('.')[0];
    const componentName = baseName.split('--').pop();
    const capitalizedComponentName = componentName.charAt(0).toUpperCase() + componentName.slice(1);
    const jsName = componentName + '.behavior.js';
    const scssName = componentName + '.scss';
    const jsFullPathFile = jsDestPath + jsName;
    const scssFullPathFile = scssDestPath + scssName;

    // Check if component files already exists.
    let jsCompFileAlreadyExist = fs.existsSync(jsFullPathFile);
    let scssCompFileAlreadyExist = fs.existsSync(scssFullPathFile);

    console.log('');
    console.group('Generated files for "' + componentName + '" component:');

    if (jsCompFileAlreadyExist && scssCompFileAlreadyExist) {
      console.log('All needed files for this component already exist.');
    }
    else {

      if (!jsCompFileAlreadyExist) {
        const templateFullPath = templatesDir + 'template.behavior.js';

        fs.copyFileSync(templateFullPath, jsFullPathFile);

        replace.sync({
          files: jsFullPathFile,
          from: /\[COMPONENT_NAME\]/g,
          to: capitalizedComponentName,
        });

        console.log(jsName);
      }

      if (!scssCompFileAlreadyExist) {
        const templateFullPath = templatesDir + 'template.scss';

        fs.copyFileSync(templateFullPath, scssFullPathFile);

        replace.sync({
          files: scssFullPathFile,
          from: /\[COMPONENT_NAME\]/g,
          to: componentName,
        });

        console.log(scssName);
      }

      let libraryNewContent = fs.readFileSync(templatesDir + 'template.library.yml', 'utf8');
      libraryNewContent = libraryNewContent.replace(/\[COMPONENT_NAME\]/g, componentName);
      fs.appendFileSync('dcamp_2020_th.libraries.yml', libraryNewContent);

    }

    console.log('');
    console.groupEnd();

    return cb(null, file);
  });
};

/**
 * Generates corresponding SCSS and js behavior file for each component
 * template located at "templates/block-types/"
 */
const genComponentsAssetsTask = () => {
  const templatesList = "templates/block-types/block--type--*.html.twig";

  return gulp
    .src(templatesList)
    .pipe(pipeGenComponentFiles());
  // .pipe(debug());
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
exports.genComponentsAssetsTask = genComponentsAssetsTask;
