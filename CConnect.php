<?php
/**
 * @author Gulam Kabirulla
 * @copyright 2016
 */
?>
<?php
    session_start();
    class Connect{
        private $con;
        function __construct() {
            $this->con=new mysqli("RabaniMarket","root","","RabaniMarket");
            $this->con->set_charset("utf-8");
        }
        function getConnection()
        {
            return $this->con;
        }
    }
?>