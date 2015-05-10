<?php

namespace CloudyHills\Fest;


class Manifest {
    protected $logger;
    protected $options;
    protected $filesys;
    protected $data;
    
    function __construct($logger, $options, $filesys) {
        $this->logger = $logger;
        $this->options = $options;
        $this->filesys = $filesys;
        // initial value
        $this->data = [
            'name' => $options->get('name'),
            'timestamp' => date('c'),
            'working_directory' => $options->get('working_directory'),
            'omit' => $options->get('omit'),
            'pathspec' => $options->get('pathspec'),
            'files' => [], 
            'directories' => [] 
        ];
        $this->logger->debug("Created Manifest with Initial Timestamp {$this->data['timestamp']}");
    }
    
    private function processSpec($basepath, $dir, $spec) {
        $fs = $this->filesys;
        $separator = $this->filesys->separator;
        if ($basepath != '' && substr($basepath, -1) != $separator)
            $basepath .= $separator;
        while ($filename = $fs->readdir($dir)) {
            if ($filename[0] == '.')
                continue;
            $fullname = $basepath . $filename;
            if (fnmatch($spec, $fullname)) {
                $bExclude = False;
                foreach ($this->options->get('omit') as $exclude) {
                    if (fnmatch($exclude, $fullname)) {
                        $bExclude = True;
                        break;
                    }
                }
                if (!$bExclude) {
                    $entry = [
                        'timestamp' => $fs->getTime($fullname),
                        'owner' => $fs->getOwner($fullname),
                        'permissions' => $fs->getPerms($fullname),
                    ];
                    if ($entry['permissions'][0] == 'd') {
                        $this->data['directories'][$fullname] = $entry;
                        if ($this->options->get('recurse')) {
                            $subdir = $fs->opendir($fullname);
                            $this->processSpec($fullname, $subdir, '*');
                        }
                    } else if ($entry['permissions'][0] == '') {
                        // TODO:  add special processing for other file times here
                    } else {
                        $entry['hash'] = $fs->hashFile('sha256', $fullname);
                        $entry['size'] = $fs->getSize($fullname);
                        $this->data['files'][$fullname] = $entry;
                    }
                }
            }
        }
    }

    // populates the manifest file list based on spec in the options
    public function hydrate() {
        $this->logger->debug("Hydrate: cwd = '{$this->options->get('working_directory')}', pathspec = '{$this->options->get('pathspec')}', recurse = '{$this->options->get('recurse')}'");

        $fs = $this->filesys;
        // move working directory
        $savedir = $fs->getcwd();
        $this->logger->debug("savedir = $savedir");
        try {
            $fs->chdir($this->options->get('working_directory'));
            $dirh = $fs->opendir('.');
            $specs = explode(' ', trim($this->options->get('pathspec')));
            foreach ($specs as $spec)
                $this->processSpec('', $dirh, $spec);
        } finally {
            $fs->chdir($savedir);
        }
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
