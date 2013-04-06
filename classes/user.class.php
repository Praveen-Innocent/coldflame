<?php
// Authentication

class User extends Generic
{
    public $error = '';
   
    
    
    
    public function addUser($name, $email, $password, $phone,$type)
    {
		$actkey = $this->randomString(30);
        return mysql_query("insert into users (name,email,password,phone,type,actkey) values('$name','$email','" . md5($password) . "','$phone','$type','$actkey')");
    }
    
    
    
    
    public function IsExistsEmail($email)
    {
        $res = mysql_query("select * from users where email='" . $email . "';");
        return $res and mysql_num_rows($res) > 0;
    }
    
    
    
    public function registerUser($name, $email, $password, $tel,$type)
    {
        if (!$this->checkEMail($email))
            return 1;
        
        if ($this->IsExistsEmail($email))
            return 2;
        
        if (strlen($password) < 6)
            return 3;
        
        if (!$this->addUser($name, $email, $password, $tel,$type))
            return 4;
        
        
        
        $this->SendConfirmation($email);
        return 0;
    }
	
	
    
        public function SendConfirmation($email)
    {
        $res = mysql_query("select actkey from users where email='$email';");
        if (!$res or mysql_num_rows($res) == 0) return false;
        $field = mysql_fetch_assoc($res);
        if ($field['actkey'] == "") {
            //print "Already active.";
            return false;
        }
        
		$from = 'welcome@mysite.com';
		$to = $email;
		$subject = "Registration Confirmation";
		$message = "Hi there! \n
Thank you for signing up with mysite.com \n Please click on the link below to activate your registration. Activation code is " . $field['actkey'] . ".\n\nClick here to continue: activate.php?email=" . $email . "&actkey=" . $field['actkey'] . "\n\nLove, \nTeam mysite." ;
		
        if($this->sendEmail($from, $to, $subject, $message))
        return true;
    }
	
	
    public function isLoggedIn()
    {
        if (!isset($_SESSION['email']) or $_SESSION['email'] == "")
            return false;
        else
            return true;
    }
	
	 
    
    public function getApprovalStatus($id)
    {
        $result = mysql_query("SELECT * FROM  users WHERE id = '$id'");
        $result = mysql_fetch_assoc($result);
        return $result['approved'];
        ;
        
    }
    
    
    
    public function activateUser($email, $actkey)
    {
        $res = mysql_query("select * from users where actkey='$actkey' and `email` = '$email';");
        if ($res and mysql_num_rows($res) > 0) {
            $field = mysql_fetch_assoc($res);
            if ($field['active'] == 0) {
                mysql_query("update users set actkey='',active=1  where actkey='$actkey' and `email` = '$email';");
                return $field;
            } else
                return 0;
        }
    }
    
    public function changePassword($oldpwd, $newpwd)
    {
        $res = mysql_query("select id from users where username='" . $_SESSION['email'] . "' and password='" . md5($oldpwd) . "';");
        
        if ($res and mysql_num_rows($res) > 0) {
            if (mysql_query("update users set password='" . md5($newpwd) . "' where username='" . $_SESSION['email'] . "';"))
                return 1; //SUCCESS
            else
                return 3; // UPDATE ERROR
        } else
            return 2; // WRONG OLD PASSWORD
    }
    
    
    
    public function getUserEmail($id)
    {
        $res = mysql_query("select email from users where id=$id;");
        if ($res and mysql_num_rows($res) > 0) {
            $field = mysql_fetch_assoc($res);
            return $field['email'];
        }
    }
    
    
    public function ForcePasswordReset($email, $pwd)
    {
        return mysql_query("update users set password='" . md5($pwd) . "' where email='$email';");
    }
	
    
    
    

    
    public function Activated()
    {
        $res   = mysql_query("select active from users where id=" . $_SESSION['id']);
        $field = mysql_fetch_assoc($res);
        if ($field['active'] == "0" or $field['active'] == 0)
            return false;
        return true;
    }
    
    public function Login($email, $password)
    {
        $res = mysql_query("select * from users where email='$email' and password='" . md5($password) . "'");
        
        if ($res and mysql_num_rows($res) > 0) {
            $field = mysql_fetch_assoc($res);
            
            $_SESSION['id']    = $field['id'];
            $_SESSION['name']  = $field['name'];
            $_SESSION['email'] = $field['email'];
            $_SESSION['type']=$field['type'];
            
        }
        return ($res and mysql_num_rows($res) > 0);
    }
    
    
	function userHello()
	{
	print 'users hello';	
	}
    
    
}

$user = new User;
?>