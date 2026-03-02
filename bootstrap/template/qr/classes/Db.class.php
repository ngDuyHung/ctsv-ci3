<?php
class Db{
	var $conn;
	public function __construct()
	{
		$s="mysql:host=".HOST."; dbname=".DB;
		$this->conn = new PDO($s, USER, PASS);
		$this->conn->query("set names 'utf8' ");
	}

	protected function selectQuery($sql)
	{
		$data = $this->conn->query($sql);
		return $data->fetchAll(PDO::FETCH_ASSOC);
	}

	protected function updateQuery($sql)
	{
		$data = $this->conn->query($sql);
		return $data->rowCount();
	}
}
