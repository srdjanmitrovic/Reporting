<?php 

namespace App;

class Logger
{
	
	/**
     * Log an internal message to transaction_aggregation.log
     * @param  string $message
     * @return void
     */
    public function logMessage($message)
    {
        echo '[' . date('Y') . '-' . date('m') . '-' . date('d') . ' ' . date('H') . ':' .  date('i') . ':' . date('s') . '] - ' . $message . ". \n";
    }

}