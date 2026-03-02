<?php
class SinhVien extends Db
{
	function tatCa()
	{
		return $this->selectQuery("select book_id, book_name from book");
	}

	function daDiemDanh()
	{
		return $this->selectQuery("select * from v_dadiemdanh ");
	}
function chuaDiemDanh()
	{
		return $this->selectQuery("select * from v_chuadiemdanh ");
	}

function insertDiemDanh($mssv)
{
	return $this->updateQuery("INSERT INTO thongtindiemdanh (mssv, thoigianvao) VALUES ('$mssv', NOW() "); 
}

}