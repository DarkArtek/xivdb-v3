<?php

namespace Deployer;

// Project name
set('application', 'xivdb-v3-sysops');
set('repository', '');
set('ssh_multiplexing', false);
inventory('hosts.yml');

// --------------------------------------------------------

function result($text)
{
    $text = explode("\n", $text);

    foreach($text as $i => $t) {
        $text[$i] = "| ". $t;
    }

    writeln("|");
    writeln(implode("\n", $text));
    writeln("|");
    writeln("");
}

function deploy($repo)
{
    writeln("------------------------------------------------------------------------------------");
    writeln("- Repository: {$repo}");
    writeln("------------------------------------------------------------------------------------");
    
    // set directory
    cd("/home/xivdb/{$repo}/");

    // we can make a lot of assumptions from the directory
    writeln('Checking authentication ...');

    //
    // Reset any existing changes
    //
    $branchStatus = run('git status');
    if (stripos($branchStatus, 'Changes not staged for commit') !== false) {
        writeln('Changes on production detected, resetting git head.');
        $result = run('git reset --hard');
        result($result);
        $result = run('git status');
        result($result);
    }

    //
    // Pull latest changes
    //
    writeln('Pulling latest code from bitbucket ...');
    $result = run('git pull');
    result($result);
    writeln('Latest 10 commits:');
    $result = run('git log -10 --pretty=format:"%h - %an, %ar : %s"');
    result($result);

    // check some stuff
    $directory = run('ls -l');
    $doctrine = run('test -e config/packages/doctrine.yaml && echo 1 || echo 0') === '1';
    
    //
    // Composer update
    //

    if (stripos($directory, 'composer.json') !== false) {
        writeln('Updating composer libraries (it is normal for this to take a while)...');
        $result = run('composer update');
        result($result);
    }
    
    //
    // Write version
    //
    writeln('Setting git version+hash');
    run('git rev-list --all --count > ./git_version.txt');
    run('git rev-parse HEAD > ./git_hash.txt');

    //
    // Clear symfony cache
    //
    if (stripos($directory, 'symfony.lock') !== false) {
        writeln('Clearing symfony cache ...');
        $result = run('php bin/console cache:warmup') . "\n";
        $result .= run('php bin/console cache:clear') . "\n";
        $result .= run('php bin/console cache:clear --env=prod');
        result($result);

        //
        // Update database schema
        //
        if ($doctrine) {
            writeln('Updating database schema ...');

            // update db
            $result = run('php bin/console doctrine:schema:update --force --dump-sql');
            result($result);

            // ask if we should drop the current db
            /*
            $shouldDropDatabase = askConfirmation('(Symfony) Drop Database?', false);
            if ($shouldDropDatabase) {
                run('php bin/console doctrine:schema:drop --force');
            }
            */
        }
    }
}

// --------------------------------------------------------

$repoNames = [];
$repoDeployments = [
    // sysops
    ['sysops',          'monty,noggy,mojito,mosh,atla,suzuna,xivsync'],
    // monty
    ['frontend',        'monty'],
    // noggy
    ['comments',        'noggy'],
    ['devapps',         'noggy'],
    ['email',           'noggy'],
    ['feedback',        'noggy'],
    ['mognet',          'noggy'],
    ['pages',           'noggy'],
    ['screenshots',     'noggy'],
    ['translations',    'noggy'],
    // mojito
    ['data',            'mojito'],
    ['api',             'mojito'],
    ['search',          'suzuna'],
    // atla
    ['sync',            'atla'],
];

foreach ($repoDeployments as $repo) {
    [$name, $hosts] = $repo;
    $hosts = explode(',', $hosts);
    
    $repoNames[] = $name;
    
    // build task
    task($name, function () use ($name, $hosts) {
        deploy("xivdb-v3-{$name}");
    })->onHosts($hosts);
}

// build task
task('xivsync', function () {
    deploy("xivsync");
})->onHosts('xivsync_home', 'xivsync_characters_1');

task('deploy:all', $repoNames);
