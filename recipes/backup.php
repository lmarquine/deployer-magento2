<?php

namespace Deployer;

desc('Backup the database');
task('magento:db:backup', function () {
    $remoteDump = "var/";
    run("cd {{release_path}}{{magento_dir}} && {{magerun}} db:dump -s @development -c gz $(date +%Y%m%d%H%M%S) {{verbose}}");
    run("cd {{release_path}}{{magento_dir}} && mv *.sql.gz ". $remoteDump);
});

desc('Download a database dump');
task('magento:db:download', function () {

    $remoteDump = "var/";
    $localDump = runLocally('pwd');
    $timeout = 300;
    $config = [
        'timeout' => $timeout
    ];

    write('Creating the database dump...');
    run("cd {{release_path}}{{magento_dir}} && {{magerun}} db:dump -s @development -c gz bkp {{verbose}}");
    run("cd {{release_path}}{{magento_dir}} && mv bkp.sql.gz ". $remoteDump);

    write('Downloading the SQL file...');
    download('{{release_path}}{{magento_dir}}'.$remoteDump.'bkp.sql.gz', $localDump.'/', $config);
    runLocally('mv bkp.sql.gz deployer_database_backup.sql.gz');
    run('rm {{release_path}}{{magento_dir}}'.$remoteDump.'bkp.sql.gz');

    write('Your database dump is called: deployer_database_backup.sql.gz');
});

desc('Backup the database');
task('magento:media:backup', function () {
    $remoteDump = "var/";
    run("cd {{release_path}}{{magento_dir}} && {{magerun}} media:dump --strip media-$(date +%Y%m%d%H%M%S).zip {{verbose}}");
    run("cd {{release_path}}{{magento_dir}} && mv media-* ". $remoteDump);
});

desc('Backup the media folder');
task('magento:media:download', function () {

    $remoteDump = "var/";
    $localDump = runLocally('pwd');
    $timeout = 300;
    $config = [
        'timeout' => $timeout
    ];

    write('Creating a Media ZIP backup...');
    run("cd {{release_path}}{{magento_dir}} && {{magerun}} media:dump --strip media_dump.zip");
    run("cd {{release_path}}{{magento_dir}} && mv media_dump.zip ". $remoteDump);

    write('Downloading the Media ZIP file...');
    download('{{release_path}}{{magento_dir}}'.$remoteDump.'media_dump.zip', $localDump.'/', $config);
    runLocally('mv media_dump.zip deployer_media_dump.zip');
    run('rm {{release_path}}{{magento_dir}}'.$remoteDump.'media_dump.zip');

    write('Your Media ZIP file is called: deployer_media_dump.zip');
});