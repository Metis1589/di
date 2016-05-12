<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of directories that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Local' => [
        'path' => 'local',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'resources/restaurant/logo',
            'gateway/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'admin/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'resources/restaurant/logo',
            'gateway/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'admin/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
    'Test' => [
        'path' => 'test',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'resources/restaurant/logo',
            'gateway/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'admin/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'resources/restaurant/logo',
            'gateway/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'admin/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
];
