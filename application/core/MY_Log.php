<?php

/**
 * Custom Logging class
 * @package		Application
 * @subpackage	Core
 * @category	Logging
 * @author		Robson Cruz
 */
class MY_Log extends CI_Log
{
    /**
     * Constructor, appends out custom level of logging
     */
    public function __construct()
    {
        parent::__construct();
        $this->_levels['MONITORING'] = 5; // For our monitoring messages
    }
}
