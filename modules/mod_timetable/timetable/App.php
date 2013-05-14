<?php

class App
{
    private static $_instance;
    public $aConfig = array();
    public $sRootPath;
    public $cn;

    private function __construct()
    {
        $this->aConfig = parse_ini_file('config.ini');
        if ( empty($this->aConfig) ) {
            die("Can't load the config file.");
        }

        $this->sRootPath = dirname(__FILE__);

        set_include_path(get_include_path() . PATH_SEPARATOR . $this->sRootPath);
        $this->sRootPath .= '/';

        require_once 'libs/Utils.php';
        require_once 'libs/Misc.php';
        require_once 'libs/Db.php';
        require_once 'libs/User.php';

        if ( $this->aConfig['Debug'] ) {
            Utils::enableDebug();
        } else {
            Utils::disableDebug();
        }

        //Интеграция с форумом (вкл/выкл)
        $this->aConfig['PhpBB'] = false;
    }

    /**
     * Позволяет обращаться к объекту без глобального $oApp
     *
     * @return App
     */
    public static function getInstance()
    {
        if( is_null(self::$_instance) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}

$oApp = App::getInstance();

?>