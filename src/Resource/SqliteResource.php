<?php
/**
 * SqliteResource
 * @package Phone
 */
namespace Phone\Resource;

use Phone\Helpers\CountryHelper;
use Phone\SqliteConnection;

/**
 * SqliteResource
 * @package Phone
 * @author  Ahmed Samir <ahmedsamir732@gmail.com>
 */
class SqliteResource implements ResourcInterface
{
    /**
     * $connection resource connection
     *
     * @var SqliteConnection
     */
    protected $connection;

    /**
     * $page holds the page number in pagination. 
     *
     * @var int
     */
    protected $page = null;

    /**
     * const PER_PAGE
     */
    const PER_PAGE = 5;

    /**
     * __constract
     *
     * @param SqliteConnection $connection
     */
    public function __construct(SqliteConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * page sets the wanted page number.
     *
     * @param integer $page
     * @return ResourcInterface
     */
    public function page(int $page): ResourcInterface
    {
        $this->page = $page;
        return $this;
    }

    /**
     * get renders phone numbers according to filters and page number to set limit, offset.
     *
     * @param array $filters
     * @return array
     */
    public function get(array $filters = []): array
    {
        $query = 'SELECT '.$this->buildSelect().' FROM customer ';
        if (!empty($filters)) {
            $conditions = $this->buildConditions($filters);
            $query .= 'WHERE '. $this->buildQuery($conditions);
        }

        $result = $this->connection->query($query);
        $rows = [];
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * buildQuery return sqlite consitions as string
     *
     * @param array $filters
     * @return array
     */
    protected function buildConditions(array $filters): array
    {
        $conditions = [];
        if (!empty($filters['country'])) {
            $country = CountryHelper::getCountry((int) $filters['country']);
            // $conditions['and'] = "phone regexp '".$country['regex']."'";
            $conditions[] = [
                'and', 
                "countryCode = ".$country['code']
            ];
        }
        if (isset($filters['valid'])) {
            if ($filters['valid'] == 1) {
                $conditions[] = [
                    'and',  
                    "countryCode IS NOT NULL"
                ];
            } elseif ($filters['valid'] == 0) {
                $conditions[] = [
                    'and',  
                    "countryCode IS NULL"
                ];
            }
        }
        return $conditions;
    }

    protected function buildQuery(array $conditions): string
    {
        $query = '';
        $index = 0;
        foreach ($conditions as $condition) {
            $operator = $condition[0];
            $statment = $condition[1];
            if ($index == 0) {
                $operator = '';
            }
            $index++;
            if (is_array($statment)) {
                $query .= " $operator ". $this->buildQuery($statment);
                continue;
            }

            $query .= " $operator ($statment) ";
        }

        return $query;
    }

    /**
     * buildSelect
     *
     * @return string
     */
    protected function buildSelect(): string
    {
        $select = [
            'phone',
        ];
        $countries = CountryHelper::getCountries();
        $caseCondition = 'CASE ';
        foreach ($countries as $countryCode => $country) {
            $caseCondition .= " WHEN phone regexp '".$country['regex']."' THEN $countryCode";
        }
        $caseCondition .= ' END countryCode';
        $select[] = $caseCondition;

        return implode(',', $select);
    }
}