<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/rsync.php';

// Config

set('repository', 'https://github.com/Tirya/smartcalendar.git');
set('bin/composer', '/usr/bin/composer');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('smartcalendar.axelschn.ch')
    ->setHostname('cr7pm.ftp.infomaniak.com')
    ->set('remote_user', 'cr7pm_deployer')
    ->set('deploy_path', '/home/clients/55e0a135b89a29f2d29615838ee954a1/sites/smartcalendar.axelschn.ch');

add('rsync', [
    'exclude' => [
        '.git',
        '/.env',
        // '/storage/',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);

set('ssh_multiplexing', true); // Speed up deployment

set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

after('deploy:failed', 'deploy:unlock'); // Unlock after failed deploy

desc('Deploy the application');


// Tasks

task('deploy', [
    'deploy:setup',
    'deploy:release',
    'deploy:info',
    'deploy:lock',
    'rsync', // Deploy code & built assets
    'deploy:secrets', // Deploy secrets
    'deploy:vendors',
    'artisan:storage:link', // |
    'artisan:view:cache',   // |
    'artisan:config:cache', // | Laravel specific steps 
    'artisan:optimize',     // |
    'artisan:migrate',      // |
    'deploy:symlink',
    'deploy:unlock',
    'deploy:cleanup',
]);

// Set up a deployer task to copy secrets to the server. 
// Grabs the dotenv file from the github secret
task('deploy:secrets', function () {
    file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
    upload('.env', get('release_path'));
});

after('deploy:failed', 'deploy:unlock');

