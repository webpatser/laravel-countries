<?php

namespace Webpatser\Countries;

/**
 * CountryList
 *
 */
class Countries extends \Eloquent {

	/**
	 * @var string
	 * Path to the directory containing countries data.
	 */
	protected $countries;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
       $this->countries = json_decode(file_get_contents(__DIR__ . '/Models/countries.json'), true);
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
		return $this->countries[$id];
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
	    $countries = $this->countries;
	    
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
	        'sub-region-code');
	    
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