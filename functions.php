<?php
/**
 * Helper functions for simply and naively rendering PHP templates with an isolated
 * variable scope (like a View) and for locking files for use with a cron or daemon.
 *
 * @author David Rogers <david@orlandophp.org>
 */


// If a template path hasn't already been defined, make some assumptions...
defined('TPL_PATH') or define('TPL_PATH', realpath(dirname(__DIR__) . '/templates'));


/**
 * Dead-simple template rendering with isolated $context via extract() and require().
 *
 * @param string $template filename to render
 * @param array $context to render with $template
 */
function render ( $template, $context )
{
    extract((array) $context); require(realpath(implode(DIRECTORY_SEPARATOR, array(
        TPL_PATH, $template
    ))));
}


/**
 * Mainly providing a namespace for locking errors.
 */
class LockError extends Exception { }


/**
 * Lock and optionally create $filename, storing the current PID in it.
 *
 * @param string $filename
 * @return resource file pointer from fopen()
 */
function lock ( $filename )
{
    if ( !($file = @fopen($filename, 'c')) or !flock($file, LOCK_EX | LOCK_NB) )
        throw new LockError("Cannot create `{$filename}`, check permissions?");

    ftruncate($file, 0); fputs($file, getmypid());

    return $file;
}


/**
 * Unlock the locked $file and close the file pointer.
 *
 * @param resource $file
 */
function unlock ( $file )
{
    flock($file, LOCK_UN); fclose($file);
}

