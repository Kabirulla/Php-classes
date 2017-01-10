<?php
/**
 * @author Gulam Kabirulla
 * @copyright 2016
 */
?>
<?php
    session_start();
    class User{
        private $con;
        function __construct($con) {
            $this->con=$con;
            $this->CreateHash();
        }
		function CreateHash()
		{
			$hash="";
			if(!isset($_COOKIE['usersHash']))
            {
                $musor='gjrivnvbrweu';
                $hash=md5(mt_rand(1,1000000)."gjrivnvbrweu");
                while($this->CheckForHashUser($hash))
                {
                    $hash=md5(mt_rand(1,1000000));
                }
                //$ip = $_SERVER['REMOTE_ADDR'];   
                //$this->con->query("INSERT INTO Users(hash,ip) VALUES('".mysqli_real_escape_string($this->con,$hash)."','".mysqli_real_escape_string($this->con,$ip)."')");
				$this->con->query("INSERT INTO Users SET hash='".mysqli_real_escape_string($this->con,$hash)."'");
                setcookie("usersHash",$hash);
            }
            else
            {
               $hash=$_COOKIE['usersHash'];
                if(!$this->CheckForHashUser($hash))
                {
                    $hash=md5(mt_rand(1,1000000));
                    while($this->CheckForHashUser($hash))
                    {
                       $hash=md5(mt_rand(1,1000000));
                    }
                    //$ip = $_SERVER['REMOTE_ADDR'];
                    //$this->con->query("INSERT INTO Users(hash,ip) VALUES('".mysqli_real_escape_string($this->con,$hash)."','".mysqli_real_escape_string($this->con,$ip)."')");
					$this->con->query("INSERT INTO Users SET hash='".mysqli_real_escape_string($this->con,$hash)."'");
                    setcookie("usersHash",$hash);   
                }   
            }
			return $hash;
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
    }
?>