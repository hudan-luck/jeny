<?php
namespace sf\db;

use sf\db\ModelInterface;
use PDO;
use sf\db\Connection;
use sf\db\ConnectionSf;

class Model implements ModelInterface
{
	/**
	 * pdo 链接对象
     * obj 	
	 */	
	public static $pdo;
	
	/**
	 *
	 *
	 *
	 */
	public static function getDb()
	{
		if (empty(static::$pdo)) {
            static::$pdo = ConnectionSf::createObject('db')->getDb();
			static::$pdo->exec("set names 'utf8'");
			return static::$pdo;
		}	
	}

	/**
     *	表名
     *	
     */	
	public static function tableName()
	{
		return get_called_class();
	}
	
	public static function primaryKey()
	{
		return ['id'];
	}

	public static function findOne($condition)
	{
		/*$sql = "select * from " . static::tableName() . ' where ';

        $params = [];
        if (!$condition) {
            $params = array_values($condition);
            $keys = [];
            foreach ($condition as $key => $val) {
                array_push($keys , "$key = ?");
            }	
            $sql .= implode(' and ', $keys);
        }
		$stmt = static::getDb()->prepare($sql);
		$rs = $stmt->execute($params);
		if ($rs) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($row)) {
                // 创建相应model的实例
                $model = new static();
                foreach ($row as $rowKey => $rowValue) {
                    // 给model的属性赋值
                    $model->$rowKey = $rowValue;
                }
                return $model;
            }
		}
		return null;
        */
		list($where, $params) = static::buildWhere($condition);
		$sql = "select * from ". static::tableName() . $where;
		$stmt = static::getDb()->prepare($sql);
        $rs = $stmt->execute($params);
		if ($rs) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!empty($row)) {
				return static::arr2Model($row);
			}
		}
		return null;
		
	}

	public static function findAll($condition)
	{
		list($where, $params) = static::buildName($condition);
		$sql = "select * from ". static::tableName() . $where;
		$stmt = static::getDb()->prepare($sql);
		$rs = $stmt->execute($params);
		if ($rs) {
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (!empty($row)) {
				return static::arr2Model($row);
			}
		}
        return null;   
	}


	public static function updateAll($condition, $attributes)
	{
		$sql = "update " . static::tableName() ;
		$params = [];
		if (!empty($attributes)) {
			$sql .= ' set ';
			$params = array_values($attributes);
			$keys = [];
			foreach ($attributes as $key => $val) {
				array_push($keys, "$key = ?");
			}
			$sql .= implode(' , ', $keys);
		}
		list($where, $params) = static::buildWhere($condition, $params);
		$sql .= $where;
		$stmt = static::getDb()->prepare($sql);
		$rs = $stmt->execute($params);
		if ($rs) {
			$rowCount = $stmt->rowCount();
		}
		return $rowCount;
	}
	
	public static function deleteAll($condition)
	{
		$sql = "delete  from " . static::tableName() ;
		list($where, $params) = static::buildWhere($condition);
		$sql .= $where;
		$stmt = static::getDb()->prepare($sql);
		$rs = $stmt->execute($params);
		$rowCount = 0;
		if ($rs) {
			$rowCount = $stmt->rowCount();
		}
		return $rowCount;
	}
	
	public function insert()
	{
		$sql = "insert into ". static::tableName();
			$keys = [];
			$params = [];
			foreach ($this as $key => $val) {
				array_push($keys, $key);
				array_push($params, $val);	
			}
			$columns = array_fill(0, count($keys), ' ? ');
			$sql .= " ( ". implode(',', $keys) . " )  values ( ". implode(',', $columns) . " )";
			$stmt = static::getDb()->prepare($sql);
			$rs = $stmt->execute($params);
			if ($rs) {
				$rowCount = $stmt->rowCount();
			}
			return $rowCount;			
	}

	public function update()
	{
        $primaryKeys = static::primaryKey();
        $condition = [];    
		foreach ($primaryKeys as $name) {
            $condition[$name] = isset($this->$name) ? $this->$name : null;
		}
        $attributes = [];
        foreach ($this as $key => $value) {
            if(!in_array($key, $primaryKeys, true)) {
                $attributes[$key] = $value;
            }
        }
        return static::updateAll($condition, $attributs) !== false;
	}
	
	public function delete()
	{
        $primaryKeys = static::primaryKey();
        $condition = [];
        foreach ($primaryKeys as $name) {
            $condition[$name] = isset($this->$name) ? $this->$name : null;
        }
        return static::deleteAll($condition) !== false;
	}	

    public static function buildWhere($condition, $params = null)
    {
		if (is_null($params)) {
			$params =[];
		}
		if ($condition) {
			$where = ' where ';
			$keys = [];
			foreach ($condition as $key => $val) {
				array_push($keys , "$key = ?");
				array_push($params, $val);
			}
			$where .= implode(' and ', $keys);
		}
		return [$where, $params];
    }

	public static function arr2Model($row)
	{
		// 创建相应model的实例
		$model = new static();
		foreach ($row as $rowKey => $rowValue) {
			// 给model的属性赋值
			$model->$rowKey = $rowValue;
		}
		return $model;
	}
    
    public static function beginTransaction()
    {
        static::getDb()->beginTransaction();
    }
    
    public static function rollBack()
    {
        static::getDb()->rollBack();
    }   
		
}
