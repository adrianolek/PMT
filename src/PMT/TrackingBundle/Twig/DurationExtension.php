<?php
namespace PMT\TrackingBundle\Twig;

class DurationExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('duration', array($this, 'getDuration')),
        );
    }

    public function getDuration($seconds, $format = 'H:m:s')
    {
        $values = array();

        foreach (array('%h' => 3600, '%m' => 60, '%s' => 1) as $unit => $value) {
            $values[$unit] = floor($seconds/$value);
            $seconds -= $values[$unit]*$value;
            $values[strtoupper($unit)] = sprintf('%02d', $values[$unit]);
        }

        return str_replace(array_keys($values), $values, $format);
    }

    public function getName()
    {
        return 'duration';
    }
}
