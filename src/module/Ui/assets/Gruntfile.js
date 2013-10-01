'use strict';

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {
    // show elapsed time at the end
    require('time-grunt')(grunt);
    // load all grunt tasks
    require('load-grunt-tasks')(grunt);

    // configurable paths
    var config = {
        app: 'source',
        dist: 'build'
    };

    grunt.initConfig({
        serlo: config,
        watch: {
            coffee: {
                files: ['<%= serlo.app %>/scripts/{,*/}*.coffee'],
                tasks: ['coffee:dist']
            },
            coffeeTest: {
                files: ['test/spec/{,*/}*.coffee'],
                tasks: ['coffee:test']
            },
            compass: {
                files: ['<%= serlo.app %>/styles/{,*/}*.{scss,sass}'],
                tasks: ['compass:server', 'autoprefixer']
            },
            styles: {
                files: ['<%= serlo.app %>/styles/{,*/}*.css'],
                tasks: ['copy:styles', 'autoprefixer']
            },
            scripts: {
                files: ['<%= serlo.app %>/scripts/{,*/}*.js'],
                tasks: ['jshint', 'copy:bower', 'requirejs']
            },
            images: {
                files: ['<%= serlo.app %>/images/{,*/}*.{png,jpg,jpeg}'],
                tasks: ['imagemin']
            },
            fonts: {
                files: ['<%= serlo.app %>/styles/fonts/*'],
                tasks: ['copy:dist']
            }
        },
        clean: {
            dist: {
                files: [{
                    dot: true,
                    src: [
                        '<%= serlo.dist %>/*',
                        '!<%= serlo.dist %>/.git*'
                    ]
                }]
            },
            server: '<%= serlo.dist %>'
        },
        jshint: {
            options: {
                jshintrc: '.jshintrc'
            },
            all: [
                'Gruntfile.js',
                '<%= serlo.app %>/scripts/{,*/}*.js'
            ]
        },
        coffee: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= serlo.app %>/scripts',
                    src: '{,*/}*.coffee',
                    dest: '<%= serlo.dist %>/scripts',
                    ext: '.js'
                }]
            },
            test: {
                files: [{
                    expand: true,
                    cwd: 'test/spec',
                    src: '{,*/}*.coffee',
                    dest: '<%= serlo.dist %>/spec',
                    ext: '.js'
                }]
            }
        },
        compass: {
            options: {
                sassDir: '<%= serlo.app %>/styles',
                cssDir: '<%= serlo.dist %>/styles',
                generatedImagesDir: '<%= serlo.dist %>/images/generated',
                imagesDir: '<%= serlo.app %>/images',
                javascriptsDir: '<%= serlo.app %>/scripts',
                fontsDir: '<%= serlo.app %>/styles/fonts',
                importPath: '<%= serlo.app %>/bower_components',
                httpImagesPath: '/images',
                httpGeneratedImagesPath: '/images/generated',
                httpFontsPath: '/styles/fonts',
                relativeAssets: false
            },
            dist: {
                options: {
                    generatedImagesDir: '<%= serlo.dist %>/images/generated'
                }
            },
            server: {
                options: {
                    debugInfo: true
                }
            }
        },
        autoprefixer: {
            options: {
                browsers: ['last 1 version']
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= serlo.dist %>/styles/',
                    src: '{,*/}*.css',
                    dest: '<%= serlo.dist %>/styles/'
                }]
            }
        },
        // not used since Uglify task does concat,
        // but still available if needed
        /*concat: {
            dist: {}
        },*/
        'bower-install': {
            app: {
                html: '<%= serlo.app %>/index.html',
                ignorePath: '<%= serlo.app %>/'
            }
        },
        // not enabled since usemin task does concat and uglify
        // check index.html to edit your build targets
        // enable this task if you prefer defining your build targets here
        uglify: {
            dist: {
                files: {
                    '<%= serlo.dist %>/scripts/main.min.js': '<%= serlo.dist %>/scripts/main.js'
                }
            }
        },
        rev: {
            dist: {
                files: {
                    src: [
                        '<%= serlo.dist %>/scripts/{,*/}*.js',
                        '<%= serlo.dist %>/styles/{,*/}*.css',
                        '<%= serlo.dist %>/images/{,*/}*.{png,jpg,jpeg,gif,webp}',
                        '<%= serlo.dist %>/styles/fonts/{,*/}*.*'
                    ]
                }
            }
        },
        useminPrepare: {
            options: {
                dest: '<%= serlo.dist %>'
            },
            html: '<%= serlo.app %>/index.html'
        },
        usemin: {
            options: {
                dirs: ['<%= serlo.dist %>']
            },
            html: ['<%= serlo.dist %>/{,*/}*.html'],
            css: ['<%= serlo.dist %>/styles/{,*/}*.css']
        },
        imagemin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= serlo.app %>/images',
                    src: '{,*/}*.{png,jpg,jpeg}',
                    dest: '<%= serlo.dist %>/images'
                }]
            }
        },
        svgmin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= serlo.app %>/images',
                    src: '{,*/}*.svg',
                    dest: '<%= serlo.dist %>/images'
                }]
            }
        },
        cssmin: {
            // This task is pre-configured if you do not wish to use Usemin
            // blocks for your CSS. By default, the Usemin block from your
            // `index.html` will take care of minification, e.g.

            //     <!-- build:css({.tmp,app}) styles/main.css -->

            minify: {
                expand: true,
                cwd: '<%= serlo.dist %>/styles/',
                src: ['*.css', '!*.min.css'],
                dest: '<%= serlo.dist %>/styles/',
                ext: '.min.css'
            }
        },
        // Put files not handled in other tasks here
        copy: {
            dist: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= serlo.app %>',
                    dest: '<%= serlo.dist %>',
                    src: [
                        // '*.{ico,png,txt}',
                        '.htaccess',
                        // 'images/{,*/}*.{webp,gif}',
                        'styles/fonts/{,*/}*.*'
                    ]
                }]
            },
            styles: {
                expand: true,
                dot: true,
                cwd: '<%= serlo.app %>/styles',
                dest: '<%= serlo.dist %>/styles/',
                src: '{,*/}*.css'
            },
            bower: {
                expand: true,
                dot: true,
                cwd: '<%= serlo.app %>/bower_components/requirejs',
                dest: '<%= serlo.dist %>/bower_components/requirejs',
                src: 'require.js'
            }
        },
        modernizr: {
            devFile: '<%= serlo.app %>/bower_components/modernizr/modernizr.js',
            outputFile: '<%= serlo.dist %>/bower_components/modernizr/modernizr.js',
            files: [
                '<%= serlo.dist %>/scripts/{,*/}*.js',
                '<%= serlo.dist %>/styles/{,*/}*.css',
                '!<%= serlo.dist %>/scripts/vendor/*'
            ],
            uglify: true
        },
        concurrent: {
            server: [
                'compass',
                'coffee:dist',
                'copy:styles',
                'copy:bower'
            ],
            test: [
                'coffee',
                'copy:styles',
                'copy:bower'
            ],
            dist: [
                'coffee',
                'compass',
                'copy:styles',
                'copy:bower',
                // 'imagemin',
                'svgmin'
            ]
        },
        requirejs: {
            compile: {
                options: {
                    baseUrl: "<%= serlo.app %>/scripts",
                    mainConfigFile: "source/scripts/main.js",
                    out: "<%= serlo.dist %>/scripts/main.js",
                    optimize: 'none'
                }
            }
        }
    });

    grunt.registerTask('dev', function (target) {
        if (target === 'dist') {
            return grunt.task.run(['build']);
        }

        grunt.task.run([
            'clean:server',
            'concurrent:server',
            'autoprefixer',
            'copy:bower',
            'requirejs',
            'copy:dist',
            'watch'
        ]);
    });

    grunt.registerTask('build', [
        'clean:dist',
        // 'useminPrepare',
        'concurrent:dist',
        'autoprefixer',
        // 'concat',
        // 'modernizr',
        'copy:dist',
        'cssmin',
        'imagemin',
        'requirejs',
        'uglify'
        // 'rev',
        // 'usemin'
    ]);

    grunt.registerTask('default', [
        'jshint',
        'build'
    ]);
};
