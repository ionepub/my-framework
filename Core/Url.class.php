<?php

	/**
	* url parse class
	*/
	class MyUrl
	{
		public static function getModule(){
			// if($url == ""){
            //     $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
            // }
            // var_dump($url);
            // var_dump($_SERVER['PHP_SELF']);

            $module = "";
            if(isset($_REQUEST['m']) && trim($_REQUEST['m'])!=""){
            	$module = trim($_REQUEST['m']);
            }
            if(!preg_match("/^[a-zA-Z]{1,}$/", $module)){
            	return self::getDefaultModule();
            }else{
            	return $module;
            }
		}

		public static function getAction(){
			$action = "";
            if(isset($_REQUEST['act']) && trim($_REQUEST['act'])!=""){
            	$action = trim($_REQUEST['act']);
            }
            if(!preg_match("/^[a-zA-Z]{1,}$/", $action)){
            	return self::getDefaultAction();
            }else{
            	return $action;
            }
		}

		private static function getDefaultModule(){
			if(!defined("DEFAULT_MODULE") || DEFAULT_MODULE==""){
				return "index";
			}else{
				return DEFAULT_MODULE;
			}
		}

		private static function getDefaultAction(){
			if(!defined("DEFAULT_ACTION") || DEFAULT_ACTION==""){
				return "index";
			}else{
				return DEFAULT_ACTION;
			}
		}
	}

?>