<?php 
class Generic {
	
	var $host = SERVER;
	var $user = USERNAME;
	var $pass = PASSWORD;
	var $db =   DB;
	
	function Generic() {
		$this->initSQL();
	}
	
	public function initSQL(){
		if($this->host!='' || $this->user!='' || $this->pass!='' || $this->db!='' ) {
		mysql_connect($this->host,$this->user,$this->pass);
		mysql_select_db($this->db) or die("Database Error: ".mysql_error());
		}
	}
	
	public function randomString($length){
		$valid_chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$random_string = "";
		$num_valid_chars = strlen($valid_chars);
		for ($i = 0; $i < $length; $i++){
			$random_pick = mt_rand(1, $num_valid_chars);
			$random_char = $valid_chars[$random_pick-1];
			$random_string .= $random_char;
		}
		return $random_string;
	}
	
	public function filter($data){
	if (get_magic_quotes_gpc ()) {
		$clean = mysql_real_escape_string (stripslashes ($data));	 
	}else{
		$clean = mysql_real_escape_string ($data);	
	} 
	return $clean;
	}


	public function cleanInput($value){
		if(is_array($value)){
			foreach($value as $key => $val){
				$value[$key] = cleanInput($val);
			}
			return $value;
		}else{
			return htmlspecialchars($value);
		}
	}
	
	public function cleanVars(){
		foreach ($_POST as $key => $value){
			$_POST[$key] = cleanInput($value);
		}
		foreach ($_GET as $key => $value){
			$_GET[$key] = cleanInput($value);
		}
	}
	
	
	
	
	public function cleanInput1($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }
  
	public   function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = mysql_real_escape_string (stripslashes($input));
        }
        $input  = cleanInput1($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
	
}





	
	

	
	public function sendmail($from,$fromname,$to,$subject,$body){
	return mail ( $to , $subject , $body, "From: $fromname");//, "From: $fromname <$from>" . "\r\n"
	}
	
	// Check email true or not; validation
	public function checkMail($email){
    if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))return true;
	else return false;
	}
	
	public function ip() {
	if (isset($_SERVER)) {	if(isset($_SERVER['HTTP_CLIENT_IP'])){
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
	$ip = $_SERVER['HTTP_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else{
	$ip = $_SERVER['REMOTE_ADDR'];
	}
	}
	else
	{
	if (getenv( 'HTTP_CLIENT_IP')) {
	$ip = getenv( 'HTTP_CLIENT_IP' );
	}
	elseif (getenv('HTTP_FORWARDED_FOR')) {
	$ip = getenv('HTTP_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR')) {
	$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	else {
	$ip = getenv('REMOTE_ADDR');
	}
	}
	return $ip;
	}
	
	
	
	public function sendEmail($to, $subj, $msg, $shortcodes = '', $bcc = false) {

		if ( !empty($shortcodes) && is_array($shortcodes) ) :

			foreach ($shortcodes as $code => $value)
				$msg = str_replace('{{'.$code.'}}', $value, $msg);

		endif;

		/* Multiple recepients? */
		if ( is_array( $to ) )
			$to = implode(', ', $to);

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . address . "\r\n";

		/* BCC address. */
		if ( $bcc ) {
			$headers .= 'Bcc: ' . $to . "\r\n";
			$to = null;
		}

		$headers .= 'Reply-To: ' . address . "\r\n";
		$headers .= 'Return-Path: ' . address . "\r\n";

		/*
		 * If running postfix, need a fifth parameter since Return-Path doesn't always work.
		 */
		// $optionalParams = '-r' . address;

		return mail($to, $subj, nl2br(html_entity_decode($msg)), $headers, $optionalParams);

	}


	public function secure($string) {

		// Because some servers still use magic quotes
		if ( get_magic_quotes_gpc() ) :

			if ( ! is_array($string) ) :
				$string = htmlspecialchars(stripslashes(trim($string)));
			else :
				foreach ($string as $key => $value) :
					$string[$key] = htmlspecialchars(stripslashes(trim($value)));
				endforeach;
			endif;

			return $string;

		endif;


		if ( ! is_array($string) ) :
			$string = htmlspecialchars(trim($string));
		else :
			foreach ($string as $key => $value) :
				$string[$key] = htmlspecialchars(trim($value));
			endforeach;
		endif;

		return $string;

	}
	
	// Check email true or not; validation
	public function checkEMail($email){
   if ( !empty($email) )
			$email = (string) $email;
		else
			return false;

		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	}

	
	public function SanitizeFile($filename)
	{
	$SafeFile = $filename;
	$SafeFile = str_replace("#", "No.", $SafeFile);
	$SafeFile = str_replace("$", "Dollar", $SafeFile);
	$SafeFile = str_replace("%", "Percent", $SafeFile);
	$SafeFile = str_replace("^", "", $SafeFile);
	$SafeFile = str_replace("&", "and", $SafeFile);
	$SafeFile = str_replace("*", "", $SafeFile);
	$SafeFile = str_replace("?", "", $SafeFile);
		
		return $SafeFile;
		}
		
		function helloWorld()
		{
			print "hello world!";
		}

	
}
$generic = new Generic;
?>