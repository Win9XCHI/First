<?php
require('FPDF/fpdf.php');

class PDF {
    private $pdf;
    private $flag = false;
    
    public function CreateFile() {
        $this->pdf = new FPDF();
        //$this->pdf->Open();
        $this->pdf->SetFont('Arial','',14);
        $this->flag = true;
        ChromePhp::log("CF");
    }
    
    public function CheckPDF() {
        ChromePhp::log("CPDF");
        return $this->flag;
    }
    
    public function NewList() {
        if ($this->CheckPDF()) {
            $this->pdf->AddPage();
        }
    }
    
    public function NewItem($w, $h, $str, $border, $ln, $align) {
        if ($this->CheckPDF()) {
            $this->pdf->Cell($w, $h, $str, $border, $ln, $align);
        }
    }
    
    public function Ln() {
        if ($this->CheckPDF()) {
            $this->pdf->Ln();
        }
    }
    
    //public function Output($data, $name) {
    public function Output() {
        if ($this->CheckPDF()) {
             return $this->pdf->Output();
        }
    }
   
    public function PdfFormation($ObjectBD, $Object) {
        ChromePhp::log("PF");
        //$MasPeople = json_decode($Object["MasPeople"]);
        
        for ($i = 0; $i < count($masNum); $i++) {
            $this->NewList();
            
            $this->NewItem(100, 6, "ПОСАДОЧНИЙ ДОКУМЕНТ - ", 0, 0, 'C');
            $this->NewItem(100, 6, "000B36C2-9012-97CB-0001", 0, 1, 'C');
            
            $this->NewItem(20, 20, "ТЕРМ. №9", 1, 0, 'C');
            $this->NewItem(100, 20, "ПАТ 'УКРЗАЛІЗНИЦЯ' м.Київ, вул.Тверська, буд.5", 1, 0, 'L');
            $this->NewItem(100, 20, "#Б5Я-Т1-0137824-1502", 1, 0, 'L');
            $this->NewItem(70, 20, "ПН:215793826073 ФН:148972168 ЗН:0000000001 ФК:000001218508", 1, 1, 'L');
            
            $this->NewItem(20, 20, "МПС", 1, 0, 'L');
            $this->NewItem(200, 20, "ЦЕЙ ПОСАДОЧНИЙ ДОКУМЕНТ Є ПІДСТАВОЮ ДЛЯ ПРОЇЗДУ", 1, 0, 'C');
            $this->NewItem(20, 20, $Object["Time"], 1, 1, 'R');
            
            $this->NewItem(50, 20, "Прізвище, Ім’я", 1, 0, 'L');
            $this->NewItem(50, 20, $MasPeople[$i + 1]['lastname']. " ". $MasPeople[$i + 1]['firstname'], 1, 0, 'L');
            $this->NewItem(20, 20, "Поїзд", 1, 0, 'L');
            $this->NewItem(70, 20, $Object["Num1"], 1, 1, 'R');
            
            $this->NewItem(25, 20, "Відправлення", 1, 0, 'L');
            if ($ObjectBD->SELECT("idStation, Name_S", "Station", "Name_S in ('". $Object["St_1"]. "', '". $Object["St_2"]. "')","", "")) {
                
            }
            $row = $ObjectBD->GetResult()->fetchArray();
            $this->NewItem(20, 20, $row['idStation'], 1, 0, 'L');
            $this->NewItem(50, 20, $Object["St_1"], 1, 0, 'L');
            $this->NewItem(20, 20, "Вагон", 1, 0, 'L');
            $this->NewItem(70, 20, $Object["NumberVagon"], 1, 1, 'R');
            
            $row = $ObjectBD->GetResult()->fetchArray();
            $this->NewItem(25, 20, "Призначення", 1, 0, 'L');
            $this->NewItem(20, 20, $row['idStation'], 1, 0, 'L');
            $this->NewItem(50, 20, $Object["St_2"], 1, 0, 'L');
            $this->NewItem(20, 20, "Місце", 1, 0, 'L');
            $this->NewItem(70, 20, $masNum[$i], 1, 1, 'R');
            
            $this->NewItem(45, 20, "Дата/час відпр.", 1, 0, 'L');
            $this->NewItem(50, 20, $Object["Time11"], 1, 0, 'L');
            $this->NewItem(20, 20, "Сервіс", 1, 0, 'L');
            $this->NewItem(70, 20, "", 1, 1, 'R'); //v
            
            $this->NewItem(45, 20, "Дата/час приб.", 1, 0, 'L');
            $this->NewItem(50, 20, $Object["Time12"], 1, 0, 'L');
            $this->NewItem(90, 20, "", 1, 1, 'L');
            
            $this->NewItem(185, 20, "ВАРТ=". $Object["Price"]. "ГРН", 1, 1, 'L');
            $this->NewItem(185, 20, "#СТР.ВІД Н/В 6000НЕОП.МІН. ТДВ СТ 'ДОМІНАНТА', КИІВ САКСАГАНСЬКОГО,119, T4928367", 1, 1, 'L');
            $this->NewItem(185, 20, "ЧАС ВІДПРАВЛЕННЯ КИЇВСЬКИЙ", 1, 1, 'L');
            
            $this->NewItem(185, 185, "Цей Посадочний документ є підставою для проїзду без звернення у касу.
Посадочний документ являється розрахунковим документом.
Посадка здійснюється за пред’явленням документа, який посвідчує особу.
Повернення даного Посадочного документа можливе до відправлення поїзда.", 1, 0, 'C');
        }
        ChromePhp::log("2PF");
        return $this->Output();
        //$this->Output("D", "tikcet.pdf");
        //return $this->Output();
        
    }
    
    
}
?>