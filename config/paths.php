<?php
return [
    'base_directories' => [
        '/home',
        '/var/www',
        '/opt',
        '/usr/local',
        '/tmp/test',
        $_SERVER['DOCUMENT_ROOT']
    ],
    
    'excluded_paths' => [
        '/proc',
        '/sys',
        '/dev',
        '/run',
        '/boot',
        '/lost+found'
    ],
    
    'excluded_directories' => [
        'node_modules',
        'vendor',
        '.git',
        '.svn',
        '__pycache__'
    ],
    
    'excluded_extensions' => [
        'dll', 'exe', 'so', 'bin', 'app'
    ],
    
    'max_file_size' => 100 * 1024 * 1024, // 100MB
    'file_chunk_size' => 8192
];