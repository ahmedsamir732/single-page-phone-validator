<?php
/**
 * ResourcInterface
 * @package Phone
 */
namespace Phone\Resource;

/**
 * Resource interface
 * @package Phone
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
interface ResourcInterface
{
    /**
     * page sets the wanted page number.
     *
     * @param integer $page
     * @return ResourcInterface
     */
    public function page(int $page): ResourcInterface;

    /**
     * get renders phone numbers according to filters and page number to set limit, offset.
     *
     * @param array $filters
     * @return array
     */
    public function get(array $filters = []): array;
}