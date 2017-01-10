<?php
/**
 * @author Gulam Kabirulla
 * @copyright 2016
 */
?>
<?php
    session_start();
    class Admin{
        private $con;
        function __construct($con) {
            $this->con=$con;
        }
        function AdminSetCookie($login,$password)
        {
            if($this->CheckAdmin($login,md5($password)))
            {
                setcookie("adminLogin",$login,2147483647);
                setcookie("adminPassword",md5($password),2147483647);
                return true;
            }
            setcookie("adminLogin", "", time()-3600);
            setcookie("adminPassword", "", time()-3600);
            return false;
        }
        function CheckAdmin($login,$password)
        {
            $res=$this->con->query("SELECT* FROM AdminUsers WHERE login='".mysqli_real_escape_string($this->con,$login)."' AND password='".mysqli_real_escape_string($this->con,$password)."'");
            if($row=$res->fetch_assoc())
            {
                return true;
            }
            return false;
        }
        function getOrders($login,$password)
        {
            if($this->CheckAdmin($login,$password))
            {
                $res=$this->con->query("SELECT Users.name AS UName,Users.number AS UNumber,Users.id AS UId FROM Users LEFT JOIN Basket ON Basket.id_user=Users.id LEFT JOIN OrdersList ON OrdersList.id_basket=Basket.id LEFT JOIN Orders ON Orders.id=OrdersList.id_order WHERE Basket.action='1' GROUP BY (Orders.id)");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
        }
        function getFinishedOrders($login,$password)
        {
            if($this->CheckAdmin($login,$password))
            {
                $res=$this->con->query("SELECT Users.name AS UName,Users.number AS UNumber,Users.id AS UId FROM Users LEFT JOIN Basket ON Basket.id_user=Users.id LEFT JOIN OrdersList ON OrdersList.id_basket=Basket.id LEFT JOIN Orders ON Orders.id=OrdersList.id_order WHERE Basket.action='2' GROUP BY (Orders.id)");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
        }
        function getUsersOrders($login,$password,$idUser)
        {
            if($this->CheckAdmin($login,$password))
            {
                $res=$this->con->query("SELECT Users.name AS UName,Users.number AS UNumber,Users.id AS UId,Orders.adress AS OAdress,Orders.id AS OId FROM Users LEFT JOIN Basket ON Basket.id_user=Users.id LEFT JOIN OrdersList ON OrdersList.id_basket=Basket.id LEFT JOIN Orders ON Orders.id=OrdersList.id_order WHERE Basket.action='1' AND Users.id='".(int)$idUser."' GROUP BY (Orders.id) ORDER BY Orders.id DESC");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
        }
        function getUsersFinishedOrders($login,$password,$idUser)
        {
            if($this->CheckAdmin($login,$password))
            {
                $res=$this->con->query("SELECT Users.name AS UName,Users.number AS UNumber,Users.id AS UId,Orders.adress AS OAdress,Orders.id AS OId FROM Users LEFT JOIN Basket ON Basket.id_user=Users.id LEFT JOIN OrdersList ON OrdersList.id_basket=Basket.id LEFT JOIN Orders ON Orders.id=OrdersList.id_order WHERE Basket.action='2' AND Users.id='".(int)$idUser."' GROUP BY (Orders.id) ORDER BY Orders.id DESC");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
        }
        function getUsersOrder($login,$password,$idOrder)
        {
            if($this->CheckAdmin($login,$password))
            {
                $res=$this->con->query("SELECT Basket.id AS BId,Basket.count AS BCount,Goods.name AS GName,Goods.id AS GId,Goods.price AS GPrice,Users.name AS UName,Users.number AS UNumber,Users.id AS UId,Orders.adress AS OAdress,Orders.id AS OId FROM Users LEFT JOIN Basket ON Basket.id_user=Users.id LEFT JOIN Goods ON Goods.id=Basket.id_goods LEFT JOIN OrdersList ON OrdersList.id_basket=Basket.id LEFT JOIN Orders ON Orders.id=OrdersList.id_order WHERE Basket.action='1' AND Orders.id='".(int)$idOrder."' ORDER BY Orders.id DESC");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
            return false;
        }
        function getUsersFinishedOrder($login,$password,$idOrder)
        {
            if($this->CheckAdmin($login,$password))
            {
                $res=$this->con->query("SELECT Basket.id AS BId,Basket.count AS BCount,Goods.name AS GName,Goods.id AS GId,Goods.price AS GPrice,Users.name AS UName,Users.number AS UNumber,Users.id AS UId,Orders.adress AS OAdress,Orders.id AS OId FROM Users LEFT JOIN Basket ON Basket.id_user=Users.id LEFT JOIN Goods ON Goods.id=Basket.id_goods LEFT JOIN OrdersList ON OrdersList.id_basket=Basket.id LEFT JOIN Orders ON Orders.id=OrdersList.id_order WHERE Basket.action='2' AND Orders.id='".(int)$idOrder."' ORDER BY Orders.id DESC");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
            return false;
        }
        function CummintOrder($login,$password,$idOrder)
        {
            if($UsersOrderRow=$this->getUsersOrder($login,$password,$idOrder))
            {
                for($i=0;$UsersOrderRow[$i];$i++)
                {
                    $this->con->query("UPDATE Basket SET action='2' WHERE id='".(int)$UsersOrderRow[$i]['BId']."'");
                }
                return true;
            }
            return false;
        }
        function AddGood($login,$password,$nameG,$priceG,$categoryG,$imgG)
        {
            if($this->CheckAdmin($login,$password))
            {
                $this->con->query("INSERT INTO Goods(id_categories,name,price) VALUES('".(int)$categoryG."','".mysqli_real_escape_string($this->con,$nameG)."','".mysqli_real_escape_string($this->con,$priceG)."')");
                $lastId=$this->con->insert_id;
                $this->con->query("INSERT INTO GoodsImg(id_goods,is_main,src) VALUES('".(int)$lastId."','1','".mysqli_real_escape_string($this->con,$imgG)."')");
            }
        }
    }
?>