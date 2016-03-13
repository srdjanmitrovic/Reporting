<?php

namespace App;

/**
 * Used to generate the revenue statistics based on the relative metrics
 */
class DateProcessor
{

    /**
     * Date of transaction data.
     *
     * @var array
     */
	private $date;

    /**
     * Validate user provided date.
     *
     * @param  array
     */
    public function validate($date)
    {
        foreach ($date as $key => $value) 
        {
            $value = intval($value); 
            if ($key == 'day' && ($value == 0 || $value > 31)) $date[$key] = date('d');
            if ($key == 'day' && ($value == 0 || $value > 12)) $date[$key] = date('m');
        }
        return $date;
    }
}