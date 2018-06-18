<?php

namespace App\Http\Controllers\extend;

use Codedge\Fpdf\Fpdf\Fpdf;
//use Codedge\Fpdf\Facades\Fpdf;
use App\models\Institution;

class PDFA4 extends Fpdf{
	var $widths;
	var $aligns;
	
	function Header() {
		try{
			$item = Institution::first();
			$institute_id = $item->institute_id;
			$sch_name = $item->sch_name;
			$motto = $item->motto;
			$reg_no = $item->reg_no;
			$phone = $item->phone;
			$email = $item->email;
			$website = $item->website;
			$country = $item->country;
			$region = $item->region;
			$address = $item->address;
			$reg_date = $item->reg_date;
			$logo_image = $item->logo_image;
			$photo_image = $item->photo_image;
			$header_image = $item->header_image;
			
			if (count($item)> 0){
			    $path = storage_path('app/photo/institute/');
				$logo = $path.$logo_image;
				$photo = $path.$photo_image;
				$header = $path.$header_image;
				//this is for the water-mark image, where found: the image should be faded using photoshop 
				$water_mark = $path.'water_mark.jpg';
				$this->Image($header,10, 5, 190, 35, ''); //name of file, x, y, width, height, type, link
				
				$this->SetXY(5,41);
				$this->Line(5, 41, 210-5, 41);
				if (file_exists($water_mark)) $this->Image($water_mark,25, 90, 145, 150, '');
			}
		} catch (Exception $e) {
			file_put_contents('file_error.txt', $e->getMessage(). PHP_EOL, FILE_APPEND);
		}
  	}
  	function Footer()
    {
         // Go to 1.5 cm from bottom
        $this->SetY(-15);
		$oldY = $this->getY();
        // Select Arial italic 8
        $this->SetFont('Arial','I',8);
        // Print centered page number
        //$this->Cell(50,10,'Page '.$this->PageNo(),0,0,'L');
		$this->Cell(190, 10, 'Printed: '. date('l jS \of F Y h:i:s A') , 0, 0, 'R');
		//$pdf->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		//$pdf->Cell(0, 5, "Page " . $pdf->PageNo() . "/{totalPages}", 0, 1);
		
		//$this->Line(5, $oldY, 210-5, $oldY); // 5mm from each edge
    }
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	function RowX($data)
	{
		$nb = 0;
		for ($i = 0; $i < count($data); $i++) { if (isset($this->styles[$i]))
		$this->SetFont('', $this->styles[$i]);
		$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		}
		$h = $this->FontSize * $nb + 2 * $this->vertical_padding; $this->CheckPageBreak($h);
		$x = $this->GetX();
		$y = $this->GetY();
		for ($i = 0; $i < count($data); $i++) {
		$w = $this->widths[$i];
		$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L'; if (isset($this->styles[$i]))
		$this->SetFont('', $this->styles[$i]); $this->Rect($x, $y, $w, $h);
		$this->SetXY($x, $y + $this->vertical_padding); $this->MultiCell($w, $this->FontSize, $data[$i], 0, $a); $x += $w;
		}
      	$this->SetY($y + $h);
  	}
}
/*
$width_header = array(10,45,25,60,20,15,15);
$set_align    = array('L','L','L','L','R','L','R');
$table_head   = array('No.','Kelompok','Kode Brg','Nama Barang','QTY','','Cek list');
$page_copy    = 0;
$fpdf->SetFont('Arial','B',11);

$lebar = $fpdf->w;

$fpdf->line($fpdf->GetX(), $fpdf->GetY(), $fpdf->GetX()+$lebar-18, $fpdf->GetY());
$fpdf->Ln(2);
$fpdf->SetWidths($width_header);
$fpdf->SetAligns($set_align);
$fpdf->SetFont('Arial','B',9);
for($i=1;$i<=1;$i++) {
    $fpdf->Row_Header($table_head);
}
$fpdf->line($fpdf->GetX(), $fpdf->GetY(), $fpdf->GetX()+$lebar-18, $fpdf->GetY());

$no        = 1;
$sub_total = 0;
$sql2 = $this->adm_entry_penjualan_model->getDataCetakDetil(" WHERE a.hps<>'*' AND a.no_bukti='$no_bukti_tmp' AND a.tgl='$tgl_tmp' ORDER BY b.acct_no, b.kode_sub");

$fpdf->SetFont('Arial','',10);

foreach ($sql2->result_array() as $key_no => $row2) {
        //Put the watermark
        $fpdf->SetFont('Arial','B',50);
        $fpdf->SetTextColor(255,222,233);
        $fpdf->RotatedText(90,90,'COPY',45);
        $fpdf->SetFont('Arial','',10);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->SetFont('Arial','',10);
    $fpdf->Row(
        array($no++,
        $row2['acct_name'],
        $row2['kode_sub'],
        $row2['nama_sub'],
        $row2['qty'].' ',
        $row2['satuan'],
        '...'
    ));

    $fpdf->line($fpdf->GetX(), $fpdf->GetY(), $fpdf->GetX()+$lebar-18, $fpdf->GetY());

}
*/
?>