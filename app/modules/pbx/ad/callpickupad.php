<?php
namespace Pbx\Ad;

use \Forge\Config;
use \Illuminate\Database\Eloquent\Model as DB;

class CallpickupAD {

    /**
     * @var \PDO PDO instance.
     */
    private static $pdo;

    public static $response;

    /**
     * @var array Database options.
     */
    private $dbOptions;

    private static function setPDO() {
        if (is_null(self::$pdo)) {
            $conn = Config::get('database.default');
            self::$pdo = DB::resolveConnection($conn)->getPdo();
        }
    }
	
	 public static function get($callpickup_id, $customer_id) {
        try {
            self::setPDO();
            $sql = 'SELECT callpickup_id FROM tp_callpickup WHERE callpickup_id=:callpickup_id AND customer_id=:customer_id LIMIT 1';
            $qry = self::$pdo->prepare($sql);
            $qry->execute(array(':callpickup_id' => $callpickup_id, ':customer_id' => $customer_id));
            return $qry->fetch();

        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying get call pickup extension : %s', $e->getMessage()), 0, $e);
            return false;
        }
    }

    public static function delete($callpickup_id, $customer_id) {

        try {
            self::setPDO();
            $qry = self::$pdo->prepare("DELETE FROM tp_callpickup WHERE callpickup_id = :callpickup_id AND customer_id = :customer_id");
            $qry->execute(array($callpickup_id, $customer_id));
            $affected_rows = $qry->rowCount();
            return true;
        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying to delete call pickup : %s', $e->getMessage()), 0, $e);
            return false;
        }
        return false;
    }
}
