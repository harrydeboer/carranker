var gulp =require('gulp');
var babel =require('gulp-babel');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');

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

function scss() {
  return (
      gulp.src('public/scss/*.scss')
          .pipe(sass({outputStyle: 'compressed'}))
          .pipe(gulp.dest('public/css'))
  );
}

gulp.task('watch', () => {
  gulp.watch('public/src/*.js', scripts);
  gulp.watch('public/scss/*.scss', scss);
});
