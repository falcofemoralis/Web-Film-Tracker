<?php

class Country{
    private string $country_id, $country;

    /**
     * Country constructor.
     * @param string $country_id
     * @param string $country
     */
    public function __construct(string $country_id, string $country)
    {
        $this->country_id = $country_id;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->country_id;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}