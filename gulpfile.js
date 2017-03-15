/*var gulp = require('gulp'),
    watch = require('gulp-watch'),
    plumber = require('gulp-plumber'),
    pug = require('gulp-pug'),
    affected = require('gulp-jade-find-affected'),
    prettify = require('gulp-prettify');

var path = {
    build: {
        // html: '_html/'
        html: 'src/components/_pages/__html/'
    },
    src: {
        html: 'src/components/_pages/!*.pug'
    },
    watch: {
        html: 'src/components/!**!/!*.pug'
    }
};

gulp.task('templates:build', function () {
    gulp.src(path.src.html)
        .pipe(plumber())
        .pipe(affected())
        .pipe(pug())
        .pipe(prettify({
            brace_style: 'expand',
            indent_size: 4,
            indent_char: ' ',
            indent_with_tabs: false,
            condense: true,
            indent_inner_html: true,
            preserve_newlines: true
        }))
        .pipe(gulp.dest(path.build.html));
});

gulp.task('watch', function () {
    watch([path.watch.html], function (e, cb) {
        gulp.start('templates:build');
    });
});

gulp.task('default', ['templates:build', 'watch']);*/

// var gulp = require('gulp');
// var shell = require('gulp-shell');
//
// gulp.task('webpack-dev-server', shell.task(['webpack-dev-server']));
// gulp.task('default', ['webpack-dev-server']);
//
// function onError (error) {
//     console.log(error.toString());
//     this.emit('end');
// }
