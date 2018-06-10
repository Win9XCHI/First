<?php
include ('TimeBase.php');

final class Route {
    
    private $id;
    private $StationStart;
    private $StationFinish;
    private $Num_Station;
    private $Time_1;
    private $Time_2;
    private $Type;
    private $ColCarriage;
    private $Station;

    public static $size_route; //кількість маршрутів
    
    public static function check() {
        $FLAG = false;

        if (self::$size_route >= 10) {
            $FLAG = true;
        }
        return $FLAG;
    }
    
    public function __construct() {
        $this->id = 0;
        $this->StationStart = "";
        $this->StationFinish = "";
        $this->Num_Station = 0;
        $this->ColCarriage = 0;
        $this->Time_1 = new TimeBase();
        $this->Time_2 = new TimeBase();
        $this->Station = array();
    }

    public function SetIdTrain($Train) {
        $this->id = $Train;
    }

    public function SetType($T) {
        $this->Type = $T;
    }

    public function GetIdTrain() {
        return $this->id;
    }

    public function SetSt1($St1) {
        $this->StationStart = $St1;
    }

    public function SetSt2($St2) {
        $this->StationFinish = $St2;
    }

    public function SetNumber_of_stations($num_s) {
        $this->Num_Station = $num_s;
    }

    public function SetTime($time, $time2) {
        $this->Time_1->SetTime($time);
        $this->Time_2->SetTime($time2);
    }

    public function Time_roat() { //час в дорозі
        return $this->Time_2->sub($this->Time_1);
    }

    public function TimeInt() { //час в дорозі
        return $this->Time_1->GetTimeInt();
    }

    public function Time_roatInt() { //час в дорозі
        $T = $this->Time_2->subTime($this->Time_1);
        return $T;
    }

    public function SetStation($num, $Coordinates, $Name) {
        $A["num"] = $num;
        $A["Coordinates"] = $Coordinates;
        $A["Name"] = $Name;
        array_push($this->Station, $A);
    }
    
    public function NewDat($dat) {
        $NewDat = "";
        $Day = "";
        $Month = "";
        $Year = "";
        $u = 0;
        $flag = false;
        for ($i = 0; $i < strlen($dat); $i++) {
            if ($i < 4) {
                $Year[$i] = $dat[$i];
            }
            if ($i > 4 && $i < 7) {
                $Month[$u] = $dat[$i];
                $u++;
            }
            if ($i == 7) {
                $u = 0;
            }
            if ($i > 7) {
                $Day[$u] = $dat[$i];
                $u++;
            }
        }

        if (($Day == "30" && $Month == "11") || ($Day == "30" && $Month == "09") || ($Day == "30" && $Month == "06") || ($Day == "30" && $Month == "04")) {
            $flag = true;
        }
        if (($Day == "31" && $Month == "01") || ($Day == "31" && $Month == "03") || ($Day == "31" && $Month == "05") || ($Day == "31" && $Month == "07") || ($Day == "31" && $Month == "08") || ($Day == "31" && $Month == "10") || ($Day == "31" && $Month == "12")) {
            $flag = true;
        }
        if ($Day == "28" && $Month == "02") {
                $flag = true;
        }

        if ($flag) {
            if ($Day == "31" && $Month == "12") {
                $NewDat = $Year;
                $numberI = $NewDat + 1;
                //$numberI++;
                $NewDat = "";
                $NewDat .= $numberI;
                $NewDat .= "01-01";

            }
            else {
                $NewDat = $Month;
                $numberI = $NewDat + 1;
                //$numberI++;
                $NewDat = "";
                if ($numberI < 10) {
                    $NewDat .= $Year. "-0". $numberI. "-01";
                }
                else {
                    $NewDat .= $Year. "-". $numberI. "-01";
                }
            }
        }
        else {
            $NewDat = $dat[strlen($dat) - 1];
            $numberI = $NewDat + 0;
            $numberI++;

            for ($i = 0; $i < strlen($dat) - 1; $i++) {
                $NewDat[$i] = $dat[$i];
            }
            $NewDat .= $numberI;
        }
        return $NewDat;
    }

