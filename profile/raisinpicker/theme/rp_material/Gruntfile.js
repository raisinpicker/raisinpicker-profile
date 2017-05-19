module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    less: {
      development: {
        options: {
          paths: ["css"]
        },
        files: {
          "css/style.css": "less/style.less"
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
  grunt.registerTask('build', ['less', 'cssmin']);
  grunt.registerTask('default', ['less', 'cssmin', 'watch']);
}
