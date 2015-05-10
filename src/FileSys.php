<?php

namespace CloudyHills\Fest;

class FileSys {
    public $separator;

    function __construct() {
        $this->separator = '/';
    }

    function opendir($path) {
        return opendir($path);
    }

    function readdir($handle) {
        return readdir($handle);
    }

    function getOwner($path) {
        $intName = fileowner($path);
        $intGroup = filegroup($path);
        $ginfo = $intGroup == 0 ? '0' : posix_getgrgid($intGroup);
        $uinfo = $intName == 0 ? '0' : posix_getpwuid($intName);
        return $uinfo['name'] . ':' . $ginfo['name']; 
    }

    function getPerms($path) {
        $perms = fileperms($path);

        if (($perms & 0xC000) == 0xC000) { // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) { // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) { // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) { // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) { // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) { // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) { // FIFO pipe
            $info = 'p';
        } else { // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ?  'r' : '-');
        $info .= (($perms & 0x0080) ?  'w' : '-');
        $info .= (($perms & 0x0040) ?  (($perms & 0x0800) ?  's' : 'x' ) : (($perms & 0x0800) ?  'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ?  'r' : '-');
        $info .= (($perms & 0x0010) ?  'w' : '-');
        $info .= (($perms & 0x0008) ?  (($perms & 0x0400) ?  's' : 'x' ) : (($perms & 0x0400) ?  'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ?  'r' : '-');
        $info .= (($perms & 0x0002) ?  'w' : '-');
        $info .= (($perms & 0x0001) ?  (($perms & 0x0200) ?  't' : 'x' ) : (($perms & 0x0200) ?  'T' : '-'));

        return $info;
    }

    function getTime($path) {
        return date('c', filemtime($path));
    }

    function getcwd() {
        if (!($cwd = getcwd()))
            throw new FileSystemException("Cannot get current working directory");
        return $cwd;
    }

    function chdir($dir) {
        if (!chdir($dir))
            throw new FileSystemException("Cannot set current working directory to $dir");
    }

    function setOwner($path, $owner) {
        list($ownerString, $groupString) = explode(':', $owner);
        $ownerString = trim($ownerString);
        $groupString = trim($groupString);
        
        $retval = True;  // innocent until proven guilty
        if ($ownerString != '0') {
            $retval = chown($path, $ownerString);
        }

        if ($groupString != '0') {
            $retval = chgrp($path, $ownerString);
        }
        return $retval;
    }

    function hashFile($type, $path) {
        return hash_file($type, $path);
    }

    function getSize($path) {
        return filesize($path);
    }
}
