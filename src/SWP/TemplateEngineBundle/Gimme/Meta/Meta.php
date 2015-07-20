<?php

namespace SWP\TemplateEngineBundle\Gimme\Meta;

use Symfony\Component\Yaml\Parser;

class Meta
{
    protected $configuration;

    protected $values;

    public function __construct($configuration, $values)
    {
        if (!is_readable($configuration)){
            throw new \InvalidArgumentException("Configuration file is not readable for parser");
        }

        $yaml = new Parser();
        $c = array_slice($yaml->parse(file_get_contents($configuration)), 0, 1);
        $this->configuration = array_shift($c);

        switch ($values) {
            case is_array($values):
                $this->values = $this->fillFromArray($values);
                break;

            case $this->isJson($values):
                $this->values = $this->fillFromJson($values);
                break;
        }
    }

    /**
     * Fill Meta from array. Array must have property names and keys.
     * 
     * @param array $values Array with propery names as keys
     *
     * @return bool
     */
    private function fillFromArray(array $values)
    {   
        $valuesArray = [];
        foreach ($values as $key => $propertyValue) {
            if (array_key_exists($key, $this->configuration['properties'])) {
                $this->$key = $propertyValue;
                $valuesArray[$key] = $propertyValue;
            }
        }

        return $valuesArray;
    }

    private function fillFromJson($values)
    {
        $arrayData = json_decode($values, true);
        $valuesArray = $this->fillFromArray($arrayData);

        return $valuesArray;
    }

    private function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function getExposedProperties()
    {
        $exposedProperties = [];
        foreach ($this->values as $key => $propertyValue) {
            if (array_key_exists($key, $this->configuration['properties'])) {
                $exposedProperties[$key] = $propertyValue;
            }
        }

        return $exposedProperties;
    }

    public function __debugInfo()
    {
        return $this->getExposedProperties();
    }    

    public function __toString()
    {
        if (array_key_exists('to_string', $this->configuration)) {
            $toStringProperty = $this->configuration['to_string'];

            if (isset($this->$toStringProperty)) {
                return $this->$toStringProperty;
            }
        }

        return json_encode($this->getExposedProperties());
    }
}