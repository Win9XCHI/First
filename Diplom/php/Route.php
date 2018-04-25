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
    //private $Type1, $Type2, $Type3, $Type4, $Type5, $Type6;
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
        $this->Station = new SplDoublyLinkedList();
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

    /*QString Route::GetSt1() {
        return StationStart;
    }

    QString Route::GetSt2() {
        return StationFinish;
    }*/

    public function SetNumber_of_stations($num_s) {
        $this->Num_Station = $num_s;
    }

    public function SetTime($time, $time2) {
        $this->Time_1->SetHH($time);
        $this->Time_1->SetMM($time);
        
        $this->Time_2->SetHH($time2);
        $this->Time_2->SetMM($time2);
    }

    public function Time_roat() { //час в дорозі
        return $this->Time_2 - $this->Time_1;
    }

    public function TimeInt() { //час в дорозі
        $T = $this->Time_1;

        $tt = $T->GetH();
        $tt .= $T->GetM();
        $ttt = "";

        for ($i = 0; $i < strlen($tt); $i++) {
            if ($tt[i] != '.') {
                $ttt .= $tt[i];
            }
        }

        return $ttt;
    }

    public function Time_roatInt() { //час в дорозі
        $T = $this->Time_2->sub($this->Time_1->GetH(), $this->Time_1->GetM());
        ChromePhp::log("t2:", $this->Time_2, "t1:",$this->Time_1, "t:",$T);
        $flag = false;

        $tt = $T->GetH();
        $tt .= $T->GetM();

        if (strlen($tt) < 3) {
            $flag = true;
        }

        $ttt = "";

        for ($i = 0; $i < strlen($tt); $i++) {
            if ($tt[i] != '.') {
                $ttt .= $tt[$i];
            }
        }

        if ($flag) {
            $ttt .= "0";
        }
ChromePhp::log($ttt);
        return $ttt;
    }

    /*public function SetIdStation_Train(int st) {
        idST = st;
    }

    public function SetIdCoordinates(QString coo) {
        Coordinates = coo;
    }

    public function SetSequence_number(int numS) {
        num = numS;
    }

    public function SetName_S(QString name) {
        Name = name;
    }*/

    public function SetStation($num, $Coordinates, $Name) {
        $A["num"] = $num;
        //$A["idST"] = $idST;
        $A["Coordinates"] = $Coordinates;
        $A["Name"] = $Name;
        $this->Station->push($A);
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

        $fout = "\n$". $this->id. "|". $this->StationStart. "|". $this->StationFinish. "|". $this->Time_roat()->GetTime(). "|". $dat. " ". $this->Time_1->GetTime(). "|";

        if ($this->Time_roat()->CheckData()) {
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
                    $numberI = $NewDat + 0;
                    $numberI++;
                    $NewDat = "";
                    $NewDat .= $numberI;
                    $NewDat .= "01-01";

                }
                else {
                    $NewDat = $Month;
                    $numberI = $NewDat + 0;
                    $numberI++;
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
                    $NewDat[i] = $dat[i];
                }
                $NewDat .= $numberI;
            }

            $fout .= $NewDat. " ";

        }
        else {
            $fout .= $dat. " ";
        }

        $numList = $this->Station->count;
        $fout .= $this->Time_2->GetTime(). "|". "\n";
        $fout .= $numList. "|\n";

        for ($i = 1; $i <= $numList; $i++) {
            $A = $this->Station->pop();

            if ($St1 == $A["Name"] || $St2 == $A["Name"]) {
                $fout .= "&". $A["Name"]. "|". $A["Coordinates"]. "|\n";
            }
            else {
                //fout << "|" << Station.popName(i).toStdString() << "|" << Station.popCoordinates(i).toStdString() << "|\n";
            }
        }
        
        return $fout;
    }
    /*public function  WriteRoute() {
        $fout = "$". $this->id. "|". $this->StationStart. "|". $this->StationFinish. "";
        return $fout;
    }*/
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
            $St3 .= St1[$i];
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
        $numList = $this->Station->count();
        $fout .= $numList. "|";

        for ($i = 1; $i <= $numList; $i++) {
            $A = $this->Station->pop();

            if ($St1 == $A["Name"] || $St2 == $A["Name"]) {
                if ($flag) {
                    $fout .= "@&". $A["Name"]. "*". $A["Coordinates"];
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
        return $fout;
    }

    public function __assign_add($right) { // Overloads += (=)
        $this->id = $right->id;
        $this->StationStart = $right->StationStart;
        $this->StationFinish = $right->StationFinish;
        $this->Num_Station = $right->Num_Station;
        $this->Time_1 = $right->Time_1;
        $this->Time_2 = $right->Time_2;
        $this->Station = $right->Station;
        $this->Type = $right->Type;
        $this->ColCarriage = $right->ColCarriage;
        /*$this->Type1 = $right->Type1;
        $this->Type2 = $right->Type2;
        $this->Type3 = $right->Type3;
        $this->Type4 = $right->Type4;
        $this->Type5 = $right->Type5;
        $this->Type6 = $right->Type6;*/
        return $this;
    }
}

?>