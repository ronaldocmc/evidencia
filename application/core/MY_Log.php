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

    /**
     * Logfile Writting
     * 
     * Called by the global log_message()
     *
     * @param string $level
     * @param string $msg
     * @return boolean
     */
    public function write_log($level, $msg)
    {
        if ($this->_enabled === FALSE) {
            return FALSE;
        }

        $level = strtoupper($level);

        // Check if this is a valid log level
        if ((!isset($this->_levels[$level]) || ($this->_levels[$level] > $this->_threshold)) && !isset($this->_threshold_array[$this->_levels[$level]])) {
            return FALSE;
        }

        // write logfile with the name 'log-LEVEL-DATE.php
        $filepath = $this->_log_path . 'log-' . $level . '-' . date('Y-m-d') . '.' . $this->_file_ext;
        $message = '';

        if (!file_exists($filepath)) {
            $newfile = TRUE;
            // Only add protection to php files
            if ($this->_file_ext === 'php') {
                $message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
            }
        }

        if (!$fp = @fopen($filepath, 'ab')) {
            return FALSE;
        }

        flock($fp, LOCK_EX);

        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (strpos($this->_date_fmt, 'u') !== FALSE) {
            $microtime_full = microtime(TRUE);
            $microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
            $date = new DateTime(date('Y-m-d H:i:s.' . $microtime_short, $microtime_full));
            $date = $date->format($this->_date_fmt);
        } else {
            $date = date($this->_date_fmt);
        }

        $message .= $this->_format_line($level, $date, $msg);

        for ($written = 0, $length = self::strlen($message); $written < $length; $written += $result) {
            if (($result = fwrite($fp, self::substr($message, $written))) === FALSE) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === TRUE) {
            chmod($filepath, $this->_file_permissions);
        }

        return is_int($result);
    }
}
