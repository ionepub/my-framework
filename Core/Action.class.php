<?php

	/**
	* Base Action class
	*/
	class Action extends Module
	{
		private $moduleName; //存储当前模型

		private $smarty=null;

		private $config;

		function Action($config=array()){
			//init module
			parent::Module($config); 

			$this->config = $config;

			//init smarty
			if(file_exists(ROOT_PATH . "/Extends/Smarty/Smarty.class.php")){
				include_once ROOT_PATH . "/Extends/Smarty/Smarty.class.php";
				$this->smarty = new Smarty;
				$this->smarty->debugging = false;
				$this->smarty->caching = DEBUG_MODE ? false : true;
				$this->smarty->cache_lifetime = 120;
				$this->smarty->left_delimiter = "{";
				$this->smarty->right_delimiter = "}";

				if(!defined("TPL_PATH")){
					define("TPL_PATH", "/template/"); //模板目录
				}

				if(!defined("THEME")){
					define("THEME", "Default"); //当前模板
				}

				$this->smarty->template_dir = ROOT_PATH . TPL_PATH . THEME . "/";

				$this->smarty->compile_dir = ROOT_PATH . DATA_PATH . "/cache/";
			}
		}

		function m($module=''){
			$module = ucfirst(strtolower($module));
			if(file_exists(MODULE_PATH . "/" . $module . "Module.class.php")){
                // init module
                include_once MODULE_PATH . "/" . $module . "Module.class.php";
                if(class_exists($module."Module")){
                    $moduleName = $module."Module";
                    return new $moduleName($this->config);
                }
            }
            throw new MyException("Module or class ".$module."Module not found");
		}

		function display($tpl=''){
			if($tpl == ""){
				throw new MyException("Cannot found template file");
			}
			if(!$this->smarty){
				if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" .$tpl)){
					//默认模板后缀 html
					if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" . $tpl . ".html")){
						throw new MyException("Cannot found template file: ".$tpl);
					}else{
						include ROOT_PATH . TPL_PATH . THEME . "/" . $tpl . ".html";
					}
				}else{
					include ROOT_PATH . TPL_PATH . THEME . "/" . $tpl;
				}
			}else{
				if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" .$tpl)){
					//默认模板后缀 html
					if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" . $tpl . ".html")){
						throw new MyException("Cannot found template file: ".$tpl);
					}else{
						$this->smarty->display($tpl . ".html");
					}
				}else{
					$this->smarty->display($tpl);
				}
			}
		}

		function assign($name='', $value=''){
			if($name!="" && $this->smarty){
				$this->smarty->assign($name, $value);
			}
		}

		function fetch($tpl = ''){
			if($tpl==""){
				return "";
			}
			if($this->smarty){
				if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" .$tpl)){
					//默认模板后缀 html
					if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" . $tpl . ".html")){
						throw new MyException("Cannot found template file: ".$tpl);
					}else{
						return $this->smarty->fetch($tpl . ".html");
					}
				}else{
					return $this->smarty->fetch($tpl);
				}
			}else{
				if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" .$tpl)){
					//默认模板后缀 html
					if(!file_exists(ROOT_PATH . TPL_PATH . THEME . "/" . $tpl . ".html")){
						throw new MyException("Cannot found template file: ".$tpl);
					}else{
						return include ROOT_PATH . TPL_PATH . THEME . "/" . $tpl . ".html";
					}
				}else{
					return include ROOT_PATH . TPL_PATH . THEME . "/" . $tpl;
				}
			}
			return "";
		}

		/**
		 * 数据转换成接送输出
		 */
		function json($data=array()){
			//todo
		}
	}

?>