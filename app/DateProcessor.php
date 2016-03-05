<?php

namespace App;

class DateProcessor
{
	/**
	 * @param  Array $date
	 * @return Array $date
	 */
	public function validate($date)		
	{	
		foreach($date as $key=>$value){
			$value = intval($value);
			switch($key){
				case('day'):
					if($value == 0 || $value >= 31) $date[$key] = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->format('d');
					break;
				case('month'):
					$date[$key] = 02;
					// if($value == 0 || $value > 12) $date[$key] = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))->format('m');
					break;
			}
		}
		return $date;
	}

}