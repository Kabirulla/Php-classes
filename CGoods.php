<?php
/**
 * @author Gulam Kabirulla
 * @copyright 2016
 */
?>
<?php
    session_start();
    class Goods{
        private $con;
        function __construct($con) {
            $this->con=$con;
        }
        function getGoodsById($idGoods)
        {
            $res=$this->con->query("SELECT* FROM Goods WHERE id='".(int)$idGoods."'");
            $row=$res->fetch_assoc();
            return $row;
        }
        function getGoodsByCategory($idCategories)
        {
            $res=$this->con->query("SELECT* FROM Goods WHERE id_categories='".(int)$idCategories."'");
            $massive[]=null;
            for($i=0;$row=$res->fetch_assoc();$i++)
            {
                $massive[$i]=$row;
            }
            return $massive;
        }   
        function getGoodsByCategoryAndName($idCategories,$name)
        {
            $res=$this->con->query("SELECT* FROM Goods WHERE id_categories='".(int)$idCategories."' AND name LIKE '%".mysqli_real_escape_string($this->con,$name)."%'");
            $massive[]=null;
            for($i=0;$row=$res->fetch_assoc();$i++)
            {
                $massive[$i]=$row;
            }
            return $massive;
        }
        function getGoodsByName($name)
        {
            $res=$this->con->query("SELECT* FROM Goods WHERE name LIKE '%".mysqli_real_escape_string($this->con,$name)."%'");
            $massive[]=null;
            for($i=0;$row=$res->fetch_assoc();$i++)
            {
                $massive[$i]=$row;
            }
            return $massive;
        }
        function getGoodsMainImg($idGoods)
        {
            $res=$this->con->query("SELECT src FROM GoodsImg WHERE id_goods='".(int)$idGoods."' AND is_main='1'");
            if($row=$res->fetch_assoc())
            {
                return $row['src'];
            }
        }
        function getGoodsInfo($idGoods)
        {
            $res=$this->con->query("SELECT* FROM Goods WHERE id='".(int)$idGoods."'");
            if($row=$res->fetch_assoc())
            {
                return $row;
            }
        }
    }
?>