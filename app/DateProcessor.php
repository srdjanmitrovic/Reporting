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
    public function validate()
    {
        foreach ($this->date as $key => $value) {
            $value = intval($value);
            switch ($key) {
                case ('day'):
                    if ($value == 0 || $value > 31)
                        $this->date[$key] = date('d');
                    break;
                case ('month'):
                    if ($value == 0 || $value > 12)
                        $this->date[$key] = date('m');
                    break;
            }
        }
    }

    /**
     * Process user provided date.
     *
     * @param  array $this->date
     * @param  array $useDate
     * @return array
     */
    public function process($date, $useDate)
    {
    	$this->date = $date;
    	if ($useDate == NULL) {
            $this->date['day']   = date('d');
            $this->date['month'] = date('m');
        }else{
        	$this->validate();
        }
        return $this->date;
    }

}