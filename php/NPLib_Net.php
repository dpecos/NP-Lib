<?php
/** 
 * NPLib - PHP
 * 
 * Network related functions
 * 
 * @package np-lib
 * @subpackage 
 * @version 20090624
 * 
 * @author Daniel Pecos Mart�nez
 * @copyright Copyright (c) Daniel Pecos Mart�nez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
require_once("NPLib_Common.php");
require_once("mail/htmlMimeMail.php");

function NP_redirect($page) {
   if (isset($_ENV['HTTP_HOST']) && isset($_ENV["SCRIPT_URL"])) {
      $host  = $_ENV['HTTP_HOST'];
      if (NP_endsWith(".php", $_ENV["SCRIPT_URL"]))
         $uri  = rtrim(dirname($_ENV["SCRIPT_URL"]), '/\\');
      else
         $uri  = rtrim($_ENV["SCRIPT_URL"], '/\\');
   } else {
      $host  = $_SERVER['HTTP_HOST'];
      if (NP_endsWith(".php", $_SERVER["SCRIPT_NAME"]))
         $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
      else
         $uri  = rtrim($_SERVER['PHP_SELF'], '/\\');
   }
	header("Location: http://$host$uri/$page");
	exit;
}

/**
 * @deprecated Use NP_sendMail instead
 */
function sendMail($from, $to, $subject, $body) {
   return NP_sendMail($from, $to, $subject, $body);
}
function NP_sendMail($from, $to, $subject, $body) {
    $mail = new htmlMimeMail();
    $mail->setFrom($from);
    $mail->setSubject($subject);
    $mail->setText($body);
    if (is_array($to))
        $result = $mail->send($to);
    else
        $result = $mail->send(array($to));

    return $result;
}

/**
 * @deprecated Use NP_sendHTMLMail instead
 */
function sendHTMLMail($from, $to, $subject, $body) {
   return NP_sendHTMLMail($from, $to, $subject, $body);
}
function NP_sendHTMLMail($from, $to, $subject, $body) {
    $mail = new htmlMimeMail();
    $mail->setFrom($from);
    $mail->setSubject($subject);
    $mail->setHTML($body);
    if (is_array($to))
        $result = $mail->send($to);
    else
        $result = $mail->send(array($to));

    return $result;
}

function NP_get_referer() {
    if (isset($_SERVER['HTTP_REFERER']))
        $referer = $_SERVER['HTTP_REFERER'];
    else if (isset($_ENV['HTTP_REFERER']))
        $referer = $_ENV['HTTP_REFERER'];
    else 
        return null;
	$referer = split("/", $referer);
	$referer = $referer[sizeof($referer)-1];
	$index = strpos($referer, "?");
	if ($index > 0) {
		$referer = substr($referer, 0, $index);
	}
	return $referer;
}

function NP_url_decode($obj) {
        if (gettype($obj) == "array") {
                foreach ($obj as $k => $v) {
                        $obj[$k] = NP_url_decode($v);
                }
        } else if (gettype($obj) == "object") {
                foreach (get_object_vars($obj) as $k => $v) {
                        $obj->$k = NP_url_decode($v);
                }
        }
        if (gettype($obj) == "string") {
                return urldecode($obj);
        } else {
                return $obj;
        }
}

function NP_json_encode($obj) {
	if (gettype($obj) == "object")
		if (version_compare(phpversion(), '5.0') < 0)
			$obj = $obj;
		else
			$obj = clone($obj);
				
	if (function_exists("json_encode")) {
		return json_encode(NP_UTF8_encode($obj));
	} else {
		require_once 'Zend/Json.php';
		return Zend_Json::encode(NP_UTF8_encode($obj));
	}
}

?>