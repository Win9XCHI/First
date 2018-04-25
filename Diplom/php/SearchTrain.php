<?php
include ('ProjectBD.php');
include ('Route.php');

final class SearchTrain {
    private $Object;
    private $Door;
    private $FlagV;

    private function StationCheck($ObjectBD) {
        $u = 0;
        $StationInput = null;

        if ($ObjectBD->SELECT("Name_S, Branch", "Station", "Name_S LIKE '%". $this->Object["value"]. "%'","", "")) {
            
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                
                $St[0] = $row["Name_S"];
                $St[1] = $row["Branch"];
                $val = $St[0]. "|". $St[1];

                if ($val != "|") {
                    ChromePhp::log($val);
                    $StationInput[] = $val;
                    $u++;
                }
            }
        }
        else {
            $this->Error(-1, "SC SS| ". $ObjectBD->LastError());
        }
        
        return $StationInput;
    }

    private function SelT($ObjectBD, $t, $num, $flagT) {
        $flag = false;
        
        switch($t) {
                case 1: {
                    $t = "СВ";
                    break;
                }
                case 2: {
                    $t = "Люкс";
                    break;
                }
                case 3: {
                    $t = "Купе";
                    break;
                }
                case 4: {
                    $t = "Плацкарт";
                    break;
                }
                case 5: {
                    $t = "Сидячий 1-го класу";
                    break;
                }
                case 6: {
                    $t = "Сидячий 2-го класу";
                    break;
                }
            }

        if ($ObjectBD->SELECT("idTrain", "Train_Carriage", "idTrain = ". $num. " AND Type_C = '". $t. "'","", "")) {

            if ($ObjectBD->GetSize() > 0) {
                $flag = true;
            }
            else {
                if (!$flagT) {
                    $flag = false;
                }
            }
        }
        else {
           $this->Error("SelT", $ObjectBD->LastError());
           $flag = false;
        }

        return $flag;
    }
    
    private function SelV($num, $stringType) {
        
        if ($this->Object["v". $num] != null) {
                 if ($this->FlagV) {
                     $stringType .= "OR ";
                 }
            
            switch($num) {
                case 1: {
                    $stringType .= "Train.TypeTrain = 'Швидкісний' ";
                    break;
                }
                case 2: {
                    $stringType .= "Train.TypeTrain = 'Фірмовий' ";
                    break;
                }
                case 3: {
                    $stringType .= "Train.TypeTrain = 'Швидкий' ";
                    break;
                }
                case 4: {
                    $stringType .= "Train.TypeTrain = 'Пасажирський' ";
                    break;
                }
                case 5: {
                    $stringType .= "Train.TypeTrain = 'Електричка' ";
                    break;
                }
            }
                 //$stringType .= "Train.TypeTrain = '". $this->Object["v". $num]. "' ";
                 $this->FlagV = true;
             }
        return $stringType;
    }
    
    private function TypeFunc($ObjectBD) {
        $this->FlagV = false; //для перевірки виконання запиту
        $FlagT = false;
        $stringResult = "";
        $Result[0] = 0;
        $Result[1] = "";
        
        $stringType = $this->SelV("1", $stringType);
        $stringType = $this->SelV("2", $stringType);
        $stringType = $this->SelV("3", $stringType);
        $stringType = $this->SelV("4", $stringType);
        $stringType = $this->SelV("5", $stringType);
        $this->FlagV = false;
        

        if ($ObjectBD->SELECT("Train.idTrain AS 'Train'", "Train JOIN Schedul ON Train.idTrain = Schedul.idTrain_S", "Schedul.Start = '". $this->Object["dat"]. "' AND (". $stringType. ");","", "")) { //запит на дату та тип потягу
            $stringType = 0;
            $i = 0;
            $size = $ObjectBD->GetSize();
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $array[] = $row['Train'];
                $i++;
            }
            $stringType = $array;
            
            for ($i = 0; $i < $size; $i++) {
                $FlagT = false;

                // перевірка на тип
                if (!$FlagT) {
                    if ($this->Object["t1"] != null) {
                        $FlagT = $this->SelT($ObjectBD,1, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t2"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,2, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t3"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,3, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t4"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,4, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t5"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,5, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t6"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,6, $stringType[$i], $FlagT);
                    }
                }
                if (!$FlagT) { //чи були знайдені  t
                    unset($stringType[$i]);
                }
            }

            
        //}
            
            // перевірка на станції
            $flag_St = false;
            $flag_Ca = false;
            $Type1 = false;
            $Type2 = false;
            $Type3 = false;
            $Type4 = false;
            $Type5 = false;
            $Type6 = false;
            $st1 = 0;
            $st2 = 0;
            $size3 = 0;

            for ($i = 0; $i < $size; $i++) {
                $flag_St = false;
                //ChromePhp::log($stringType[$i]);

                if ($ObjectBD->SELECT("Station_Train.Sequence_number, Station.Name_S", "Station, Station_Train", "Station.Name_S in ('". $this->Object["St_1"]. "', '". $this->Object["St_2"]. "')  AND Station.idStation = Station_Train.idStation_Train AND Station_Train.idTrain = ". $stringType[$i] ,"", "Station_Train.Sequence_number DESC")) {
                    $n = 0;
                    while ($row = $ObjectBD->GetResult()->fetchArray()) {
                        //ChromePhp::log($row['Sequence_number']);
                        $stringStation[] = $row['Sequence_number'];
                        //ChromePhp::log($stringStation[$n]);
                        $n++;
                    }  //запит по станціям
                    ChromePhp::log($n);
                    if ($n == 0) {
                        $st1 = 0;
                        $st2 = 0;
                    }
                    else {
                        $st1 = $stringStation[0];
                        $st2 = $stringStation[1];
                    }
                    

                    if ($st1 == 0 || $st2 == 0) {
                        $flag_St = true;
                    }
                    //ChromePhp::log($stringStation[0],$stringStation[1],$st1, $st2);
                }

                if (!$flag_St) {
                    if ($ObjectBD->SELECT("TypeTrain","Train","idTrain = ". $stringType[$i],"","")) {
                            $type = $ObjectBD->GetResult()->fetchArray()["TypeTrain"];
                     }
                    if ($type == "Електричка") {
                        if ($st1 > $st2 && !$flag_St) {
                            $stringResult .= $stringType[$i]. " "; //запис номерів
                        }
                    }
                    else {
                        //Carriage
                        if ($ObjectBD->SELECT("Type_C", "Train_Carriage", "idTrain = ". $stringType[$i],"", "")) {
                           // $size2 = $ObjectBD->GetSize();
                            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                                $c = $row["Type_C"];

                                if ($c == "СВ") {
                                    $Type1 = true;
                                    continue;
                                }
                                if ($c == "Люкс") {
                                    $Type2 = true;
                                    continue;
                                }
                                if ($c == "Купе") {
                                    $Type3 = true;
                                    continue;
                                }
                                if ($c == "Плацкарт") {
                                    $Type4 = true;
                                    continue;
                                }
                                if ($c == "Сидячий 1-го класу") {
                                    $Type5 = true;
                                    continue;
                                }
                                if ($c == "Сидячий 2-го класу") {
                                    $Type6 = true;
                                }
                            }

                            $flag_Ca = false;

                            if ( $Type1) {
                                $NOS = 18;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]);
                            }
                            if ( $Type2 && !$flag_Ca) {
                                $NOS = 36;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]);
                            }
                            if ( $Type3 && !$flag_Ca) {
                                $NOS = 36;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]);
                            }
                            if ( $Type4 && !$flag_Ca) {
                                $NOS = 56;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]);
                            }
                            if ( $Type5 && !$flag_Ca) {
                                $NOS = 60;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]);
                            }
                            if ( $Type6 && !$flag_Ca) {
                                $NOS = 60;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]);
                            }
                        }

                        if ($st1 > $st2 && !$flag_St && $flag_Ca) { //перевірка станцій
                            $stringResult .= $stringType[$i]. " "; //запис номерів
                            $size3++;
                        }
                    }
                }

            }
            $Result[0] = $size3;
            $Result[1] = $stringResult;

            if ($size3 == 0) {
                $Result[0] = -3;
                return $Result;
            }

        }
        else {
            $this->Error(-1, " |". $ObjectBD->LastError());
            $Result[0] = -1;
            return $Result;
        }

        return $Result; //повертає кількість знайдених номерів маршрутів
    }
               
    private function NOSFunc($NOS, $ObjectBD, $st1, $st2, $num) {
        $flag_Ca1 = true;
        $flag_Ca2 = false;
        //$i = 0;

        //ChromePhp::log($NOS, $st2, $st1, $num);
        for ($u = 1; $u <= $NOS; $u++) {
            if ($ObjectBD->SELECT("Carriage.Employment", "Carriage, Train_Carriage", "Train_Carriage.idTrain = ". $num. " AND Carriage.idCarriage = Train_Carriage.idTrain_Carriage AND Carriage.Station_number >= ". $st2. " AND Carriage.Station_number < ". $st1. " AND Carriage.Place = ". $u,"", "")) {

                $size = $ObjectBD->GetSize();
            
                //$stringType[$i] = $row['Train'];
                //$i++;
                
                if ($size != 0) {
                    while ($row = $ObjectBD->GetResult()->fetchArray()) {
                        if ($row["Employment"] != "-") {
                            $flag_Ca1 = false;
                            break;
                        }
                    }
                    if ($flag_Ca1) {
                        $flag_Ca2 = true;
                    }
                }
            }
        }

        return $flag_Ca2;
    }
    
    private function FormationFile($Result, $ObjectBD) {
        
        $num = "";
        $IRoute = 0;
        //Route::$size_route = $Result[0]; //кількість маршрутів

        for ($i = 0; $i < strlen($Result[1]); $i++ ) {
           
            if ($Result[1][$i] == " ") {
                $ObjectRoute[] = new Route();
                
                if ($ObjectBD->SELECT("Train.idTrain, Train.St_1, Train.St_2, Station.Name_S, Station_Train.Arrival_time, Station_Train.Time_of_departure", " Train JOIN Station_Train ON Train.idTrain = Station_Train.idTrain JOIN Station ON Station_Train.idStation_Train = Station.idStation", "Train.idTrain = ". $num. " AND Station.Name_S in ('". $this->Object["St_1"]. "', '". $this->Object["St_2"]. "')","", "Station_Train.Sequence_number DESC")) {
                    
                    $row = $ObjectBD->GetResult()->fetchArray();
                    
                    $ObjectRoute[$IRoute]->SetIdTrain($row["idTrain"]);
                    $ObjectRoute[$IRoute]->SetSt1($row["St_1"]);
                    $ObjectRoute[$IRoute]->SetSt2($row["St_2"]);
                    $Buff[0]["Name_S"] = $row["Name_S"];
                    $Buff[0]["Arrival_time"] = $row["Arrival_time"];
                    $Buff[0]["Time_of_departure"] = $row["Time_of_departure"];
                    
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $Buff[1]["Name_S"] = $row["Name_S"];
                    $Buff[1]["Arrival_time"] = $row["Arrival_time"];
                    $Buff[1]["Time_of_departure"] = $row["Time_of_departure"];
                    
                    $ObjectRoute[$IRoute]->SetTime($Buff[1]["Time_of_departure"], $Buff[0]["Arrival_time"]);
                        
                    
                   if ($ObjectBD->SELECT("Station.Name_S, Station.Coordinates, Station_Train.Sequence_number", "Station, Station_Train", "Station_Train.idTrain = ". $num. " AND Station_Train.idStation_Train = Station.idStation", "", "")) {

                       //$size = $ObjectBD->GetSize();
                       while ($row = $ObjectBD->GetResult()->fetchArray()) {
                           $Buff[0]["Name_S"] = $row["Name_S"];
                           $Buff[0]["Coordinates"] = $row["Coordinates"];
                           $Buff[0]["Sequence_number"] = $row["Sequence_number"];
                           // запис координатів станцій
                           $ObjectRoute[$IRoute]->SetStation($Buff[0]["Sequence_number"], $Buff[0]["Coordinates"], $Buff[0]["Name_S"]);
                       }
                       $IRoute++;
                   }
                   else {
                       $this->Error(-2, "ForFi SS [n,c,s]| ". $ObjectBD->LastError());
                       return -2;
                   }
                }
                else {
                    $this->Error(-1, "ForFi ST| ". $ObjectBD->LastError());
                    return -1;
                }
                
                $num = "";
            }
            else {
                $num .= $Result[1][$i];
            }    
        }
        Route::$size_route = $Result[0];
        $Result[1] = "";
        //$mas;
        //$mas1;
        $i = Route::$size_route - 1;
        for ($j = 0; $j <= $i; $j++) {
            $mas[$j] = $ObjectRoute[$j]->Time_roatInt();
            $mas1[$j] = $mas[$j];
            ChromePhp::log($mas[$j]);
        }

        $k = 1;

        while (($k!=0) && ($i>0))  {
          $k = 0;

          for ($j = 0; $j <= $i - 1; $j++) {
               if ($mas[$j] > $mas[$j+1]) {
                  $a = $mas[$j];
                  $mas[$j] = $mas[$j+1];
                  $mas[$j+1] = $a;
                  $k = 1;
                }
          }
         $i--;
         }
        
        ChromePhp::log($mas);
        
       for ($i = 0; $i < Route::$size_route; $i++) {

            for ($j = 0; $j < Route::$size_route; $j++) {
                if ($mas[$i] == $mas1[$j]) {
                    $Result[1] .= $ObjectRoute[$j]->WriteRoute($this->Object["St_1"],$this->Object["St_2"],$this->Object["dat"]);
                    $mas[$i] = -1;
                }
            }
        }
        return $Result;
    }
    /*
    private function TrainFunc($ObjectBD) {
        $this->FlagV = false; //для перевірки виконання запиту

        if ($this->Object["Fil"] == "1") {
            if ($ObjectBD->SELECT("idTrain", "Train", "","", "")) { //запит на філії
                $size = $ObjectBD->GetSize();
                $i = 0;
                while ($row = $ObjectBD->GetResult()->fetchArray()) {
                    $result[$i] = $row['idTrain'];
                    $i++;
                }
                if ($size == 0) {
                    $Result[0] = -98;
                    return $Result;
                }
            }
            else {
                $this->Error("Error train select (T1)", $ObjectBD->LastError());
                $Result[0] = -1;
                return $Result;
            }
        }
        else {
            $Ofil = $this->Object["Fil"];
                if ($Ofil == "2") {
                    $fil = "Одеська";
                }
                if ($Ofil == "3") {
                    $fil = "Південна";
                }
                if ($Ofil == "4") {
                    $fil = "Південно-Західна";
                }
                if ($Ofil == "5") {
                    $fil = "Придніпровська";
                }
                if ($Ofil == "6") {
                    $fil = "Донецька";
                }
                if ($Ofil == "7") {
                    $fil = "Львівська";
                }
            
            $this->FlagV = false;

                // перевірка на тип
                $stringType = $this->SelV("1", $stringType);
                $stringType = $this->SelV("2", $stringType);
                $stringType = $this->SelV("3", $stringType);
                $stringType = $this->SelV("4", $stringType);
                $stringType = $this->SelV("5", $stringType);
                $this->FlagV = false;
            
            if ($ObjectBD->SELECT("Train.idTrain", "Train, Station, Station_Train", "Station_Train.idTrain = Train.idTrain AND Station_Train.Sequence_number = 1 AND Station_train.idStation_Train = Station.idStation AND Station.Branch = '". fil. "' AND (". $stringType. ")","", "")) { //запит на філії
                $size = $ObjectBD->GetSize();
                $i = 0;
                while ($row = $ObjectBD->GetResult()->fetchArray()) {
                    $result[$i] = $row['idTrain'];
                    $i++;
                } //запис у файл номерів
                if ($size == 0) {
                    $Result[0] = -99;
                    return $Result;
                }
                $Result[0] = $size;
                $Result[1] = $result;
            }
            else {
                $this->Error("Error train select (T1)", $ObjectBD->LastError());
                $Result[0] = -1;
                return $Result;
            }
        }

        return $Result; //повертає кількість знайдених номерів маршрутів
    }
    
    private function FTrainFile($Result, $ObjectBD) {
        $ObjectRoute = new Route;
        Route::$size_route = $Result[0]; //кількість маршрутів
        $IRoute = 0;
        $stringResult = "";
        $num = "";

        for ($i = 0; $i < strlen($Result[1]); $i++ ) {
           
            if ($Result[1][$i] == " ") {

                if ($ObjectBD->SELECT("idTrain, St_1, St_2", "Train", "idTrain = ". num,"", "")) {
                    $ObjectRoute[$IRoute]->SetIdTrain($ObjectBD->GetResult()["idTrain"]);
                    $ObjectRoute[$IRoute]->SetSt1($ObjectBD->GetResult()["St_1"]);
                    $ObjectRoute[$IRoute]->SetSt2($ObjectBD->GetResult()["St_2"]);
                    $stringResult .= $ObjectRoute[$IRoute]->WriteRoute($this->Object["St_1"],$this->Object["St_2"],$this->Object["dat"]);
                    $IRoute++;
                }
                else {
                    $this->Error(-1, "FTF| ". $ObjectBD->LastError());
                    return -1;
                }
                $num = "";
            }
            else {
                $num .= $Result[1][$i];
            }   
        }
        return $stringResult;
    }
   
    private function ADD($ObjectBD) {
        $ObjectR = new Route;

        switch($this->Door) {
        case 1: {

             if ($ObjectBD->SELECT("idTrain", "Train", "idTrain = ". $this->Object["numTrain"], "", "")) {
                 $ObjectR->SetIdTrain($ObjectBD->GetResult()["idTrain"]);

                 if ($ObjectR->GetIdTrain() == 0) {

                     if ($ObjectBD->SELECT("Name_S", "Station", "Station.Name_S in ('". $this->Object["St_1"]. "', '". $this->Object["St_2"]. "')","", "")) {
                         $r1 = $ObjectBD->GetResult()["Name_S"];
                         $ObjectBD->GetResult() = $ObjectBD->GetResult()->fetchArray();
                         $r2 = $ObjectBD->GetResult()["Name_S"];

                         if ($r1 == "") {
                             return -13;
                         }
                         if ($r2 == "") {
                             return -14;
                         }

                         if ($ObjectBD->InsertTrain($this->Object["numTrain"], $this->Object["St_1"], $this->Object["St_2"], $this->Object["Col"], $this->Object["Time"], $this->Object["Type"], $this->Object["Col_V"])) {
                             return 0;
                         }
                         else {
                            $this->Error(-15, "ADD IT| ". $ObjectBD->LastError());
                         }
                     }
                     else {
                         $this->Error(-11, "ADD SST| ". $ObjectBD->LastError());
                         return -1;
                     }
                 }
                 else {
                     return -12;
                 }
             }
             else {
                 $this->Error(-1, "ADD SN| ". $ObjectBD->LastError());
                 return -1;
             }

            break;
        }
        case 2: {

            if ($ObjectBD->SELECT("idStation", "Station", "idStation = ". $this->Object["cod"], "", "")) {

                if ($ObjectBD->GetResult()["idStation"] != 0) {
                    if ($ObjectBD->InsertST($this->Object["cod"], $this->Object["num"], $this->Object["num_S"], $this->Object["Time_1"], $this->Object["Time_2"])) {
                        return 0;
                    }
                    else {
                       $this->Error(-25, "ADD IST| ". $ObjectBD->LastError());
                    }
                }
                else {
                    return -21;
                }
            }
            else {
                $this->Error(-2, "ADD Sid| ". $ObjectBD->LastError());
                return -2;
            }
            break;
        }
        case 3: {

            if ($ObjectBD->InsertSchedule($this->Object["num"], $this->Object["dat"], $this->Object["T1"], $this->Object["T2"])) {
                return 0;
            }
            else {
               $this->Error(-35, "ADD ISch| ". $ObjectBD->LastError());
            }
            break;
        }
        case 4: {
            $fl = true;
            //$type;
            $NOS = 0;

            $COL = $this->Object["Col"];
            $n = $this->Object["Type_V"];
            if ( $n == "СВ") {
                $NOS = 18;
            }
            if ( $n == "Люкс") {
                $NOS = 36;
            }
            if ( $n == "Купе") {
                $NOS = 36;
            }
            if ( $n == "Плацкарт") {
                $NOS = 56;
            }
            if ( $n == "Сидячий 1-го класу") {
                $NOS = 60;
            }
            if ( $n == "Сидячий 2-го класу") {
                $NOS = 60;
            }

            if ($ObjectBD->InsertTC($this->Object["cod_V"], $this->Object["num"], $this->Object["num_V"], $this->Object["Type_V"], $NOS)) {
                if ($ObjectBD->SELECT("TypeTrain","Train","idTrain = ". $this->Object["num"],"","")) {
                    $type = $ObjectBD->GetResult()["TypeTrain"];
                    if ($type == "Електричка") {
                        $fl = false;
                    }
                }

                if ($fl) {

                    for ($i = 1; $i <= $COL; $i++) {

                        for ($j = 1; $j <= $NOS; $j++) {

                            if (!$ObjectBD->InsertCarriage($this->Object["cod_V"], $i, $j, "-")) {
                                $this->Error(-36, "ADD IC| ". $ObjectBD->LastError());
                            }
                        }
                    }
                }
                return 0;
            }
            else {
               $this->Error(-35, "ADD ITC| ". $ObjectBD->LastError());
            }
            break;
        }

        }
        return 0;
    }
    
    private function StationFunc($ObjectBD) {
        $i = 0;
        $Result[0] = 0;

        if ($ObjectBD->SELECT("Train.idTrain", "Station, Station_Train, Train JOIN Schedul ON Train.idTrain = Schedul.idTrain_S", "Station.Name_S = '". $this->Object["St"]. "' AND Station.idStation = Station_Train.idStation_Train AND Schedul.Start = '". $this->Object["dat"]. "'","", "")) {
                    
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                  $st = $row["idTrain"];

                   if ($st != 0) {
                        $Result[1][$i] = $st; //запис номерів
                        $Result[0]++;
                   } 
            }
        }
        else {
           $this->Error(-2, "StFu SidT| ". $ObjectBD->LastError());
        }

        if ($Result[0] == 0) {
             $Result[0] = -32;
             return $Result;
         }

        return $Result;
    }
    
    private function StationFile($Result, $ObjectBD) {
        $ObjectRoute = new Route;
        $IRoute = 0;
        //$Buff;
        Route::$size_route = $Result[0]; //кількість маршрутів

        for ($i = 0; $i < strlen($Result[1]); $i++ ) {
           
            if ($Result[1][$i] == " ") {

                if ($ObjectBD->SELECT("Train.idTrain, Train.St_1, Train.St_2, Station.Name_S, Station_Train.Arrival_time, Station_Train.Time_of_departure", " Train JOIN Station_Train ON Train.idTrain = Station_Train.idTrain JOIN Station ON Station_Train.idStation_Train = Station.idStation", "Train.idTrain = ". $num. " AND Station.Name_S in ('". $this->Object["St"]. "', '". $this->Object["St_2"]. "')","", "Station_Train.Sequence_number DESC")) {
                    $ObjectRoute[$IRoute]->SetIdTrain($ObjectBD->GetResult()["idTrain"]);
                    $ObjectRoute[$IRoute]->SetSt1($ObjectBD->GetResult()["St_1"]);
                    $ObjectRoute[$IRoute]->SetSt2($ObjectBD->GetResult()["St_2"]);
                    $Buff[0]["Name_S"] = $ObjectBD->GetResult()["Name_S"];
                    $Buff[0]["Arrival_time"] = $ObjectBD->GetResult()["Arrival_time"];
                    $Buff[0]["Time_of_departure"] = $ObjectBD->GetResult()["Time_of_departure"];
                    
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $Buff[1]["Name_S"] = $row["Name_S"];
                    $Buff[1]["Arrival_time"] = $row["Arrival_time"];
                    $Buff[1]["Time_of_departure"] = $row["Time_of_departure"];
                    
                    $ObjectRoute[$IRoute]->SetTime($Buff[1]["Time_of_departure"], $Buff[0]["Arrival_time"]);
                    $IRoute++;
                }
                else {
                    $this->Error(-1, "StF ST| ". $ObjectBD->LastError());
                    $Result[0] = -1;
                    return $Result;
                }
                $num = "";
            }
            else {
                $num .= $Result[1][$i];
            }
        }

        $Result[1] = "";
        //$mas;
        //$mas1;
        $i = $Result[0] - 1;
        for ($j = 0; $j <= $i; $j++) {
            $mas[$j] = $ObjectRoute[$j]->TimeInt();
            $mas1[$j] = $mas[$j];
        }

        $k = 1;

        while (($k!=0) && ($i>0))  {
          $k = 0;

          for ($j = 0; $j <= $i - 1; $j++) {
               if ($mas[$j] > mas[$j+1]) {
                  $a = mas[$j];
                  $mas[$j] = $mas[$j+1];
                  $mas[$j+1] = $a;
                  $k = 1;
                }
          }
         $i--;
         }

       for ($i = 0; $i < $Result[0]; $i++) {

            for ($j = 0; $j < $Result[0]; $j++) {
                if ($mas[$i] == $mas1[$j]) {
                    $Result[1] .= $ObjectRoute[$j]->WriteRouteSt();
                    $mas[$i] = -1;
                }
            }
        }
        return $Result;
    }
    
    private function DeleteNumber($ObjectBD) {
        $num = $this->Object["Num"];
        //$numC;

        if ($ObjectBD->DELETE("Station_Train", "idTrain = ". $num)) {

            if ($ObjectBD->SELECT("idTrain_Carriage", "Train_Carriage", "idTrain = ". $num,"", "")) {

                while ($row = $ObjectBD->GetResult()->fetchArray()) {
                     $numC = $row["idTrain_Carriage"];

                    if ($ObjectBD->DELETE("Carriage", "Carriage.idCarriage = ". $numC)) {

                        if ($ObjectBD->DELETE("Train_Carriage", "idTrain = ". $num)) {

                            if ($ObjectBD->DELETE("Schedul", "idTrain_S = ". $num)) {

                                if ($ObjectBD->DELETE("Train", "idTrain = ". $num)) {

                                }
                                else {
                                    $this->Error(-5, "DN DT| ". $ObjectBD->LastError());
                                    return -5;
                                }
                            }
                            else {
                                $this->Error(-4, "DN DSh| ". $ObjectBD->LastError());
                                return -4;
                            }
                        }
                        else {
                            $this->Error(-3, "DN DTC| ". $ObjectBD->LastError());
                            return -3;
                        }
                    }
                    else {
                        $this->Error(-2, "DN DC| ". $ObjectBD->LastError());
                        return -2;
                    }
                }
             }
             else {
                $this->Error(-6, "DN STC| ". $ObjectBD->LastError());
                return -6;
             }
        }
        else {
            $this->Error(-1, "DN DST| ". $ObjectBD->LastError());
            return -1;
        }
        return 0;
    }
    
    private function DeleteSC($ObjectBD) {
        $num = $this->Object["Num"];

        if ($this->Object["S"] != "0") {

            if ($ObjectBD->SELECT("Sequence_number", "Station_Train", "idStation_Train = ". $this->Object["S"],"", "")) {
                $Sn = $ObjectBD->GetResult()["Sequence_number"];


                if ($ObjectBD->DELETE("Station_Train", "idTrain = ". $num. " AND idStation_Train = ". $this->Object["S"])) {

                   if ($ObjectBD->UPDATE("Train", "Number_of_stations = Number_of_stations - 1","idTrain = ". $num)) {

                       if ($ObjectBD->SELECT("idTrain_Carriage", "Train_Carriage", "idTrain = ". $num,"", "")) {

                           while ($row = $ObjectBD->GetResult()->fetchArray()) {
                                $numC = $row["idTrain_Carriage"];
                               
                                if ($ObjectBD->DELETE("Carriage", " Station_number = ". $Sn. " AND idCarriage = ". $numC)) {

                                    if ($ObjectBD->UPDATE("Station_Train", "Sequence_number = Sequence_number - 1","Sequence_number > ". $Sn. " AND idTrain = ". $num)) {


                                    }
                                    else {
                                        $this->Error(-6, "DSC UC| ". $ObjectBD->LastError());
                                        return -6;
                                    }
                                }
                                else {
                                    $this->Error(-3, "DSC DC| ". $ObjectBD->LastError());
                                    return -3;
                                }
                           }
                        }
                        else {
                           $this->Error(-5, "DSC STC| ". $ObjectBD->LastError());
                           return -5;
                        }
                    }
                    else {
                       $this->Error(-2, "DSC UT| ". $ObjectBD->LastError());
                       return -2;
                    }
                }
                else {
                    $this->Error(-1, "DSC DST| ". $ObjectBD->LastError());
                    return -1;
                }
            }
            else {
                $this->Error(-4, "DSC SST| ". $ObjectBD->LastError());
                return -4;
            }
        }
        if ($this->Object.Search("nC") != 0) {

            if ($ObjectBD->DELETE("Carriage", "idCarriage = ". $this->Object["nC"])) {
                if ($ObjectBD->SELECT("Number_carriage", "Train_Carriage", "idTrain_Carriage = ". $this->Object["nC"],"", "")) {
                    $Sn = $ObjectBD->GetResult()["Number_carriage->"];

                    if ($ObjectBD->DELETE("Train_Carriage", "idTrain_Carriage = ". $this->ObjectObject["nC"])) {

                        if ($ObjectBD->UPDATE("Train_Carriage", "Number_carriage = Number_carriage - 1","Number_carriage > ". $Sn. " AND idTrain = ". $num)) {


                        }
                        else {
                            $this->Error(-10, "DSC UTC| ". $ObjectBD->LastError());
                            return -10;
                        }
                    }
                    else {
                        $this->Error(-8, "DSC DTC| ". $ObjectBD->LastError());
                        return -8;
                    }
                }
                else {
                    $this->Error(-9, "DSC STC| ". $ObjectBD->LastError());
                    return -9;
                }
            }
            else {
                $this->Error(-7, "DSC DC| ". $ObjectBD->LastError());
                return -7;
            }
        }
        return 0;
    }
    
    private function DeleteSh($ObjectBD) {
        if (!$ObjectBD->DELETE("Schedul", "idTrain_S = ". $this->Object["Num"]. " AND Start = '". $this->Object["dat"]. "'")) {
            $this->Error(-1, "DSh DSh| ". $ObjectBD->LastError());
            return -1;
        }
        return 0;
    }
    
    private function SearchMapWay($ObjectBD) {
        $ObjectRoute = new Route;
        $Buff[0] = -2;
        $i = 0;

        if ($ObjectBD->SELECT("Station.Name_S, Station.Coordinates, Station_Train.Sequence_number", "Station, Station_Train", "Station_Train.idTrain = ". Object["Num"]. " AND Station_Train.idStation_Train = Station.idStation", "", "")) {
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $ObjectRoute[$i]->SetStation(row['Sequence_number'], row['Coordinates'], row['Name_S']);
                $Buff[$i] = $ObjectRoute[$i]->WriteRouteMap($this->Object);
                $i++;
            }  
         }
         else {
            $this->Error(-2, "SMW SS| ". $ObjectBD->LastError());
            return $Buff[0];
         }
        return $Buff;
    }
    
    private function Authentication($ObjectBD) {
        //$PIB;

        if ($ObjectBD->SELECT("PIB", "Input", "Login = '" . Object["login"]. "' AND Password = '". Object["password"]. "'","", "")) {
            $PIB = $ObjectBD->GetResult()['PIB'];

            if ($PIB == "") {
                return "-10";
            }
        }
        else {
            $this->Error(-1, "A SI| ". $ObjectBD->LastError());
            return "-1";
        }

        return $PIB;
    }
    
    private function WriteFile($str) {
        if (!$handle = fopen("Error_log.txt", "wb")) {
            $error = "C1 (Not open file)";
            exit;
        }
        else {
            if (fwrite($handle, $str) === FALSE) {
                $error = "C11 (Not write file)";
                exit;
            }
        }
    }*/

    private function Error($number, $String) {
        $this->WriteFile($number. "|". $String. "/");  
    }
    
    /*public function SearchTrain($POST) {
        $this->Object = $POST;
        $this->Door = $POST['C'];
        
        ChromePhp::log($this->Door);
    }*/
    
    public function HallWay($POST) {
        $this->Object = $POST;
        $this->Door = $POST['C'];
        
        //return $POST['C'];
        
        $stringResult = "";
        $ObjectBD = new ProjectBD();
        $ObjectBD->createConnection();
        ChromePhp::log('2 хал');

        switch($this->Door) {
            case 0: {
                $stringResult = $this->TypeFunc($ObjectBD); //пошук маршрутів (1)
                ChromePhp::log('3');

                if ($stringResult[0] > 0) {
                   $stringResult = $this->FormationFile($stringResult, $ObjectBD); //запис маршрутів
                    ChromePhp::log('4');
                   if ($stringResult[0] < 0) {
                       $this->Error($stringResult[1], "Simakin flew");
                       $stringResult[1] = "Not Train";
                   }
                    else {
                        $stringResult[1] .= "$";
                    }
                }
                else {
                    $stringResult[1] = "Not Train";
                }
                return $stringResult[1];
                //break;
            }
            
            /*case 10: {
            $stringResult = $this->TrainFunc($ObjectBD); //пошук маршрутів (1)

            if ($stringResult[0] < 0) {

                switch ($stringResult[0]) {
                    case -98: {
                        $stringResult[1] = "Not Train";
                        break;
                    }
                    case -99: {
                        $stringResult[1] = "Not Train";
                        break;
                    }
                    case -1: {
                        $stringResult[1] = "false";
                        break;
                    }
                }
            }
            else {
               $stringResult = $this->FTrainFile($stringResult, $ObjectBD); //запис маршрутів
                $stringResult .= "$";
                return $stringResult;
            }
            return $stringResult[1];

            }
            case 1: {
            }
            case 2: {
            }
            case 3: {
            }
            case 4: {
                $stringResult = $this->ADD($ObjectBD); //

                if ($stringResult < 0) {

                    switch ($stringResult) {
                        case -12: {
                            $stringResult = "Not num";
                            break;
                        }
                        case -13: {
                            $stringResult = "Not St_1";
                            break;
                        }
                        case -14: {
                            $stringResult = "Not St_2";
                            break;
                        }
                        case -21: {
                            $stringResult = "Not cod";
                            break;
                        }
                        case -1: {
                            $stringResult = "false";
                            break;
                        }
                    }
                }
                else {
                  $stringResult = "OK";
                }
                return $stringResult;
            }
            case 5: {
                $stringResult = $this->StationFunc($ObjectBD); //

                if ($stringResult[0] < 0) {

                    switch ($stringResult[0]) {
                        case -19: {
                            $stringResult[1] = "Not dat";
                            break;
                        }
                        case -32: {
                            $stringResult[1] = "Not St";
                            break;
                        }
                        case -1: {
                            $stringResult[1] = "false";
                            break;
                        }
                    }
                    return $stringResult[1];
                }
                else {
                   $stringResult = $this->StationFile($stringResult, $ObjectBD);
                }
                
                $stringResult[1] .= "$";
                return $stringResult[1];

            }*/
            case 6: {
                ChromePhp::log('start SC');
                $StationInput = $this->StationCheck($ObjectBD);
                $col = 0;
                foreach ($StationInput as $i => $value) {
                    $col++;
                    ChromePhp::log($col);
                }
                

                if ($col != 0) {
                    $fout = "$";

                    for ($i = 0; $i < $col; $i++) {
                        $fout .= $StationInput[$i]. "$";
                    }
                }
                else {
                    $fout = "Not Station";
                }
                return $fout;
            }

        /*case 7: {
            $size = $this->DeleteNumber($ObjectBD);

            if ($size == 0) {
                $fout = "true";
            }
            else {
                $fout = "Not Delete (Error DataBase)";
            }
            return $fout;
        }
        case 8: {
            $size = $this->DeleteSC($ObjectBD);

            if ($size == 0) {
                $fout = "true";
            }
            else {
                $fout = "Not Delete (Error DataBase)";
            }
            return $fout;
        }
        case 9: {
            $size = $this->DeleteSh($ObjectBD);

            if ($size == 0) {
                $fout = "true";
            }
            else {
                $fout = "Not Delete (Error DataBase)";
            }
            return $fout;
        }


        case 111: {
            $p = $this->Authentication($ObjectBD);

                if ($p == "-10") {
                    $fout = "Not Authentication";
                }
                else {
                    if ($p == "-1") {
                        $fout = "Error server";
                    }
                    else {
                        $fout = "true";
                        $fout2 = "Signed in [". $p. "]\n";
                        $this->WriteFile($fout2);
                        
                    }
                }
            return $fout;
        }

        case 20: {
            $Result = "";
            $stringResult = $this->SearchMapWay($ObjectBD);
            if ($stringResult != -2) {
                 $this->Error($stringResult, "Simakin flew");
                $Result = "Error map";
             }
             else {
                 foreach ($StationInput as $i => $value) {
                    $Result .= $stringResult[$i];
                }
                  $Result .= "$";
             }
            return $Result;
        }*/

            default: {
                return -1;
            }
        }
        return 0;
    }
}
 
?>