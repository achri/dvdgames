<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 
class Lib_sweeping {
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function cek_expired()
	{
		/* STATUS
			Penjualan
				0 - keranjang
				1 - rincian pembayaran
				2 - rincian pengiriman
				3 - sudah kirim
				4 - batal
				5 - expired
			pengiriman
				0 - rincian pembayaran
				1 - rincian pengiriman
				2 - sudah dikirim
				3 - batal
		*/
		// SORTIR DATA PENJUALAN KEMARIN DAN SETERUSNYA YANG BERSTATUS 0
		$PSQL = "select jual_id,jual_tgl,status from penjualan where status != 3";
		$gjual = $this->CI->db->query($PSQL);		
		if ($gjual->num_rows() > 0):
			foreach ($gjual->result() as $rjual):
				// KONVERSI TANGGAL KE DETIK
				$jual_tgl = date_format(date_create($rjual->jual_tgl),'U');
				$sekarang = date('U');
				$kalkulasi = $sekarang - $jual_tgl;
				
				$jual_id = $rjual->jual_id;	// ID PENJUALAN
				
				if ($kalkulasi >= 86400 && $kalkulasi < 432000): // JIKA SUDAH 1 HARI UBAH PENJUALAN MENJADI EXPIRED
					if ($rjual->status == 0):
						// HAPUS DATA PENJUALAN DETAIL JIKA STATUS 0
						$UPSQL = "delete from penjualan where jual_id = $jual_id";
						$UPDSQL = "delete from penjualan_detail where jual_id = $jual_id";
						$this->CI->db->query($UPSQL);
						$this->CI->db->query($UPDSQL);
					else:
						// UBAH DATA PENJUALAN 
						$USQL = "update penjualan set session_id = '',status = 5 where jual_id = $jual_id and status in (1,2)";
						$this->CI->db->query($USQL);
					endif;
				elseif ($kalkulasi >= 432000):	// JIKA SUDAH LEBIH 5 HARI HAPUS DATA PENJUALAN STATUS 1,2,4 dan 5
					// HAPUS DATA PENGIRIMAN
					$HKSQL = "delete from pengiriman where jual_id = $jual_id";
					$this->CI->db->query($HKSQL);
					$HPSQL = "delete from penjualan_detail where jual_id = $jual_id";
					$this->CI->db->query($HPSQL);
					$HPDSQL = "delete from penjualan where jual_id = $jual_id";
					$this->CI->db->query($HPDSQL);
				endif;
				
			endforeach;
		endif;
	}
	
}