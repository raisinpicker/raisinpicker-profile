module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    less: {
      development: {
        options: {
          paths: ["css"]
        },
        files: {
          "css/bsmaterial.css": "less/settings.less"
        }
      }
    },
    cssmin: {
      files: {
        expand: true,
        cwd: 'css',
        src: ['*.css', '!*.min.css'],
        dest: 'css',
        ext: '.min.css'
      }
    },
    concat: {
      dist: {
        src: ['node_modules/bootstrap-material-design/dist/js/ripples.min.js', 'node_modules/bootstrap-material-design/dist/js/material.min.js'],
        dest: 'js/bsmaterial.min.js'
      }
    },
    watch: {
      grunt: {
        options: {
          reload: true
        },
        files: ['Gruntfile.js']
      },
      css: {
        files: 'less/**/*.less',
        tasks: ['less', 'cssmin']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  
  grunt.registerTask('build', ['less', 'concat', 'cssmin']);
  grunt.registerTask('default', ['less', 'cssmin', 'watch']);
}
