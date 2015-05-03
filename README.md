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

    $options = new FestJobOptions();
    
    // see full list of options below
    $options->set('async', True);

    $job = new FestBackupJob("my backup", '.', $options);

    while (!$job->isFinished())
        echo "Raw Status: " . $job->getStatus();

    if ($job->status() == FestBackupJob::SUCCESS) {
        // professional driver on closed course.  do not attempt.
        shell_exec("rm -rf *");

        $job = new RestoreJob("my backup", $options);
        while (!$job->isFinished())
            echo "Raw Status: " . $job->getStatus();

        if ($job->status() == FestRestoreJob::SUCCESS)
            echo "phew.\n";
    }
````

## Dependency Injection

`fest-php` uses `monolog` for logging and `guzzle` for communication with the fest library.  Options for these libraries can be overridden or shared with other modules by passing in an `aura-php` dependency injection module:

````
    use \CloudyHills\Fest\FestBackupJob;
    use \CloudyHills\Fest\FestJobOptions;
    use \

````
