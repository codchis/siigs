<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_DB_mysql_driver extends CI_DB_mysql_driver {

    function __construct($params){
        parent::__construct($params);
        //log_message('debug', 'Extended DB driver class instantiated!');
    }

    
    /**
	 * Execute the query
	 *
	 * @access	private called by the base class
	 * @param	string	an SQL query
	 * @return	resource
	 */
	function _execute($sql)
	{
		$sql = $this->_prep_query($sql);
        $result = @mysql_query($sql, $this->conn_id);
                
        if(!$result)
            log_message('error', preg_replace("[\n|\r|\n\r]", ' ', $sql).' No. '. mysql_errno().': '.mysql_error());
        
		return $result;
	}

}

/* End of file mysql_driver.php */
/* Location: ./system/database/drivers/mysql/mysql_driver.php */