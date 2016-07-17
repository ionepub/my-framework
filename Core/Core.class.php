<?php

    /**
    * 核心类 core class
    */
    class Core
    {
        //database config
        private $config = array();

        private $module;

        private $action;

        function __construct()
        {
            if(!defined("DEBUG_MODE")){
                define("DEBUG_MODE", false);
            }
            if(DEBUG_MODE == true){
                error_reporting(E_ALL);
            }else{
                error_reporting(0);
            }

            define("CORE_PATH", dirname(__FILE__));
            define("ROOT_PATH", dirname(CORE_PATH));

            try {
                if(!file_exists(CORE_PATH . '/Exception.class.php')){
                    throw new Exception("Missing file Exception.class.php");
                }

                include_once CORE_PATH . '/Exception.class.php';

                if(!defined("DATA_PATH")){
                    throw new MyException("DATA_PATH not defined");
                }

                if(!file_exists(ROOT_PATH . DATA_PATH . "/config.php")){
                    throw new MyException("config.php not found");
                }

                //include config file
                $this->config = include_once ROOT_PATH . DATA_PATH . "/config.php";
                if(!is_array($this->config)){
                    throw new MyException("config.php must return an array");
                }

                //app path
                if(!defined("APP_PATH")){
                    define("APP_PATH", "/app/"); // 前面必须有/
                }

                if(APP_PATH == ""){
                    throw new MyException("Invalid app_path");
                }

                if(!file_exists(ROOT_PATH . APP_PATH)){
                    throw new MyException("Folder " . APP_PATH . " not found");
                }

                //define module and action path
                define("MODULE_PATH", ROOT_PATH . APP_PATH . "/module/");
                define("ACTION_PATH", ROOT_PATH . APP_PATH . "/action/");

                //module 不是必须的，但是action是必须的
                if(!file_exists(ACTION_PATH)){
                    throw new MyException("Action folder not found");
                }

                //base module class file
                if(!file_exists(CORE_PATH . "/Module.class.php")){
                    throw new MyException("Missing file Module.class.php");
                }

                //base action class file
                if(!file_exists(CORE_PATH . "/Action.class.php")){
                    throw new MyException("Missing file Action.class.php");
                }
                
                //init
                $this->init($this->config);

            } catch (MyException $e) {
                $e->error();
            } catch (Exception $e){
                echo "Error '".$e->getMessage()."' occurred on line ".$e->getLine()." in File ".$e->getFile();
                exit;
            }
        }

        private function init($config=array()){
            //init mysql class
            $this->init_module($config);
            
            // parse url
            $this->init_url();

            //base action 
            include_once CORE_PATH . "/Action.class.php";
            if(!class_exists("Action")){
                throw new MyException("Base class Action not found");
            }

            //action
            if(!file_exists(ACTION_PATH . "/" . $this->module . "Action.class.php")){
                throw new MyException("Unknown module file: ". $this->module . "Action.class.php");
            }

            include_once ACTION_PATH . "/" . $this->module . "Action.class.php";

            if(!class_exists($this->module . "Action")){
                throw new MyException("Class " . $this->module . "Action not found");
            }

            $actionClassName = $this->module . "Action";
            $actionHandller = new $actionClassName($config);

            // is_callable 判断方法是否可以被调用，private方法将返回false
            if(!is_callable(array($actionHandller, $this->action))){
                throw new MyException("Action ".$this->action." not found");
            }

            // call_user_func 将调用类中的方法
            call_user_func(array($actionHandller, $this->action));
        }

        /**
         * parse url and get module/action
         */
        private function init_url($url=""){
            if(!file_exists(CORE_PATH . '/Url.class.php')){
                throw new MyException("Missing file Url.class.php");
            }

            include_once CORE_PATH . '/Url.class.php';

            $this->module = ucfirst(strtolower(MyUrl::getModule()));

            $this->action = ucfirst(strtolower(MyUrl::getAction()));

            if($this->module == ""){
                throw new MyException("Unknown module name");
            }

            if($this->action == ""){
                throw new MyException("Unknown action name");
            }
        }

        /**
         * init mysql connection
         */
        private function init_module($config=array()){
            if(empty($config) || !is_array($config)){
                return false; //do not init mysql
            }
            
            // check connection params
            $dbname = isset($config['dbname']) && $config['dbname'] != "" ? $config['dbname'] : "";
            $tablepre = isset($config['tablepre']) && $config['tablepre'] != "" ? $config['tablepre'] : "";
            if($dbname=="" || $tablepre==""){
                return false; //do not init mysql
            }
            $dbhost = isset($config['dbhost']) && $config['dbhost'] != "" ? $config['dbhost'] : "localhost";
            $username = isset($config['username']) && $config['username'] != "" ? $config['username'] : "root";
            $dbpassword = isset($config['dbpassword']) && $config['dbpassword'] != "" ? $config['dbpassword'] : "root";
            $dbcharset = isset($config['dbcharset']) && $config['dbcharset'] != "" ? $config['dbcharset'] : "utf-8";

            if($dbcharset != "utf-8" && $dbcharset != "gb2312" && $dbcharset != "gbk"){
                throw new MyException("Unknown database charset");
            }

            $config = array(
                'dbhost'    =>  $dbhost,
                'dbname'    =>  $dbname,
                'username'  =>  $username,
                'dbpassword'=>  $dbpassword,
                'tablepre'  =>  $tablepre,
                'dbcharset' =>  $dbcharset,
            );

            //base module
            include_once CORE_PATH . "/Module.class.php";
            if(!class_exists("Module")){
                throw new MyException("Base class Module not found");
            }
        }
    }

    new Core();

?>