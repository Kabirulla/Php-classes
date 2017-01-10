<?php
/**
 * @author Gulam Kabirulla
 * @copyright 2016
 */
?>
<?php
    session_start();
    class Basket{
        private $con;
        function __construct($con) {
            $this->con=$con;
        }
        function GetUsersBasket()
        {                                    
            if($rowUser=$this->CheckForUser())
            {
                $res=$this->con->query("SELECT Goods.id AS id_goods, Goods.name AS name,Goods.price AS price, Basket.count AS count,Basket.id AS id_basket, GoodsImg.src AS src FROM Goods LEFT JOIN Basket ON Goods.id=Basket.id_goods LEFT JOIN GoodsImg ON Goods.id=GoodsImg.id_goods WHERE Basket.id_user='".$rowUser['id']."' AND Basket.action='0' ORDER BY Basket.id");
                $massive[]=null;
                for($i=0;$row=$res->fetch_assoc();$i++)
                {
                    $massive[$i]=$row;
                }
                return $massive;
            }
            return false;
        }
		function CheckForHashUser($hash)
        {
            $res=$this->con->query("SELECT* FROM Users WHERE hash='".mysqli_real_escape_string($this->con,$hash)."'");
            if($row=$res->fetch_assoc())
            {
                return true;
            }
            return false;
        }
        function CheckForUser()
        {
            $res=$this->con->query("SELECT id FROM Users WHERE hash='".mysqli_real_escape_string($this->con,$_COOKIE['usersHash'])."'");
            if($rowUser=$res->fetch_assoc())
            {
                return $rowUser;    
            }
            return false;
        }
        function AddToBasket($idGoods,$count)
        {
            $res=$this->con->query("SELECT id FROM goods WHERE id='".(int)$idGoods."'");            
            if((int)$idGoods<=0||(int)$count<=0||!($res->fetch_assoc()))
            {
                return "false";       
            }    	
            if($rowUser=$this->CheckForUser())
            {
                $res=$this->con->query("SELECT id FROM basket WHERE id_user='".(int)$rowUser['id']."' AND id_goods='".(int)$idGoods."' AND action='0'");
                if($rowCheck=$res->fetch_assoc())
                {
                    $this->con->query("UPDATE Basket SET count='".(int)$count."' WHERE id_user='".(int)$rowUser['id']."' AND id_goods='".(int)$idGoods."' AND action='0'");
                }
                else
                {
                    $this->con->query("INSERT INTO `Basket` SET id_user='".$rowUser['id']."',id_goods='".(int)$idGoods."',count='".(int)$count."'");  
                }  	
                return "true";
            }
			else
			{
				$musor='musor';
                $hash=md5(mt_rand(1,1000000)."musor");
                while($this->CheckForHashUser($hash))
                {
                    $hash=md5(mt_rand(1,1000000));
                }   
                $this->con->query("INSERT INTO Users(hash) VALUES('".mysqli_real_escape_string($this->con,$hash)."')");
                setcookie("usersHash",$hash);
				$idUser=$this->con->insert_id;
				$res=$this->con->query("SELECT id FROM basket WHERE id_user='".(int)idUser."' AND id_goods='".(int)$idGoods."' AND action='0'");
                if($rowCheck=$res->fetch_assoc())
                {
                    $this->con->query("UPDATE Basket SET count='".(int)$count."' WHERE id_user='".(int)$idUser."' AND id_goods='".(int)$idGoods."' AND action='0'");
                }
                else
                {
                    $this->con->query("INSERT INTO Basket(id_user,id_goods,count) VALUES('".$idUser."','".(int)$idGoods."','".(int)$count."')");  
                }
                return "true";
			} 	
            return "false";
        }
        function delGoodFromBasket($idGood)
        {      
            $rowUser=$this->CheckForUser();
            if($rowUser&&$idGood>0)
            {
                $this->con->query("DELETE FROM Basket WHERE id_user='".$rowUser['id']."' AND id_goods='".(int)$idGood."' AND action='0'");
                return "true";    
            }
            return "false";
        }
        function BuyGoods($name,$number,$adress)
        {                    
            $rowUser=$this->CheckForUser();
            if($rowUser&&trim($name)!=""&&trim($number)!=""&&trim($adress)!="")
            {
                $this->con->query("INSERT INTO ORDERS(adress) VALUES('".mysqli_real_escape_string($this->con,$adress)."')");
                $lastId=$this->con->insert_id;
                $massive=$this->GetUsersBasket();
                for($i=0;$massive[$i];$i++)
                {
                    $this->con->query("INSERT INTO OrdersList(id_basket,id_order) VALUES('".$massive[$i]['id_basket']."','".$lastId."')");
                }
                $this->con->query("UPDATE Basket SET action='1' WHERE id_user='".$rowUser['id']."' AND action='0'");
                $this->con->query("UPDATE Users SET name='".mysqli_real_escape_string($this->con,$name)."',number='".mysqli_real_escape_string($this->con,$number)."' WHERE id='".$rowUser['id']."'");
                return "true";    
            }
            return "false";
        }
    }
?>