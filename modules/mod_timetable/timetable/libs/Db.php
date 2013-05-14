<?php

class Db
{

	protected $_db_conn = null;

	protected static $_instance = null;

	protected $_resource = null;

	private function __construct()
	{
		$this->_db_conn = App::getInstance()->cn;
	}

	public function getNumRows($result)
	{
		return mysql_num_rows($result);
	}
	/**
	 * get instance of singleton
	 *
	 * @return Db
	 */
	public static function getInstance()
	{
		if( is_null(self::$_instance) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function escape($sStr)
	{
		return mysql_real_escape_string($sStr);
	}

	public function query($sStr, $bAutoFetch = false)
	{
		$this->_resource = mysql_query( $sStr, $this->_db_conn );
		
		if ( !$this->_resource && App::getInstance()->aConfig['Debug'] ) {
			Utils::deb("MySQL error: ".mysql_error().",\nQuery: ".$sStr);
		}
		
		if ( $bAutoFetch ) {
			return $this->fetchAssoc($this->_resource);
		} else {
			return $this->_resource;
		}
	}

	public function fetchAssoc($oRes = null)
	{
		if( empty($oRes) && ! empty($this->_resource) ) {
			$oRes = $this->_resource;
		}

		return mysql_fetch_assoc( $oRes );
	}
    
    public function getValue($sStr, $sFieldName)
    {
        $row = $this->query($sStr, true);
        return isset($row[$sFieldName]) ? $row[$sFieldName] : false; 
    }

    public function getLastError()
    {
        return mysql_error($this->_db_conn);
    }

    public function getInsertedId()
    {
        return mysql_insert_id($this->_db_conn);
    }
}