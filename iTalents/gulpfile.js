const gulp = require('gulp')
const webpack = require('webpack-stream')

gulp.task('default', function() {
  	return gulp.src('resources/assets/js/main.js')
    	.pipe(webpack( require('./webpack.config.js') ))
    	.pipe(gulp.dest('public/js'))
})