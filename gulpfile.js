const gulp = require('gulp');
const cleanCSS = require('gulp-clean-css');
const compass = require('gulp-compass');
const autoprefixer = require('gulp-autoprefixer');

gulp.task('default', () => {
    return gulp.src('public/dist/scss/*.scss')
        .pipe(compass({
            sass: 'public/dist/scss/',
            css: 'public/dist/css/'
        }))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('public/dist/css/'));
});

gulp.task('watch', function () {
    gulp.watch('public/dist/scss/*.scss', gulp.series('default'))
});