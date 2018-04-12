var gulp		= require('gulp'),
	watch		= require('gulp-watch'),
	php			= require('gulp-connect-php'),
	browserSync	= require("browser-sync").create(),
	reload		= browserSync.reload;

var config = {
    proxy: '127.0.0.1:8080',
    startPath: '/',
	port: 9000
};

gulp.task('watch', function () {
	gulp.watch([
		'lib/**/*.*',
		'public/**/*.*',
		'translations/**/*.*',
		'views/**/*.*',
		'*.php',
		'gulpfile.js',
	]).on('change', reload);
});

gulp.task('default', ['watch', 'php'], function () {
	browserSync.init(config);
});

gulp.task('php', function() {
    php.server({base: ".", port: 8080, keepalive: true});
});