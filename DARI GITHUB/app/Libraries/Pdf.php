<?php 
namespace App\Libraries;

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf extends \TCPDF
{
  protected $kode_unik = '---';

    function __construct()
    {
      parent::__construct();      
    }

    function kode_unik($kode_unik=null){
      $this->kode_unik  = $kode_unik;
    }

    public function Footer() {      
	    // $image_file = '<img src="'.DOKUMEN_FISIK_PATH.'img/perizinan/bsre.png" width="87px" height="38px">';
      // $image_file = '<img src="'.base_url('public/bsre.png').'" width="87px" height="38px">';
      $image_file = '<img src="'.DOKUMEN_FISIK_PATH.'bsre.png'.'" width="87px" height="38px">';      

      // $image_file = $this->Image(
      //     DOKUMEN_FISIK_PATH.'bsre.png',
      //     10,
      //     10,
      //     30
      // );

	    $txt = "Dokumen ini ditandatangani secara elektronik dengan menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi 
Elektronik (BSRE) pada Badan Siber dan Sandi Negara (BSSN)
Masukkan kode ".$this->kode_unik." untuk mengecek keaslian dokumen pada link Qrcode";
      
						
	    // footer (text & image)
	    $this->SetY(-17);
	    $this->writeHTML($image_file, true, false, false, false, '');      
	    $this->SetY(-15);
	    $this->writeHTML("<hr>", true, false, false, false, '');
		  $this->SetY(-14);
	    $this->SetX(41);
	    $this->SetFont('helvetica', '', 8, '', false);
	    $this->MultiCell(185, 5, $txt, 0, 'L', 0, 1, '', '', true);                
	  }

    public function Header() {
          // get the current page break margin
          $bMargin = $this->getBreakMargin();
          // get current auto-page-break mode
          $auto_page_break = $this->AutoPageBreak;
          // disable auto-page-break
          $this->SetAutoPageBreak(false, 0);
          // set background image
          $img_file = base_url().'/public/dpmptsp.png';
          // $img_file = BASE_URL_PORT.'public/dpmptsp.png';

          $this->Image($img_file, -25, 0, $this->getPageWidth()+50, $this->getPageHeight(), '', '', '', true, 300,'',false,false,0);
          // set backround image
          // $watermarkpath = base_url().'/public/magelangkab2.png';

          // $watermarkpath = base_url('public/magelangkab2.png'); ASLI
          $watermarkpath = DOKUMEN_FISIK_PATH.'magelangkab2.png';          

          $ImageW = 75; //WaterMark Size
          $ImageH = 100;
          $myPageWidth = $this->getPageWidth();
          $myPageHeight = $this->getPageHeight();
          $myX = ($myPageWidth / 2) - 40;  //WaterMark Positioning
          $myY = ($myPageHeight / 2) - 55;
          
          $this->SetAlpha(0.1);
          $this->Image($watermarkpath, $myX, $myY, $ImageW, $ImageH, '', '', 'C', true, 300);
          $this->SetAlpha(1);
          // restore auto-page-break status
          $this->SetAutoPageBreak(true, $bMargin);
          // set the starting point for the page content
          $this->setPageMark();
    }
}
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */
?>