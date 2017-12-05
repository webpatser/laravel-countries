<?php

namespace Tests;

use Webpatser\Countries\Countries;

class CountriesTest extends BaseCase
{
    /**
     * Get content of the json file.
     *
     * @return array
     */
    private function content(): array
    {
        $file = __DIR__.'/../src/Webpatser/Countries/Models/countries.json';

        return json_decode(file_get_contents($file), true);
    }

    /**
     * @test
     */
    public function returns_content_of_the_json_file()
    {
        $countries = new class extends Countries
        {
            public function getCountriesFileContent()
            {
                return $this->getCountries();
            }
        };

        $this->assertEquals(
            $this->content(),
            $countries->getCountriesFileContent()
        );
    }

    /**
     * @test
     */
    public function returns_single_country_record()
    {
        $this->assertEquals(
            $this->content()[4],
            (new Countries)->getOne(4)
        );
    }

    /**
     * @test
     */
    public function returns_unsorted_list_of_countries()
    {
        $this->assertEquals(
            $this->content(),
            (new Countries)->getList()
        );
    }

    /**
     * @test
     */
    public function returns_sorted_list_of_countries()
    {
        $countries = new class extends Countries
        {
            protected function getCountries(): array
            {
                return [
                    1 => [
                        'capital' => 'London',
                        'currency_code' => 'GBP'
                    ],
                    2 => [
                        'capital' => 'Warsaw',
                        'currency_code' => 'PLN'
                    ],
                    3 => [
                        'capital' => 'Lisbon',
                        'currency_code' => 'EUR'
                    ],
                    4 => [
                        'capital' => 'Madrid',
                        'currency_code' => 'EUR'
                    ]
                ];
            }
        };

        $this->assertEquals(
            [
                3 => [
                    'capital' => 'Lisbon',
                    'currency_code' => 'EUR'
                ],
                1 => [
                    'capital' => 'London',
                    'currency_code' => 'GBP'
                ],
                4 => [
                    'capital' => 'Madrid',
                    'currency_code' => 'EUR'
                ],
                2 => [
                    'capital' => 'Warsaw',
                    'currency_code' => 'PLN'
                ]
            ],
            $countries->getList('capital')
        );
    }

    /**
     * @test
     */
    public function returns_list_for_select_form_element()
    {
        $countries = new class extends Countries
        {
            protected function getCountries(): array
            {
                return [
                    1 => [
                        'name' => 'United Kingdom'
                    ],
                    2 => [
                        'name' => 'Poland'
                    ],
                    3 => [
                        'name' => 'Portugal'
                    ],
                    4 => [
                        'name' => 'Spain'
                    ]
                ];
            }
        };

        $this->assertEquals(
            [
                2 => 'Poland',
                3 => 'Portugal',
                4 => 'Spain',
                1 => 'United Kingdom'
            ],
            $countries->getListForSelect('name')
        );
    }
}