<?php
/**
 * CountryHelper
 * 
 * @package Phone
 */
namespace Phone\Helpers;

use Phone\Exceptions\CountryNotFoundException;

/**
 * CountryHelper
 * 
 * @package Phone
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */
class CountryHelper
{
    /* 
        Cameroon | Country code = +237 | regex = \(237\)\ ?[2368]\d{7,8}$
        Ethiopia | Country code = +251 | regex = \(251\)\ ?[1-59]\d{8}$
        Morocco | Country code = +212 | regex = \(212\)\ ?[5-9]\d{8}$
        Mozambique | Country code = +258 | regex = \(258\)\ ?[28]\d{7,8}$
        Uganda | Country code = +256 | regex = \(256\)\ ?\d{9}$

     */
    /**
     * array COUNTRIES
     */
    const COUNTRIES = [
        '237' => [
            'name'          =>  'Cameroon',
            'code'          =>  '237',
            'country_code'  =>  '+237',
            'regex'         =>  '\(237\)\ ?[2368]\d{7,8}$'
        ],
        '251' => [
            'name'          =>  'Ethiopia',
            'code'          =>  '251',
            'country_code'  =>  '+251',
            'regex'         =>  '\(251\)\ ?[1-59]\d{8}$'
        ],
        '212' => [
            'name'          =>  'Morocco',
            'code'          =>  '212',
            'country_code'  =>  '+212',
            'regex'         =>  '\(212\)\ ?[5-9]\d{8}$'
        ],
        '258' => [
            'name'          =>  'Mozambique',
            'code'          =>  '258',
            'country_code'  =>  '+258',
            'regex'         =>  '\(258\)\ ?[28]\d{7,8}$'
        ],
        '256' => [
            'name'          =>  'Uganda',
            'code'          =>  '256',
            'country_code'  =>  '+256',
            'regex'         =>  '\(256\)\ ?\d{9}$'
        ],
    ];

    /**
     * getCountries
     *
     * @return array
     */
    public static function getCountries(): array
    {
        return self::COUNTRIES;
    }


    /**
     * getCountry
     *
     * @param integer $code
     * @return array
     * @throws CountryNotFoundException
     */
    public static function getCountry(int $code): array
    {
        if (!empty(self::COUNTRIES[$code])) {
            return self::COUNTRIES[$code];
        }

        throw new CountryNotFoundException;
    }
}