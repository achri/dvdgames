<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 
class Lib_mail {
	private $CI,$harga_dvd,$beli_session,$email_admin,$email_selles;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('email');
		$this->CI->config->load('email');
		
		// private variable
		$this->beli_session = $this->CI->session->userdata('beli_session');
		$this->harga_dvd	= $this->CI->config->item('harga_dvd');
		
		// email
		$this->email_admin	= $this->CI->config->item('email_admin');
		$this->email_selles	= $this->CI->config->item('email_selles');
	}
	
	function mail_rincian()
	{
		// PREPARE CONTENT
		$data = "
		<HTML>
		<HEAD>
		<TITLE>DVDGAMES-ONLINE.COM RINCIAN PEMBELIAN</TITLE>
		</HEAD>
		<BODY>
		
		<table width='100%'>
			<tbody valign='top'>
			<tr>
				<td colspan = 3>
					BANK MANDIRI
				</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>
				<td width='5%'>Rekening</td><td width='1px'>:</td><td></td>
			</tr>
			<tr>
				<td>Nama</td><td>:</td><td></td>
			</tr>
		</table>
		<br/>
		";
		
		$SQLdvd = '
			select *
			from penjualan_detail as pjd
			inner join master_dvd as dvd on dvd.dvd_id = pjd.dvd_id
			inner join master_kategori as kat on kat.kat_id = pjd.kat_id
			inner join penjualan as pj on pj.jual_id = pjd.jual_id and pj.session_id = "'.$this->beli_session.'"
		';	
		
		$gdvd = $this->CI->db->query($SQLdvd);
		$no = 1;
		$total = 0;
		$data .= "
		<table width='100%'>
			<tbody valign='top'>
			<tr>
				<td colspan = 3>
					DATA RINCIAN DVD
				</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>
			<td colspan = 3>
			<table border=1 width='100%'>
			<tr>
				<td>No</td><td>Kategori</td><td>Game</td><td>DVD</td><td>Jml</td><td>Harga</td>
			</tr>
			";
		foreach ($gdvd->result() as $row):
			$data .= "
			<tr>	
				<td>".$no.".</td><td>".$row->kat_nama."</td><td>".$row->dvd_nama."</td><td>".$row->dvd_jumlah."</td><td>".$row->jumlah."</td><td>Rp.".number_format($row->harga,2)."</td>
			</tr>
			";
			$total += $row->harga;
			$no++;
		endforeach;
		$data .= "
			</table>
			</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>
				<td colspan=3 align='right'>Total : Rp. ".number_format($total,2)."</td>
			</tr>
			</tbody>
		</table><br>";
		
		$SQLjual = '
			select p.jual_no,p.email,p.tot_jumlah,p.tot_dvd,p.bonus,p.tot_harga,
			k.alamat,k.total_biaya,t.tarif_hrg,t.tarif_cas,tk.tiki_wilayah,
			tkp.paket_nama
			from pengiriman as k
			inner join master_tarif as t on t.tarif_id = k.tarif_id
			inner join master_tiki as tk on tk.tiki_id = t.tujuan
			inner join master_tiki_paket as tkp on tkp.paket_id = t.paket_id
			inner join penjualan as p on p.jual_id = k.jual_id and p.session_id = "'.$this->beli_session.'"
		';	
		
		$gjual = $this->CI->db->query($SQLjual);
		foreach ($gjual->result() as $row):
			$data .= "
			<table width='100%'>
			<tbody valign='top'>
			<tr>
				<td colspan = 3>
					DATA RINCIAN PEMBELIAN
				</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td width = '20px'>Nomor</td><td width='1px'>:</td><td>".$row->jual_no."</td>
			</tr>
			<tr>	
				<td>Email</td><td>:</td><td>".$row->email."</td>
			</tr>
			<tr>	
				<td>Alamat</td><td>:</td><td>".$row->alamat."</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td>Jumlah</td><td>:</td><td>".$row->tot_jumlah." item(s) = ".$row->tot_dvd." dvd</td>
			</tr>
			<tr>	
				<td>Potongan</td><td>:</td><td>".$row->bonus." dvd = Rp. ".number_format(($row->bonus != 0)?($row->bonus * $this->harga_dvd):(0),2)."</td>
			</tr>
			<tr>	
				<td>Harga</td><td>:</td><td>Rp. ".number_format($row->tot_harga,2)."</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td>Paket</td><td>:</td><td>".$row->paket_nama."</td>
			</tr>
			<tr>	
				<td>Wilayah</td><td>:</td><td>".$row->tiki_wilayah."</td>
			</tr>
			<tr>	
				<td>Biaya</td><td>:</td><td>Rp. ".number_format($row->tarif_hrg,2)."</td>
			</tr>
			<tr>	
				<td>Cas</td><td>:</td><td>Rp. ".number_format($row->tarif_cas,2)."</td>
			</tr>
			<tr>
				<td colspan = 3><hr></td>
			</tr>
			<tr>	
				<td>Total Biaya</td><td>:</td><td>Rp. ".number_format($row->total_biaya,2)."</td>
			</tr>
			<tr>
				<td colspan = 3>&nbsp;</td>
			</tr>
			</tbody>
			</table>
			
			Catatan : <br>
			- Setiap pembelian kelipatan 5 dvd mendapatkan potongan harga gratis 1 dvd. <br/>
			- Berapa rupiah dari total biaya diatas bertujuan untuk system mengkonfirmasi transaksi dan transfer anda. <br/>				
			- Transaksi yang belum dibayar akan dibatalkan secara otomatis setelah 5 hari oleh system. <br/>
			- Pengiriman barang akan dilayani langsung sebelum jam sibuk tiki (08:00 s/d 16:00) setelahnya dikirim esok hari. <br/>
			- Untuk tracking barang kunjungi ".anchor(site_url(),'DVDGAMES-ONLINE.COM')." <br/>
			";
			
			$email = $row->email;
		endforeach;
		
		$data .= "
		</BODY>
		</HTML>
		";
		
		// EMAIL CONTENT
		$this->CI->email->clear();
		$this->CI->email->from($this->email_admin, 'DVDGAMES-ONLINE.COM');
		$this->CI->email->to($email);
		$this->CI->email->cc($this->email_selles);
		//$this->CI->email->bcc('administrator@localhost');
		$this->CI->email->subject('Rincian pembelian DVD');
		$this->CI->email->set_alt_message('Untuk informasi lebih lanjut hubungi kami : cs@dvdgames-online.com');
		$this->CI->email->message(htmlspecialchars_decode($data));

		$this->CI->email->send();
		echo $this->CI->email->print_debugger();
		
	}
}