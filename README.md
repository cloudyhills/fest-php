# fest-php
PHP client side classes for interfacing with fest.


## Getting Started

You can load fest-php with composer.  You need to use the following lines to include the file:

````
{
    "repositories": [
        {
          "type": "vcs",
          "url": "https://github.com/cloudyhills/fest-php"
        }
    ],
    "require": {
        "cloudyhills/fest-php": "dev"
    }
}
````

In a PHP program, you can access `fest` services by creating an object of class `FestJob`.  Options for this job are determined by a `FestJobOptions` object.

````
    use \CloudyHills\Fest\FestBackupJob;
    use \CloudyHills\Fest\FestRestoreJob;
    use \CloudyHills\Fest\FestJobOptions;
    
    use Monolog\Logger;

    $options = new FestJobOptions();
    
    $options->set('recurse', True);   // Recurse down directories
    $options->set('pathspec', '*');   // file names, directories, like in linux command line
    $options->set('working_directory', '/var/www/html');  // base directory to back up
    $options->set('name', 'my_backup_2015_12_25');  // backup name.
    $options->set('omit', ['.git/*']);  // pathspecs to ignore

    $log = new Logger('backup');

    $job = new FestBackupJob($log, $options);
    $job->start();
    
    while (job->status() == FestBackupJob::WORKING) {
        $status = $job->getFullStatus();
        echo "\r";
        echo "{$status['num_files']} files   ";
        echo "{$status['num_directories']} directories   ";
        echo "uploaded  {$status['num_bytes_uploaded']}";
        echo " of {$status['num_bytes_total']} bytes.";
    }
    echo "\n";

    if ($job->status() == FestBackupJob::SUCCESS) {
        // professional driver on closed course.  do not attempt.
        shell_exec("rm -rf *");

        $job = new RestoreJob("my backup", $options);
        while (!$job->isFinished())
            echo "Raw Status: " . $job->getStatus();

        if ($job->status() == FestRestoreJob::SUCCESS)
            echo "phew.\n";
    } else {
        "Backup Error Occurred: " . $job->getError() . "\n"
    }
````

## Dependency Injection

`fest-php` uses `monolog` for logging and `guzzle` for communication with the fest library.  Options for these libraries can be overridden or shared with other modules by passing in an `aura-php` dependency injection module:

````
    use \CloudyHills\Fest\FestBackupJob;
    use \CloudyHills\Fest\FestJobOptions;
    use \

````
