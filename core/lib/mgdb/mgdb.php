<?php
/**
 * Created by PhpStorm.
 * User: muhittingulap
 * Date: 29.05.2017
 * Time: 17:40
 */
namespace MG\Db;

class mgdb
{
    private $sorgu;
    private $mgdb;
    private $errorno = 0;

    public function __construct($mgdb = array(), $v = '')
    {
        ini_set('memory_limit', '900M');

        if ($v != "" && count($mgdb) > 0) {
            $this->mgdb = $mgdb;
            $this->v = $v;
            $this->ls = 'server';

            $this->connect();
        } else {
            $this->error(1001, '', 1);
        }
    }

    private function connect()
    {
        try {
            $bg = 'mysql:host=' . $this->mgdb[$this->v][$this->ls]["host"] . ';dbname=' . $this->mgdb[$this->v][$this->ls]["db"];
            $this->db = new \PDO($bg, $this->mgdb[$this->v][$this->ls]["user"], $this->mgdb[$this->v][$this->ls]["pass"], $this->options);
            if ($this->mgdb[$this->v][$this->ls]["cs"]) {
                $this->db->exec("SET NAMES '" . $this->mgdb[$this->v][$this->ls]["cs"] . "';");
                $this->db->exec("SET CHARACTER SET " . $this->mgdb[$this->v][$this->ls]["csa"]);
            }
        } catch (PDOException $e) {
            $this->error($e->getCode(), $e->getMessage(), 1);
        }
    }

    public function mg_query($query, $data = array())
    {
        if ($query == "") return $this->error(1005, '', 1);

        $this->query($query);
        $this->arraybind($data);
        $this->execute();
    }

    public function mg_query_one($query, $data = array())
    {
        if ($query == "") return $this->error(1005, '', 1);

        $this->query($query);
        $this->arraybind($data);
        return $this->single();
    }

    public function mg_query_array($query, $data = array())
    {
        if ($query == "") return $this->error(1005, '', 1);

        $this->query($query);
        $this->arraybind($data);
        return $this->multi();
    }

    public function mg_dbinsert($m = array(), $table = "")
    {
        if (count($m) <= 0) return $this->error(1002, '', 1, $table);
        if ($table == "") return $this->error(1003, '', 1);

        if (count($m) > 0) {
            $this->query('INSERT INTO ' . $table . ' (' . implode(',', array_keys($m)) . ') VALUES (' . implode(',', array_map(array($this, 'mgbindinsert'), array_keys($m))) . ')');
            $this->arraybind($m);
            $this->execute();
            return $this->insert_id();
        } else {
            return $this->error(1002, '', 1, $table);
        }
    }

    /*  public function mg_dbclose()
    {
        $this->mgdb =null;
    }*/


    public function mg_dbinsert_multi($m = array(), $table = "")
    {

        if (count($m) <= 0) return $this->error(1002, '', 1, $table);
        if ($table == "") return $this->error(1003, '', 1);

        $this->start_transaction();
        $this->query('INSERT INTO ' . $table . ' (' . implode(',', array_keys($m[key($m)])) . ') VALUES (' . implode(',', array_map(array($this, 'mgbindinsert'), array_keys($m[key($m)]))) . ')');

        foreach ($m as $k => $v) {
            $this->arraybind($v);
            $this->execute();
            $mid[$k] = $this->insert_id();
        }

        $this->end_transaction();

        return $mid;
    }

    public function mg_dbupdate($m = array(), $table = "", $where = "", $sec = array())
    {
        if (count($m) <= 0) return $this->error(1002, '', 1, $table);
        if ($table == "") return $this->error(1003, '', 1);
        if ($where == "") return $this->error(1004, '', 1);

        $UP = $this->mgbindupdate($m);

        if (count($sec) > 0) {
            foreach ($sec as $k => $v) {
                $m[$k] = $v;
            }
        }

        $this->query("UPDATE " . $table . " SET " . $UP . " WHERE " . $where);
        $this->arraybind($m);
        $this->execute();
    }

    private function start_transaction()
    {
        return $this->db->beginTransaction();
    }

    private function cancel_transaction()
    {
        return $this->db->rollBack();
    }

    private function end_transaction()
    {
        return $this->db->commit();
    }


    private function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->sorgu->bindValue($param, $value, $type);
    }

    private function execute()
    {
        if ($this->errorno == 0) {
            try {
                $this->sorgu->execute();
            } catch (PDOException $e) {
                $this->error($e->getCode(), $e->getMessage(), 1);
            }
        }
    }

    public function rowCount()
    {
        return $this->sorgu->rowCount();
    }

    private function query($query)
    {
        if ($this->errorno == 0) {
            $this->sorgu = $this->db->prepare($query);
        }
    }

    private function mgbindinsert($v)
    {
        return (':' . $v);
    }

    private function mgbindupdate($m = array())
    {
        $UP = "";
        $i = 0;
        $DC = count($m);
        foreach ($m as $k => $v) {
            $i++;
            $UP .= $k . "= :" . $k . ($DC > $i ? "," : "");
        }
        return $UP;
    }

    private function arraybind($d = array())
    {
        if (count($d) > 0) {
            foreach ($d as $k => $v) {
                $this->bind(':' . $k, $v);
            }
        }
    }

    private function insert_id()
    {
        return $this->db->lastInsertId();
    }

    public function call($g = "")
    {
        return '';
    }

    private function single()
    {
        $this->execute();
        return $this->sorgu->fetch(\PDO::FETCH_ASSOC);
    }

    private function multi()
    {
        $this->execute();
        return $this->sorgu->fetchAll(\PDO::FETCH_ASSOC);
    }


    private function error($error_code, $error_message = "", $error_type = 0, $table = "")
    {
        $this->errorno = 1;
        $mes = $error_message != "" ? $error_message : $this->error_list($error_code, $table);

        $return = array(
            'status' => 0,
            'code' => $error_code,
            'message' => $mes,
        );

        if ($error_type > 0) {
            print '<div><span>ERROR ! : [' . $error_code . '] </span><span>' . $mes . '</span></div>';
            exit;
        } else {
            return $return;
        }
    }

    private function error_list($e = 0, $table = "")
    {
        $err = array(
            "1001" => 'Database Belirtilmedi.',
            "1002" => $table . ' -> [Array] Veri Girmelisiniz.',
            "1003" => 'Bir Tablo Adı Girmelisiniz.',
            "1004" => 'Bir Koşul Girmelisiniz.',
            "1005" => 'Bir Sorgu Girmelisiniz.',
        );

        if ($e == 0) {
            return $err;
        } else {
            return $err[$e];
        }
    }

}