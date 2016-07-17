<?php
	
    define("DATA_PATH", "/data/"); // 前面必须有/

    define("APP_PATH", "/app/"); // 前面必须有/

    define("TPL_PATH", "/template/"); //模板目录

    define("THEME", "Default"); //当前模板

    define("DEFAULT_MODULE", "index");

    define("DEFAULT_ACTION", "index");

    define("DB_MODE", "Mysql"); //数据库模式，mysql/mysqli

    define("DEBUG_MODE", true); //设置为true则显示所有错误，false则只显示自定义错误

    include_once './Core/Core.class.php';
?>