<?php
    if (php_sapi_name() != 'cli') die('Restricted');
    use Core\{DB, Helper};
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(__FILE__));

    // load configuration and helper functions
    require_once(ROOT.DS.'config'.DS.'config.php');
    $isCli = php_sapi_name() == 'cli';
    if(!RUN_MIGRATIONS_FROM_BROWSER && !$isCli) die('restricted');

    function autoload($className){
        $classAry = explode('\\', $className);
        $class = array_pop($classAry);
        $subPath = strtolower(implode(DS, $classAry));
        $path = ROOT . DS.$subPath.DS.$class.'.php';
        if(file_exists($path)){
            require_once($path);
        }
    }

    spl_autoload_register('autoload');

    $db = DB::getInstance();

    $migrationTable = $db->query("SHOW TABLES LIKE 'migrations'")->results();
    $previousMigs = [];
    $migrationsRun = [];

    if(!empty($migrationTable)){
        $query = $db->query("SELECT migration FROM migrations")->results();
        foreach($query as $q){
            $previousMigs[] = $q->migration;
        }
    }
    // get all files
    $migrations = glob('migrations'.DS.'*.php');

    foreach($migrations as $fileName){
        $klass = str_replace('migrations'.DS, '', $fileName);
        $klass = str_replace('.php', '', $klass);
        if(!in_array($klass, $previousMigs)){
            $klassNamespace = 'Migrations\\'.$klass;
            $mig = new $klassNamespace($isCli);
            $mig->up();
            $db->insert('migrations', ['migration' => $klass]);
            $migrationsRun[] = $klassNamespace;
        }
    }

    if(sizeof($migrationsRun) == 0) {
        if($isCli){
            echo "\e[0;37;42m\n\n"."    No new migrations to run.\n\e[0m\n";
        } else {
            echo '<p style="color:#006600;">No new migrations to run.</p>';
        }
    }