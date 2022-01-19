<?php
/**
 * SqliteConnection
 * 
 * @package Phone
 */
namespace Phone;

use SQLite3;

/**
 * SqliteConnection
 * 
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
class SqliteConnection extends \SQLite3
{
    /**
     * opened holds whether the connection has opened or not.
     *
     * @var boolean
     */
    protected static $opened = false;

    /**
     * __constract open sqlite connection if its not already opened. 
     */
    public function __construct()
    {
        if (! self::$opened) {
            $this->open(__DIR__. '/../sample.db');
            $this->createRegexpFunction();
            self::$opened = true;
        }
    }

    protected function createRegexpFunction()
    {
        $regexp = function ($pattern, $string) {
            if (preg_match('/'.$pattern.'/', $string)) {
                return true;
            }
            return false;
        };

        SQLite3::createFunction(
            'regexp',
            $regexp,
            2
        );
    }
}