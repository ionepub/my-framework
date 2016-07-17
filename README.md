# my-framework 一个简单的php mvc框架

框架使用smarty做模板引擎，支持mysql和mysqli

## 目录结构

├───app
│   ├───action
│   └───module
├───Core
├───data
│   └───cache
├───Extends
│   └───Smarty
│       ├───plugins
│       └───sysplugins
└───template
    └───Default

## 使用方法

### 1. 数据库连接

打开data目录下的config.php文件，即可配置数据库信息

```php

	//database config
	return array(
		'dbhost' 	=> 	'localhost',    //默认localhost
		'dbname' 	=>	'test',         //必填
		'username'	=>	'root',       //默认root
		'dbpassword'=>	'root',       //默认root
		'tablepre'	=>	'pre_',       //必填
		'dbcharset'	=>	'utf-8',      //默认utf-8
	);

```

    可以在项目入口文件index.php中设置data目录

### 2. 项目基本设置

    除了数据库连接设置，其他设置都在项目入口文件index.php中

```php
    define("DATA_PATH", "/data/"); // data目录，前面必须有/，必填

    define("APP_PATH", "/app/"); // 项目主目录，前面必须有/，默认app，可选

    define("TPL_PATH", "/template/"); //模板目录，前面必须有/，默认template，可选

    define("THEME", "Default"); //当前模板名，默认Default，可选

    define("DEFAULT_MODULE", "index");  //默认模型名，默认index，可选

    define("DEFAULT_ACTION", "index");  //默认控制器名，默认index，可选

    define("DB_MODE", "Mysql"); //数据库模式，mysql/mysqli，默认mysqli，可选

    define("DEBUG_MODE", true); //设置为true则显示所有错误，false则只显示自定义错误，默认false
```

### 3. 开始使用

3.1 引入框架文件

在入口文件底部添加如下代码即可：

```php

    include_once './Core/Core.class.php';
    
```

3.2 添加控制器

todo




