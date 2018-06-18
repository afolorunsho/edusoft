<?php

namespace App\Http\Controllers\extend;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\models\Institution;

class PDFLogo extends Fpdf{

	function Header() {
		try{
			$item = Institution::first();
			$logo_image = $item->logo_image;
			
			if (count($item)> 0){
			    $path = storage_path('app/photo/institute/');
				$logo = $path.$logo_image;
				$this->Image($logo,15, 5, 25, 25, 'PNG'); //name of file, x, y, width, height, type, link
			}
		} catch (Exception $e) {
			file_put_contents('file_error.txt', $e->getMessage(). PHP_EOL, FILE_APPEND);
		}
  	}
  	function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(50,10,'Page '.$this->PageNo(),0,0,'L');
		$this->Cell(190, 10, 'Printed: '. date('l jS \of F Y h:i:s A') , 0, 0, 'R');
    }
}
?>