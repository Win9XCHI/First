<?php

class TimeBase {
    private $T;
    private $data = false;
    
    public function CheckData() {
        return $this->data;
    }    
    
    public function SetTime($time) {
        $this->T = new DateTime($time);
    }
    
    public function sub($time) {
        $TT = $this->T->diff($time->GetTimeT());
        
        $cont1 = $this->T->format('H.i');
        $cont2 = $time->GetTimeT()->format('H.i');
        
        ChromePhp::log("sub", $cont1, $cont2);
        
        if($cont1 < $cont2) {
            $this->data = true;
            $t1 = explode('.', $cont1);
            $t2 = explode('.', $cont2);
            
            $t1 = $t1[0] * 60 + $t1[1];
            $t2 = $t2[0] * 60 + $t2[1];            
            $h = (1440 - $t2 + $t1);            
            $min = $h / 60;            
            $min = explode('.', $min);            
            $t1 = $min[0];            
            $min = $h - ($min[0] * 60);            
            $h = $t1;
            
            if (empty($min)) {
                $TT = $h. ":00";
            }
            else {
                if ($min < 10) {
                    $TT = $h. ":0". $min;
                }
                else {
                    $TT = $h. ":". $min;
                }
                
            }
        }
        else {
            if ($TT->format('%i') < 10) {
                if ($TT->format('%i') == "") {
                    $TT = $TT->format('%H:00');
                } else {
                    $TT = $TT->format('%H:0%i');
                }
            }
            else {
                $TT = $TT->format('%H:%i');
            }
        }
            
        return $TT;
        //return date("H:i", $TT);
    }
    
    public function subTime($time) {
        $cont1 = $this->T->format('Hi');
        $cont2 = $time->GetTimeT()->format('Hi');
        
        ChromePhp::log("subTime", $cont1, $cont2);
        if($cont1 < $cont2) {
            $this->data = true;
        }
        
        $TT = $this->T->diff($time->GetTimeT());

        $cont = $TT->format('%H%i');
            
        return $cont;
        //return abs($this->T->diff($time->GetTimeT()));
    }
    
    public function add($time) {
        $New = new DateTime($time);        
        $this->T->add(new DateInterval("PT". $New->format('H'). "H". $New->format('i'). "M"));
    }
    
    public function GetTimeInt() {
        
        $TT = $this->T->format('Hi');
        
        return $TT;
    }
    
    public function GetTime() {
        return $this->T->format('H:i');
        //return date("H:i", $this->T);
    }
    
    private function GetTimeT() {
        return $this->T;
        //return date("H:i", $this->T);
    }
}

?>