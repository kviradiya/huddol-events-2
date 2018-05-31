//gulpfile.js

var gulp = require('gulp');
var sass = require('gulp-sass');
gulp.task('default', function () {
  console.log('Hello Gulp!')
});


//style paths
var sassFiles = '3clicks-child-theme/sass/*.scss',
    cssDest = '3clicks-child-theme/';

gulp.task('styles', function(){
  gulp.src(sassFiles)
      .pipe(sass().on('error', sass.logError))
      .pipe(gulp.dest(cssDest));
  console.log("file written to " + cssDest)
});

gulp.task('watch',function() {
  gulp.watch(sassFiles,['styles']);
});