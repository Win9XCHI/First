<?php

class TimeBase {
    private $H;
    private $M;
    private $data;
    
    public function CheckData() {
        return $this->data;
    }
    
    public function SetH($time) {
        $this->H = $time;
        ChromePhp::log($this->H);
    }
    
    public function SetM($time) {
        $this->M = $time;
    }

    public function SetHH($time) {
        $m = "";

        for ($i = 0; $i < strlen($time); $i++) {
            if ($time[$i] == ":") {
                break;
            }
            else {
                $m[$i] = $time[$i];
            }
        }
        $this->H = $m;
    }
    public function SetMM($time) {
        $flag = false;
        $m = "";
        $j = 0;

        for ($i = 0; $i < strlen($time); $i++) {
            if ($time[$i] == ":") {
                $flag = true;
                $i++;
            }

            if ($flag) {
                $m[] = $time[$i];
                if ($i == 3) {
                    $m[] = '.';
                }
            }
        }
        $this->M = $m;
    }
    public function GetH() {
        return $this->H;
    }
    public function GetM() {
        return $this->M;
    }
    public function GetT() {
        $j = 0;
        $f = "";
        $flag = false;

        $s = $this->H;
        $s += ":";
        $s .= $this->M;

        for ($i = 0; $i < strlen($s); $i++) {
            if ($s[$i] == '.') {
                $flag = true;
            }
        }

        for ($i = 0; $i < strlen($s); $i++) {
            $s[$i] = s[$j];
            $j++;
            if ($s[$i] == '.') {
                $i--;
            }
        }

        if ($flag) {
            for ($i = 0; $i < strlen($s); $i++) {
                $f[$i] = $s[$i];
            }
        }
        else {
            for ($i = 0; $i < strlen($s); $i++) {
                $f[$i] = $s[$i];
            }
            $f .= "0";
        }

        return $f;
    }

    public function __construct() {
        $this->H = 0;
        $this->M = 0;
    }
    /*public function TimeBase($i, $n) {
        $this->H = $i;
        $this->M = $n;
    }
    public function TimeBase($n) {
        $flag = false;
        $m = "";
        $j = 0;

        for ($i = 0; $i < strlen($n); $i++) {
            if ($n[i] == ":") {
                $flag = true;
                $this->H = $m;
                $m = "";
            }

            if (!$flag) {
                $m[i] = $n[i];
            }
            else {
                $m[j] = $n[i];
                $j++;
            }
        }
        $this->M = m;
    }*/
    
    public function sub($hour, $min) { // Overloads - operator (Склей массив)
        $Ob = new TimeBase();
ChromePhp::log( (float) $min, (float) $this->GetM());
        if ($min > $this->GetM()) {
    ChromePhp::log(($hour * 1) - ($this->GetH() * 1));
            $Ob->SetH(abs($hour - $this->GetH()) - 1);
    ChromePhp::log($min - $this->GetM());
            $Ob->SetM($min - $this->GetM());
    ChromePhp::log($hour, $this->GetH());
        }
        else {
    ChromePhp::log(($hour * 1) - ($this->GetH() * 1));
            $Ob->SetH(abs($hour - $this->GetH()));
            $Ob->SetM($this->GetM() - $min);
    ChromePhp::log($hour, $this->GetH());
        }
ChromePhp::log($Ob);
        if ($hour > 12 && $this->GetH() < 12 ) {
            $Ob->SetH($this->GetH() - $hour + 24);
            $Ob->data = true;
        }
        
        ChromePhp::log($Ob);

        return $Ob;
    }
    public function assign_add($right) { // Overloads += (=)
        $this->H = $right->GetH();
        $this->M = $right->GetM();
        $this->data = $right->CheckData();
        return $this;
    }

}

?>