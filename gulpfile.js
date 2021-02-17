var gulp =require('gulp');
var babel =require('gulp-babel');
var uglify = require('gulp-uglify');

function scripts() {
  return (
      gulp.src('public/src/*.js')
          .pipe(babel({
            presets: ['@babel/env']
          }))
          .pipe(uglify())
          .pipe(gulp.dest('public/js'))
  );
}

gulp.task('watch', () => {
  gulp.watch('public/src/*.js', scripts);
});
