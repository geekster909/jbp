var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var autoprefixer = require('gulp-autoprefixer');
var notify = require("gulp-notify");
var plumber = require('gulp-plumber');
var sourcemaps = require('gulp-sourcemaps');

var notifyGeneric = {
    title: function () {
      return '<%= file.relative %>';
    },
    onLast: true,
    subtitle: "Successfully Compiled",
    message: "@ Time: <%= options.hour %>:<%= options.minute %>:<%= options.second %> ",
    templateOptions: {
      hour: new Date().getHours(),
      minute: new Date().getMinutes(),
      second: new Date().getSeconds()
    }
};

var onError = function(err) {
    notify.onError({
      title:    "Gulp",
      subtitle: "Failure!",
      message:  "Error: <%= error.message %>",
      sound:    "Sosumi"
    })(err);

    this.emit('end');
};

gulp.task('default', ['sass', 'compress', 'watch'])

gulp.task('compile', ['sass', 'compress']);

gulp.task('sass', function(){
  return gulp.src('scss/styles.scss')
    .pipe(sourcemaps.init())
  	.pipe(plumber({errorHandler: onError}))
    .pipe(sass().on('error', sass.logError)) // Using gulp-sass
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('css'))
    .pipe(notify(notifyGeneric))
});

gulp.task('compress', function() {
  return gulp.src('js/jbp.js')
  	.pipe(plumber({errorHandler: onError}))
    .pipe(uglify())
    .pipe(rename({
      suffix: '-min'
    }))
    .pipe(gulp.dest('js/min'))
    .pipe(notify(notifyGeneric));
});



gulp.task('watch', function () {
    gulp.watch('scss/*.scss', ['sass']);
    gulp.watch('js/jbp.js', ['compress']);
});