    public function WriteRoute($St1, $St2, $dat) {
        $St3 = "";
        $fout = "";

        for ($i = 0; $i < strlen($St1); $i++) {

            if ($St1[$i] == '\'') {
                $i++;
            }
            $St3 .= $St1[$i];
        }
        $St1 = $St3;
        $St3 = "";

        for ($i = 0; $i < strlen($St2); $i++) {
            if ($St2[$i] == '\'') {
                $i++;
            }
            $St3 .= $St2[$i];
        }
        $St2 = $St3;

        //$fout = "\n$". $this->id. "|". $this->StationStart. "|". $this->StationFinish. "|". $this->Time_roat()->GetTime(). "|". $dat. " ". $this->Time_1->GetTime(). "|";
        $fout = "\n$". $this->id. "|". $this->StationStart. "|". $this->StationFinish. "|". $this->Time_roat(). "|". $dat. " ". $this->Time_1->GetTime(). "|";
        

        if ($this->Time_2->CheckData()) {
            $fout .= $this->NewDat($dat). " ";
        }
        else {
            $fout .= $dat. " ";
        }

        $numList = count($this->Station);
        $fout .= $this->Time_2->GetTime(). "|". "\n";
        $fout .= $numList. "|\n";

        for ($i = 1; $i <= $numList; $i++) {
            $A = array_pop($this->Station);

            if ($St1 == $A["Name"] || $St2 == $A["Name"]) {
                $fout .= "&". $A["Name"]. "|". $A["Coordinates"]. "|\n";
            }
            else {
                //fout << "|" << Station.popName(i).toStdString() << "|" << Station.popCoordinates(i).toStdString() << "|\n";
            }
        }
        
        ChromePhp::log($fout);
        
        return $fout;
    }
    
    public function  WriteRouteTrain() {
        $fout = "$". $this->id. "|". $this->StationStart. "|". $this->StationFinish. "";
        return $fout;
    }
    
    public function  WriteRouteSt() {
        $fout = "$". $this->id. "|". $this->StationStart. "|". $this->StationFinish. "|". $this->Type. "|". $this->Time_2->GetTime(). "|". $this->Time_1->GetTime();
        return $fout;
    }
    
    public function  WriteRouteMap($Object) {
        $flag = false;

        $St1 = $Object['St_1'];
        $St2 = $Object['St_2'];
        $St3 = "";

        for ($i = 0; $i < strlen($Object['St_1']); $i++) {

            if ($St1[$i] == '\'') {
               $i++;
            }
            $St3 .= $St1[$i];
         }
         $St1 = $St3;
         $St3 = "";

         for ($i = 0; $i < strlen($Object['St_2']); $i++) {
             if ($St2[$i] == '\'') {
                 $i++;
             }
             $St3 .= $St2[$i];
         }
         $St2 = $St3;

        $fout = "\n$";
        $numList = count($this->Station);
        $fout .= $numList. "|";

        for ($i = 1; $i <= $numList; $i++) {
            $A = array_pop($this->Station);

            if ($St1 == $A["Name"] || $St2 == $A["Name"]) {
                if ($flag) {
                    //$fout .= "@&". $A["Name"]. "*". $A["Coordinates"];
                    $fout .= "&". $A["Name"]. "*". $A["Coordinates"];
                }
                else {
                    $fout .= "&". $A["Name"]. "*". $A["Coordinates"];
                }
                $flag = true;
            }
            else {
                $fout .= "!". $A["Name"]. "|". $A["Coordinates"];
                $flag = false;
            }
        }
        
        //ChromePhp::log($fout);
        
        return $fout;
    }

    /*public function __assign_add($right) { // Overloads += (=)
        $this->id = $right->id;
        $this->StationStart = $right->StationStart;
        $this->StationFinish = $right->StationFinish;
        $this->Num_Station = $right->Num_Station;
        $this->Time_1 = $right->Time_1;
        $this->Time_2 = $right->Time_2;
        $this->Station = $right->Station;
        $this->Type = $right->Type;
        $this->ColCarriage = $right->ColCarriage;
        return $this;
    }*/
}

?>