<?php
/**
 * MYSQL class
 * @author:  anthony
 * @version: v1.0
 * ---------------------------------------------
 * $Date: 2013-09-18
 * $Id: comm_mysql.php
*/

class comm_mysql
{
	var $db_id;
	var $transOff = 0;
	var $transCnt = 0;
	var $_transOK = null;
	var $host_;
	var $user_;
	var $password_;
	var $dbname_;
	var $charset_ = "utf8";
	
	function __construct($host, $user, $password, $dbname, $charset='')
	{
		$this->host_ = $host;
		$this->user_ = $user;
		$this->password_ = $password;
		$this->dbname_ = $dbname;
		$this->charset_ = $charset;
        
		$this->connect();
	}

	function connect()
	{      
		$this->db_id = mysql_connect($this->host_, $this->user_, $this->password_);

		if ($this->db_id)
		{
			if (mysql_select_db($this->dbname_, $this->db_id))
			{
				if ($this->charset_) {
					$this->query("set names $this->charset_");
				}
				return true;
			}
			else
			{
				$this->db_message('mysql_select_db failed dbname is '.$this->dbname_);
			}
			
		}
		else
		{
			$this->db_message('mysql_connect failed host:' . $this->host_ );
		}
	}

	function db_message($msg)
	{
		die($msg);
	}
	
	function query($sql)
	{
		$this->ping();

		$rs = mysql_query($sql, $this->db_id);
		if ($rs)
        {
			return new rs_mysql($rs);
        }
		
		return false;
	}
	
	function execute($sql)	
	{
		return $this->query($sql);
	}
	
	function selectLimit($sql, $num, $start = 0)
    {
        if ($start)
        {
            $sql .= ' limit ' . $start . ', ' . $num;
        }
        else
        {
            $sql .= ' limit ' . $num;
        }

        return $this->query($sql);
    }
	
	function getOne($sql)
	{
		$rs = $this->query($sql);
		if (!$rs)
			return false;
		$row = $rs->fetchNumRow();
		return $row[0];
	}
	
	function getRow($sql)
	{
		$rs = $this->query($sql);
		if (!$rs)
		{
			return false;
		}
		$row = $rs->fetchAssoc();
		return $row;
	}
	
	function getAll($sql)
	{
		$rs = $this->query($sql);
		if (!$rs)
			return false;
		$data = array();
		while ($row = $rs->fetchAssoc())
		{
			$data[] = $row;
		}
		return $data;
	}
	
	function insert($data, $table)	
	{
		if (@!is_array($data))
			return false;
		foreach ($data as $k=>$v)
		{
			$arr_k[] = '`' . $k . '`';
			if (is_string($v))
				$arr_v[] = "'{$v}'";
			else
				$arr_v[] = $v;
		}
		$sql = "insert into `$table` (" . implode(",", $arr_k) . ") values (" . implode(",", $arr_v) . ")";
		return $this->query($sql);
	}
	
	function update($data, $table, $wq='')
	{
		if (@!is_array($data))
			return false;
		foreach ($data as $k=>$v)
		{
			if (is_string($v))
				$v = "'{$v}'";
			$arr[] = '`' . $k . '`' . '=' . $v;
		}

		$sql = "update `$table` set " . implode(",", $arr);
		if ($wq)
			$sql .= " where $wq";

		return $this->query($sql);
	}

	function StartTrans() {
		if ($this->transOff > 0) {
			$this->transOff += 1;
			return;
		}
		
		$this->_transOK = true;
		
		$this->_BeginTrans();
		$this->transOff = 1;
	}

	function _BeginTrans() {
		if ($this->transOff) return true;
		$this->transCnt += 1;

		$this->execute('SET AUTOCOMMIT=0');
		$this->execute('BEGIN');
		return true;
	}

	function FailTrans()
	{

		$this->_transOK = false;
	}

	function _RollbackTrans() {
		if ($this->transOff) return true;
		if ($this->transCnt) $this->transCnt -= 1;
		$this->execute('ROLLBACK');
		$this->execute('SET AUTOCOMMIT=1');
		return true;
	}

	function CompleteTrans($autoComplete = true)
	{
		if ($this->transOff > 1) {
			$this->transOff -= 1;
			return true;
		}
		
		$this->transOff = 0;
		if ($this->_transOK && $autoComplete) {
			if (!$this->_CommitTrans()) {
				$this->_transOK = false;			}
		} else {
			$this->_transOK = false;
			$this->_RollbackTrans();
		}
		
		return $this->_transOK;
	}

	function _CommitTrans($ok=true) {
		if ($this->transOff) return true; 
		if (!$ok) return $this->FailTrans();
		if ($this->transCnt) $this->transCnt -= 1;

		$this->execute('COMMIT');
		$this->execute('SET AUTOCOMMIT=1');
		return true;
	}


		
	function affected_rows()
	{
		return mysql_affected_rows($this->db_id);
	}
	
	function insert_id()
	{
		return mysql_insert_id($this->db_id);
	}
	
	function errorMsg()
	{
		return mysql_error($this->db_id);
	}

	function ping()
	{
		if(!mysql_ping($this->db_id))
		{
			mysql_close($this->db_id); 
			$this->connect();
		} 
	}

	function close()
	{
		return mysql_close($this->db_id);
	}
    
    function escape($input)
    {
        return mysql_escape_string($input);
    }
}

class rs_mysql
{
	var $res;
	function rs_mysql($rs)
	{
		$this->res = $rs;
	}
	
	function fetchNumRow()
	{
		return mysql_fetch_row($this->res);
	}
	
	function fetchRow()
	{
		return mysql_fetch_assoc($this->res);
	}
	
	function fetchAssoc()
	{
		return mysql_fetch_assoc($this->res);
	}
	
	function fetchArray()
	{
		return mysql_fetch_array($this->res);
	}
	
	function num_rows()
	{
		return mysql_num_rows($this->res);
	}
	
	function recordCount() {
		return mysql_num_rows($this->res);
	}
	
	function close()
	{
		return mysql_free_result($this->res);
	}
}

?>
