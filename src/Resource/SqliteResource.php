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
    protected $page = 1;

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
        $query .= $this->addFilters($filters);
        $query .= $this->addOffset();
        $result = $this->connection->query($query);
        return $this->generateList($result);
    }

    /**
     * count all phones after applying filters.
     *
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        $query = 'SELECT count(*) count FROM customer ';
        $query .= $this->addFilters($filters);
        $result = $this->connection->query($query);
        $result = $result->fetchArray();
        return $result['count'];
    }

    /**
     * generateList function
     *
     * @param [type] $result
     * @return array
     */
    protected function generateList($result): array
    {
        $rows = [];
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * addFilters
     *
     * @param array $filters
     * @return string
     */
    protected function addFilters(array $filters): string
    {
        $query = '';
        if (!empty($filters)) {
            $conditions = $this->buildConditions($filters);
            $conditionsQuery = $this->buildQuery($conditions);
            if ($conditionsQuery) {
                $query = 'WHERE '. $conditionsQuery;
            }
        }

        return $query;
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
            $conditions[] = [
                'and',
                "phone regexp '".$country['regex']."'"
            ];
        }
        if (isset($filters['valid'])) {
            $countries = CountryHelper::getCountries();
            foreach ($countries as $countryCode => $country) {
                $phoneConditions = [];
                if ($filters['valid'] == 1) {
                    $phoneConditions[] = [
                        'or',  
                        "phone regexp '".$country['regex']."'"
                    ];           
                } elseif ($filters['valid'] == 0) {
                    $phoneConditions[] = [
                        'and',  
                        "phone not regexp '".$country['regex']."'"
                    ];
                }
            }
            if (!empty($phoneConditions)) {
                $conditions[] = [
                    'and',  
                    $phoneConditions
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

    /**
     * addOffset
     *
     * @return string
     */
    protected function addOffset(): string
    {
        $limit = self::PER_PAGE;
        $offset = ($this->page - 1) * $limit;

        return " Limit $limit OFFSET $offset";
    }
}