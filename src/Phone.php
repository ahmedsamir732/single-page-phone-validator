<?php
/**
 * Phone
 */
namespace Phone;

use Phone\Resource\ResourcInterface;
use Phone\Resource\SqliteResource;

/**
 * Phone class
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 * @package Phone
 */
class Phone
{
    public static function list(array $filters, int $page = 0)
    {
        $resource = self::getResource('sqlite');
        return $resource->page($page)->get($filters);
    }

    public static function getResource($type): ResourcInterface
    {
        switch ($type) {
            case 'sqlite':
                $resource = new SqliteResource(new SqliteConnection);
                break;
            default:
                $resource = new SqliteResource(new SqliteConnection);
                break;
        }

        return $resource;
    }
}