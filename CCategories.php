<?php
/**
 * @author Gulam Kabirulla
 * @copyright 2016
 */
?>
<?php
    session_start();
    class Categories{
        private $con;
        function __construct($con) {
            $this->con=$con;
        }
        public function getAllCategory()
        {
            $res=$this->con->query("SELECT* FROM Categories");
            $massive[]=null;
            for($i=0;$row=$res->fetch_assoc();$i++)
            {
                $massive[$i]=$row;
            }
            return $massive;
        }
        function getCategory($id)
        {
            if((int)$id>0)
            {
                $res=$this->con->query("SELECT* FROM Categories WHERE id ='".(int)$id."'");
                if($row=$res->fetch_assoc())
                {
                    return $row;
                }
            }  
            return false;
        }
    }
?>