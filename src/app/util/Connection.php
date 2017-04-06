<?php



namespace app\util;

/**
 * Description of Connection
 *
 * @author vench
 */
class Connection {

    /**
     * 
     * @staticvar \PDO $conn
     * @return \PDO
     * @throws \PDOException
     * @todo конечно по хорошему тут нужен некий конфиг из контекста приложения
     */
    public static function getConn() {
        static $conn = null;
        if(is_null($conn)) {
            $app = \app\App::current();
            $config = $app->get('\app\AppConfig');
            $db = $config->getValue('db'); 
            
            $dsn = isset($db['dsn']) ? $db['dsn'] : 'mysql:dbname=testdb;host=127.0.0.1';
            $user = isset($db['user']) ? $db['user'] : 'root';
            $password = isset($db['password']) ? $db['password'] : 'admin';
 
            try {
                $conn = new \PDO($dsn, $user, $password, [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                    \PDO::ATTR_ERRMODE  => \PDO::ERRMODE_WARNING
                ]); 
            }   catch (\PDOException $e) { 
                //TODO add log
                throw $e;
            } 
        }
        return $conn;
    }
}
