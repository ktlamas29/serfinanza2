const gulp = require("gulp");
const concat = require("gulp-concat");
// const uglify = require("gulp-uglify");
const uglify = require('gulp-uglify-es').default;
const uglifycss = require("gulp-uglifycss");
const strip = require('gulp-strip-comments');
const stripCssComments = require('gulp-strip-css-comments');
const stripDebug = require('gulp-strip-debug');

gulp.task("pack-js", function () {
    return gulp
        .src([
            "js/jquery.min.js",
            "js/jquery-ui.js",
            "js/bootstrap.min.js",
            "js/slick.min.js",
            "js/js-sha.js",
            "js/popper.min.js",
            "js/wow.min.js"
        ])
        .pipe(concat("bundle.js"))
        .pipe(strip())
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(gulp.dest("public/build/js"));
});
gulp.task("pack-index-js", function () {
    return gulp
        .src([
            "js/index.js"
        ])
        .pipe(concat("bundle_index.js"))
        .pipe(strip())
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(gulp.dest("public/build/js"));
});

gulp.task("pack-css", function () {
    return gulp
        .src([
            "css/normalize.css",
            'css/bootstrap.css',
            "css/animate.css"
        ])
        .pipe(concat("styles.css"))
        .pipe(uglifycss())
        .pipe(stripCssComments())
        .pipe(gulp.dest("public/build/css"));
});

gulp.task("default", ["pack-js", "pack-css", 'pack-index-js']);