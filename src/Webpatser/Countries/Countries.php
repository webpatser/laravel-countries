<?php

namespace Webpatser\Countries;

/**
 * CountryList
 *
 */
class Countries {

	/**
	 * @var string
	 * Path to the directory containing countries data.
	 */
	protected $countries;

    /**
     * Constructor.
     *
     * @param string|null $dataDir Path to the directory containing countries data
     */
    public function __construct()
    {
       $this->countries = json_decode(file_get_contents(__DIR__ . '/Models/countries.json'), true);
    }

	/**
	 * Returns one country
	 * 
	 * @param string $country The country
	 * @param string $locale The locale (default: en)
	 * @param string $format The format (default: php)
	 * @param string $source Data source: "icu" or "cldr"
	 * @return string
	 */
	public function getOne($id)
	{
		return $this->countries[$id];
	}

	/**
	 * Returns a list of countries
	 * 
	 * @param string $locale The locale (default: en)
	 * @param string $locale The format (default: php)
	 * @param string $source Data source: "icu" or "cldr"
	 * @return array
	 */
	public function getList()
	{
		return $this->countries;
	}
}