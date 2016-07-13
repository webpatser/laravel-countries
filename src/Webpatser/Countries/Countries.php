<?php

namespace Webpatser\Countries;

use Illuminate\Database\Eloquent\Model;

/**
 * CountryList
 *
 */
class Countries extends Model {

	/**
	 * @var string
	 * Path to the directory containing countries data.
	 */
	protected $countries;

	/**
	 * @var string
	 * The table for the countries in the database, is "countries" by default.
	 */
	protected $table;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
       $this->table = \Config::get('countries.table_name');
    }

    /**
     * Get the countries from the JSON file, if it hasn't already been loaded.
     *
     * @return array
     */
    protected function getCountries()
    {
        //Get the countries from the JSON file
        if (sizeof($this->countries) == 0){
            $this->countries = json_decode(file_get_contents(__DIR__ . '/Models/countries.json'), true);
        }

        //Return the countries
        return $this->countries;
    }

	/**
	 * Returns one country
	 *
	 * @param string $id The country id
     *
	 * @return array
	 */
	public function getOne($id)
	{
        $countries = $this->getCountries();
		return $countries[$id];
	}

	/**
	 * Returns a list of countries
	 *
	 * @param string sort
	 *
	 * @return array
	 */
	public function getList($sort = null)
	{
	    //Get the countries list
	    $countries = $this->getCountries();

	    //Sorting
	    $validSorts = array(
	        'capital',
	        'citizenship',
	        'country-code',
	        'currency',
	        'currency_code',
	        'currency_sub_unit',
	        'full_name',
	        'iso_3166_2',
	        'iso_3166_3',
	        'name',
	        'region-code',
	        'sub-region-code',
	        'eea',
	        'calling_code',
	        'currency_symbol',
	        'flag',
			'continent'
        );

	    if (!is_null($sort) && in_array($sort, $validSorts)){
	        uasort($countries, function($a, $b) use ($sort) {
	            if (!isset($a[$sort]) && !isset($b[$sort])){
	                return 0;
	            } elseif (!isset($a[$sort])){
	                return -1;
	            } elseif (!isset($b[$sort])){
	                return 1;
	            } else {
	                return strcasecmp($a[$sort], $b[$sort]);
	            }
	        });
	    }

	    //Return the countries
		return $countries;
	}
}
