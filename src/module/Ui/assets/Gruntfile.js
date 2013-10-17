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
            compass: {
                files: ['<%= serlo.app %>/styles/{,*/}*.{scss,sass}'],
                tasks: ['compass:server', 'autoprefixer']
            },
            styles: {
                files: ['<%= serlo.app %>/styles/{,*/}*.css'],
                tasks: ['copy:styles', 'autoprefixer']
            },
            jsLang: {
                files: ['<%= serlo.app %>/lang/*'],
                tasks: ['concat:lang']
            },
            scripts: {
                files: ['<%= serlo.app %>/scripts/{,*/}*.js'],
                tasks: ['jshint', 'copy:requirejs', 'requirejs']
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
                jshintrc: '.jshintrc',
                ignores: [
                    '<%= serlo.app %>/scripts/thirdparty/{,*/}*.js',
                    '<%= serlo.app %>/scripts/modules/serlo_i18n.js'
                ]
            },
            all: [
                '<%= serlo.app %>/scripts/{,*/}*.js'
            ]
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
                browsers: ['last 2 version']
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
        'bower-install': {
            app: {
                html: '<%= serlo.app %>/index.html',
                ignorePath: '<%= serlo.app %>/'
            }
        },
        uglify: {
            dist: {
                files: {
                    '<%= serlo.dist %>/scripts/main.min.js': '<%= serlo.dist %>/scripts/main.js'
                }
            }
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
                        '.htaccess',
                        'styles/fonts/{,*/}*.*',
                        'bower_components/jquery/jquery.min.map'
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
            requirejs: {
                expand: true,
                dot: true,
                cwd: '<%= serlo.app %>/bower_components/requirejs',
                dest: '<%= serlo.dist %>/bower_components/requirejs',
                src: 'require.js'
            },
            modernizr: {
                expand: true,
                dot: true,
                cwd: '<%= serlo.app %>/bower_components/modernizr',
                dest: '<%= serlo.dist %>/bower_components/modernizr',
                src: 'modernizr.js'
            }
        },
        concat: {
            options: {
                banner: '/**\n * Dont edit this file!\n' +
                    ' * This module generates itself from lang.js files!\n' +
                    ' * Instead edit the language files in /lang/\n' +
                    ' **/\n\n' +
                    '/*global define*/\n' +
                    'define(function () {\n' +
                    'var i18n = {};\n',
                footer: '\nreturn i18n;\n' +
                    '});'
            },
            lang: {
                src: ['<%= serlo.app %>/lang/*.js'],
                dest: '<%= serlo.app %>/scripts/modules/serlo_i18n.js'
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
                'copy:styles',
                'copy:requirejs'
            ],
            test: [
                'copy:styles',
                'copy:requirejs'
            ],
            dist: [
                'compass',
                'copy:styles',
                'copy:requirejs',
                'svgmin'
            ]
        },
        requirejs: {
            compile: {
                options: {
                    baseUrl: "<%= serlo.app %>/scripts",
                    mainConfigFile: "source/scripts/main.js",
                    out: "<%= serlo.dist %>/scripts/main.js",
                    preserveLicenseComments: false,
                    optimize: 'none', // set to uglify2
                    uglify2: {
                        output: {
                            beautify: false
                        },
                        compress: {
                            sequences: true,
                            global_defs: {
                                DEBUG: true
                            }
                        },
                        warnings: true,
                        mangle: true
                    }
                }
            }
        },
        process: {
            lang: {
                files: [{
                    src: '<%= serlo.app %>/scripts/{,*/}*.js',
                    dest: '<%= serlo.app %>/lang-processed/'
                }],
                options: {
                    processors: [
                        {
                            pattern: /(^t\(| t\(|\nt\()('|")(.*)\)/g,
                            setup: function () {
                                return {
                                    strings: []
                                };
                            },
                            handler: function (context, params) {
                                console.log(params);
                                if (params.match && params.match.length) {
                                    var string = (/(["'])((?:[^\\\1]|(?:\\\\)*|\\.)*?)\1/.exec(params.match)[0]);
                                    context.strings.push(string);
                                }
                            },
                            teardown: function (context) {
                                console.log(context.strings);
                            }
                        }
                    ]
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
            'copy:requirejs',
            'concat:lang',
            'requirejs',
            'copy:dist',
            'copy:modernizr',
            'watch'
        ]);
    });

    grunt.registerTask('build', [
        'clean:dist',
        'concurrent:dist',
        'autoprefixer',
        'copy:dist',
        'concat:lang',
        'cssmin',
        'imagemin',
        'requirejs',
        'modernizr',
        'uglify'
    ]);

    grunt.registerTask('update-lang', [
        'process:lang'
    ]);

    grunt.registerTask('default', [
        'jshint',
        'build'
    ]);
};
