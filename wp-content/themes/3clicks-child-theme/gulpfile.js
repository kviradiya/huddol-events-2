//gulpfile.js

var gulp = require('gulp');
gulp.task('default', function () {
  console.log('Hello Gulp!')
});


//style paths
var sassFiles = 'sass/*.scss',
    cssDest = '';

gulp.task('styles', function(){
  gulp.src(sassFiles)
      .pipe(sass().on('error', sass.logError))
      .pipe(gulp.dest(cssDest));
});

gulp.task('watch',function() {
  gulp.watch(sassFiles,['styles']);
});