<?php
include ('ProjectBD.php');
include ('Route.php');
include ('PDF.php');
//include ('Redis.php');

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
    
    private function StationCODCheck($ObjectBD) {
        $u = 0;
        $StationInput = null;

        if ($ObjectBD->SELECT("idStation, Name_S", "Station", "idStation LIKE '%". $this->Object["value"]. "%'","", "")) {
            
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                
                $St[0] = $row["idStation"];
                $St[1] = $row["Name_S"];
                $val = $St[0]. "|". $St[1];

                if ($val != "|") {
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
        
        if ($this->Object["v". $num] != 0) {
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
        $Time1 = new TimeBase();
        $Time2 = new TimeBase();
        
        $stringType = $this->SV($stringType);
        
        if ($ObjectBD->SELECT("Train.idTrain AS 'Train'", "Train JOIN Schedul ON Train.idTrain = Schedul.idTrain_S", "Schedul.Start = '". $this->Object["dat"]. "' AND (". $stringType. ")","", "")) { //запит на дату та тип потягу
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
                    if ($this->Object["t1"] != 0) {
                        $FlagT = $this->SelT($ObjectBD,1, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t2"] != 0 && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,2, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t3"] != 0 && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,3, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t4"] != 0 && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,4, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t5"] != 0 && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,5, $stringType[$i], $FlagT);
                    }
                    if ($this->Object["t6"] != 0 && !$FlagT) {
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

            for ($i = 0; $i < count($stringType); $i++) {
                $flag_St = false;
                //ChromePhp::log($stringType[$i]);

                if ($ObjectBD->SELECT("Station_Train.Sequence_number, Station.Name_S", "Station, Station_Train", "Station.Name_S in ('". $this->Object["St_1"]. "', '". $this->Object["St_2"]. "')  AND Station.idStation = Station_Train.idStation_Train AND Station_Train.idTrain = ". $stringType[$i] ,"", "Station_Train.Sequence_number DESC")) {
                    $n = 0;
                    while ($row = $ObjectBD->GetResult()->fetchArray()) {
                        //ChromePhp::log($row['Sequence_number']);
                        $stringStation[$n] = $row['Sequence_number'];
                        //ChromePhp::log($stringStation[$n]);
                        $n++;
                    }  //запит по станціям
                    //ChromePhp::log($stringStation);
                    if ($n == 0) {
                        $st1 = 0;
                        $st2 = 0;
                    }
                    else {
                        $st1 = $stringStation[0];
                        $st2 = $stringStation[1];
                    }
                    
                    if ($ObjectBD->SELECT("Station_Train.Time_of_departure, Station_Train.Arrival_time, Station_Train.Sequence_number", "Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation", "(Station.Name_S = '". $this->Object["St_1"]. "' OR Station_Train.Sequence_number = 1) AND Station_Train.idTrain = ". $stringType[$i],"", "")) {
                        ChromePhp::log($ObjectBD->GetSize());
                        if ($ObjectBD->GetSize() == 2) {
                                        
                            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                                if ($row['Sequence_number'] == 1) {
                                    ChromePhp::log($row['Time_of_departure']);
                                    $Time1->SetTime($row['Time_of_departure']);
                                }
                                else {
                                    ChromePhp::log($row['Arrival_time']);
                                    $Time2->SetTime($row['Arrival_time']);
                                }
                            }
                            $c = $Time2->subTime($Time1);

                            if ($Time2->CheckData() || $st1 == 0 || $st2 == 0) {
                                $flag_St = true;
                            }
                        } else {
                            if ($st1 == 0 || $st2 == 0) {
                                $flag_St = true;
                            }
                        }
                        
                    }
                    
                }

                if (!$flag_St) {
                    if ($ObjectBD->SELECT("TypeTrain","Train","idTrain = ". $stringType[$i],"","")) {
                            $type = $ObjectBD->GetResult()->fetchArray()["TypeTrain"];
                     }
                    if ($type == "Електричка") {
                        if ($st1 > $st2 && !$flag_St) {
                            $stringResult .= $stringType[$i]. " "; //запис номерів
                            $size3++;
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
            $mas1[$j] = $mas[$j] + 0;
        }
        $k = 1;

        while (($k!=0) && ($i>0))  {
          $k = 0;

          for ($j = 0; $j <= $i - 1; $j++) {
               if ($mas1[$j] > $mas1[$j+1]) {
                  $a = $mas1[$j];
                  $mas1[$j] = $mas1[$j+1];
                  $mas1[$j+1] = $a;
                  $k = 1;
                }
          }
         $i--;
         }
        
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
    
    private function TrainFunc($ObjectBD) {
        $this->FlagV = false; //для перевірки виконання запиту
        // перевірка на тип
        $stringType = $this->SelV("1", $stringType);
        $stringType = $this->SelV("2", $stringType);
        $stringType = $this->SelV("3", $stringType);
        $stringType = $this->SelV("4", $stringType);
        $stringType = $this->SelV("5", $stringType);
        $this->FlagV = false;

        if ($this->Object["Fil"] == "1") {
            $select = "idTrain";
            $from = "Train";
            $where = $stringType;
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
            
            
            $select = "Train.idTrain";
            $from = "Train, Station, Station_Train";
            $where = "Station_Train.idTrain = Train.idTrain AND Station_Train.Sequence_number = 1 AND Station_train.idStation_Train = Station.idStation AND Station.Branch = '". $fil. "' AND (". $stringType. ")";
        }
        if ($ObjectBD->SELECT($select, $from, $where,"", "")) { //запит на філії
                $i = 0;
                while ($row = $ObjectBD->GetResult()->fetchArray()) {
                    $result[$i] = $row['idTrain'];
                    $i++;
                }
                if ($i == 0) {
                    $Result[0] = -98;
                    return $Result;
                }
            }
            else {
                $this->Error("Error train select (T1)", $ObjectBD->LastError());
                $Result[0] = -1;
                return $Result;
            }
            $Result[0] = $i;
            $Result[1] = $result;

        return $Result; //повертає кількість знайдених номерів маршрутів
    }
    
    private function FTrainFile($Result, $ObjectBD) {
        //Route::$size_route = $Result[0]; //кількість маршрутів
        $IRoute = 0;
        $stringResult = "";
        $num = "";
        //ChromePhp::log($Result[1]);

        for ($i = 0; $i < count($Result[1]); $i++ ) {
           
            if ($Result[1][$i] != " ") {
                $ObjectRoute[] = new Route();
                $num = $Result[1][$i];
                //ChromePhp::log($num);

                if ($ObjectBD->SELECT("idTrain, St_1, St_2", "Train", "idTrain = ". $num,"", "")) {
                    
                    $row = $ObjectBD->GetResult()->fetchArray();
                    
                    $ObjectRoute[$IRoute]->SetIdTrain($row["idTrain"]);
                    $ObjectRoute[$IRoute]->SetSt1($row["St_1"]);
                    $ObjectRoute[$IRoute]->SetSt2($row["St_2"]);
                    $stringResult .= $ObjectRoute[$IRoute]->WriteRouteTrain(/*$this->Object["St_1"],$this->Object["St_2"],$this->Object["dat"]*/);
                    $IRoute++;
                    
                    //ChromePhp::log($stringResult);
                }
                else {
                    $this->Error(-1, "FTF| ". $ObjectBD->LastError());
                    return -1;
                }
                $num = "";
            }
            /*else {
                $num .= $Result[1][$i];
            } */  
        }
        return $stringResult;
    }
    
    private function SV($stringType) {
        $stringType = $this->SelV("1", $stringType);
        $stringType = $this->SelV("2", $stringType);
        $stringType = $this->SelV("3", $stringType);
        $stringType = $this->SelV("4", $stringType);
        $stringType = $this->SelV("5", $stringType);
        $this->FlagV = false;
        return $stringType;
    }
    
    private function TransferFile($Train, $ObjectBD) {
        $Ob = new Route();
        $this->Time_1 = new TimeBase();
        $this->Time_2 = new TimeBase();
        
        for ($i = 1; $i < ($Train[0] * 2) + 1; $i++) {
            
            if($ObjectBD->SELECT("St_1, St_2", "Train", "idTrain = ". $Train[$i][0]['id'],"", "")) {
                $row = $ObjectBD->GetResult()->fetchArray();
                $Train[$i][0]['Start'] = $row['St_1'];
                $Train[$i][0]['Finish'] = $row['St_2'];
            }
            
            if ($i % 2 != 0) {
                $Train[$i][0]['St_1'] = $this->Object["St_1"];
                
                if($ObjectBD->SELECT("Station.Name_S AS 'Name_S'", "Station_Train JOIN Station ON Station.idStation = Station_Train.idStation_Train", "Station_Train.idTrain = ". $Train[$i + 1][0]['id']. " AND Station_Train.Sequence_number = ". $Train[$i + 1][0]['NS'],"", "")) {
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $Train[$i][0]['St_2'] = $row['Name_S'];
                }
            }
            else {
               if($ObjectBD->SELECT("Station.Name_S AS 'Name_S'", "Station_Train JOIN Station ON Station.idStation = Station_Train.idStation_Train", "Station_Train.idTrain = ". $Train[$i][0]['id']. " AND Station_Train.Sequence_number = ". $Train[$i][0]['NS'],"", "")) {
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $Train[$i][0]['St_1'] = $row['Name_S'];
                } 
                
                $Train[$i][0]['St_2'] = $this->Object["St_2"];
            }
            
            //ChromePhp::log($Train[$i]);
            
            if($ObjectBD->SELECT("Station_Train.Arrival_time AS 'Arrival_time', Station_Train.Time_of_departure AS 'Time_of_departure'", "Station_Train JOIN Station ON Station.idStation = Station_Train.idStation_Train", "Station_Train.idTrain = ". $Train[$i][0]['id']. " AND Station.Name_S in ('". $Train[$i][0]['St_1']. "', '". $Train[$i][0]['St_2']. "')","", "Station_Train.Sequence_number DESC")) {
                
                $row = $ObjectBD->GetResult()->fetchArray();

                $Time2["Arrival_time"] = $row["Arrival_time"];
                $Time2["Time_of_departure"] = $row["Time_of_departure"];
                    
                $row = $ObjectBD->GetResult()->fetchArray();
                $Time1["Arrival_time"] = $row["Arrival_time"];
                $Time1["Time_of_departure"] = $row["Time_of_departure"];
                //
                //ChromePhp::log($Time1["Time_of_departure"], "-");
                //ChromePhp::log($Time1["Arrival_time"]);
                //ChromePhp::log($Time2["Time_of_departure"]);
                //ChromePhp::log($Time2["Arrival_time"], "-");
                //
                $this->Time_1->SetTime($Time1["Time_of_departure"]);
                $this->Time_2->SetTime($Time2["Arrival_time"]);
                
                $Train[$i][0]['Time_roat'] = $this->Time_2->sub($this->Time_1);
                $Train[$i][0]['Time_1'] = $Time1["Time_of_departure"];
                $Train[$i][0]['Time_2'] = $Time2["Arrival_time"];
                $Train[$i][0]['dat'] = $this->Object["dat"];
            }
            
            if ($i % 2 == 0) {
                //ChromePhp::log($Train[$i - 1][0]['Time_2']);
                //ChromePhp::log($Train[$i][0]['Time_1']);
                //
                $this->Time_1->SetTime($Train[$i - 1][0]['Time_2']);
                $this->Time_2->SetTime($Train[$i][0]['Time_1']);
                
                $Train[$i][0]['Time_Expectation'] = $this->Time_2->sub($this->Time_1);
                
                if ($this->Time_2->CheckData()) {
                    $Train[$i][0]['dat'] = $Ob->NewDat($this->Object["dat"]);
                }
                else {
                    $Train[$i][0]['dat'] = $this->Object["dat"];
                }
            }
            //ChromePhp::log($Train[$i]);
        }

        return $Train;
    }
    
    private function STransfer($ObjectBD, $Train) {
        $Result[0] = 0;
        $stringType = "";
        $Ob = new Route();
        $Idat = $Ob->NewDat($this->Object["dat"]);
        //ChromePhp::log("Idat =",$Idat);
        
        for ($i = 0; $i < $Train[0]; $i++) {
            //ChromePhp::log("Train[1][$i] =",$Train[1][$i]);
            
            $AR = "";
            $stringType = $this->SV($stringType);
            $Inc = $Train[1][$i]['NS'];
            if ($ObjectBD->SELECT("Arrival_time", "Station_Train", "idTrain = ". $Train[1][$i]['id']. " AND Sequence_number = ". $Inc,"", "")) {
               $row = $ObjectBD->GetResult()->fetchArray();
               $buff =  $row['Arrival_time'];
            }
            
            for ($q = 0; $q < 5; $q++) {
                if ($buff[$q] != ':') {
                    $AR .= $buff[$q];
                }
            }
            $Inc++;
            //ChromePhp::log("AR =",$AR);
            //ChromePhp::log("Inc =",$Inc);
            
            if ($ObjectBD->SELECT("Number_of_stations", "Train", "idTrain = ". $Train[1][$i]['id'],"", "")) {
                $row = $ObjectBD->GetResult()->fetchArray();
            }
            $ColST = $row['Number_of_stations'];
            
            for ($q = $Inc; $q <= $ColST; $q++) {
                $n = 0;
            //ChromePhp::log("Station_Train.Sequence_number = ". $q);
                
                if ($ObjectBD->SELECT("Train.idTrain AS 'Train', Station_Train.Sequence_number AS 'Sequence_number'", "Train JOIN Schedul ON Train.idTrain = Schedul.idTrain_S JOIN Station_Train ON Station_Train.idTrain = Train.idTrain", "(Schedul.Start = '". $this->Object["dat"]. "' OR Schedul.Start = '". $Idat. "') AND (". $stringType. ") AND Station_Train.idStation_Train = (Select idStation_Train From Station_Train WHERE Station_Train.idTrain = ". $Train[1][$i]['id']. " AND Station_Train.Sequence_number = ". $q. ")","", "")) {
                    $size = $ObjectBD->GetSize();
                    while ($row = $ObjectBD->GetResult()->fetchArray()) {
                        $T[$n]['id'] = $row['Train'];
                        $T[$n]['NS'] = $row['Sequence_number'];
                        
                        //ChromePhp::log("T[$n]['id']] =",$T[$n]['id']);
                        //ChromePhp::log("T[$n]['NS'] =",$T[$n]['NS']);
                        
                        $n++;
                    }
                    //ChromePhp::log("size =",$size);
                    
                    for ($n = 0; $n < $size; $n++) {
                        
                        if ($ObjectBD->SELECT("Station_Train.Sequence_number AS 'Sequence_number'", "Station JOIN Station_Train ON Station_Train.idStation_Train = Station.idStation", "Station_Train.idTrain = ". $T[$n]['id']. " AND Station.Name_S = '".$this->Object["St_2"]. "'","", "")) {
                            $row = $ObjectBD->GetResult()->fetchArray();
                            
                            //ChromePhp::log("row['Sequence_number'] =",$row['Sequence_number']);
                        //ChromePhp::log("T[$n]['NS'] =",$T[$n]['NS']);

                            if ($row['Sequence_number'] < $T[$n]['NS']) {
                                unset($T[$n]);
                            }
                            else {
                            
                                $FlagT = false;

                                // перевірка на тип
                                if (!$FlagT) {
                                    if ($this->Object["t1"] != null) {
                                        $FlagT = $this->SelT($ObjectBD,1, $T[$n]['id'], $FlagT);
                                    }
                                    if ($this->Object["t2"] != null && !$FlagT) {
                                        $FlagT = $this->SelT($ObjectBD,2, $T[$n]['id'], $FlagT);
                                    }
                                    if ($this->Object["t3"] != null && !$FlagT) {
                                        $FlagT = $this->SelT($ObjectBD,3, $T[$n]['id'], $FlagT);
                                    }
                                    if ($this->Object["t4"] != null && !$FlagT) {
                                        $FlagT = $this->SelT($ObjectBD,4, $T[$n]['id'], $FlagT);
                                    }
                                    if ($this->Object["t5"] != null && !$FlagT) {
                                        $FlagT = $this->SelT($ObjectBD,5, $T[$n]['id'], $FlagT);
                                    }
                                    if ($this->Object["t6"] != null && !$FlagT) {
                                        $FlagT = $this->SelT($ObjectBD,6, $T[$n]['id'], $FlagT);
                                    }
                                }
                                if (!$FlagT) { //чи були знайдені  t
                                    unset($T[$n]);
                                }
                                else {
                                    if ($this->Object["dat"] == $Idat) {
                                        
                                        $DT = "";
                                        if ($ObjectBD->SELECT("Time_of_departure", "Station_Train", "idTrain = ". $T[$n]['id']. " AND Sequence_number = ". $T[$n]['NS'],"", "")) {
                                           $row = $ObjectBD->GetResult()->fetchArray();
                                           $buff =  $row['Time_of_departure'];
                                        }
                                        //ChromePhp::log($buff);

                                        for ($t = 0; $t < 5; $t++) {
                                            if ($buff[$t] != ':') {
                                                $DT .= $buff[$t];
                                            }
                                        }
                                        //ChromePhp::log("DT =",$DT);

                                        if ($DT <= $AT) {
                                            unset($T[$n]);
                                        }
                                        else {
                                            $Result[1][] = $Train[1][$i];
                                            $Result[2][] = $T[$n];
                                            $Result[0]++;
                                        }
                                    }
                                    else {
                                        $Result[1][] = $Train[1][$i];
                                        $Result[2][] = $T[$n];
                                        $Result[0]++;
                                    }
                                }
                            }
  
                            
                        } // if SN2
                    } // for N
                } // if SN1
            } //for Q
        } // for I
        
        if ($Result[0] == 0) {
            return -10;
        }
        //ChromePhp::log($Result);
        
        return $Result;
    }
    
    private function Transfer($ObjectBD) {
        $this->FlagV = false; //для перевірки виконання запиту
        $FlagT = false;
        $stringResult = "";
        $Result[0] = 0;
        $Result[1] = "";
        $stringType = "";
        $i = 0;
        
        $stringType = $this->SV($stringType);
        //ChromePhp::log("stringType =", $stringType);

        if ($ObjectBD->SELECT("Train.idTrain AS 'Train', Station_Train.Sequence_number AS 'Sequence_number'", "Train JOIN Schedul ON Train.idTrain = Schedul.idTrain_S JOIN Station_Train ON Station_Train.idTrain = Train.idTrain JOIN Station ON Station.idStation = Station_Train.idStation_Train", "Schedul.Start = '". $this->Object["dat"]. "' AND (". $stringType. ") AND Station.Name_S = '". $this->Object["St_1"]. "'","", "")) { //запит на дату та тип потягу
            
            $size = $ObjectBD->GetSize();
            //ChromePhp::log("size =", $size);
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $Train[$i]['id'] = $row['Train'];
                $Train[$i]['NS'] = $row['Sequence_number'];
                
                //ChromePhp::log("Train[$i]['id'] =", $Train[$i]['id']);
                //ChromePhp::log("Train[$i]['NS'] =", $Train[$i]['NS']);
                
                $i++;
            }
            $stringType = $Train;
            
            for ($i = 0; $i < $size; $i++) {
                $FlagT = false;

                // перевірка на тип
                if (!$FlagT) {
                    if ($this->Object["t1"] != null) {
                        $FlagT = $this->SelT($ObjectBD,1, $stringType[$i]['id'], $FlagT);
                    }
                    if ($this->Object["t2"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,2, $stringType[$i]['id'], $FlagT);
                    }
                    if ($this->Object["t3"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,3, $stringType[$i]['id'], $FlagT);
                    }
                    if ($this->Object["t4"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,4, $stringType[$i]['id'], $FlagT);
                    }
                    if ($this->Object["t5"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,5, $stringType[$i]['id'], $FlagT);
                    }
                    if ($this->Object["t6"] != null && !$FlagT) {
                        $FlagT = $this->SelT($ObjectBD,6, $stringType[$i]['id'], $FlagT);
                    }
                }
                if (!$FlagT) { //чи були знайдені  t
                    unset($stringType[$i]);
                }
            }
            
            // перевірка на станції
            /*$flag_Ca = false;

            for ($i = 0; $i < $size; $i++) {
                $Type1 = false;
                $Type2 = false;
                $Type3 = false;
                $Type4 = false;
                $Type5 = false;
                $Type6 = false;

                    if ($ObjectBD->SELECT("TypeTrain","Train","idTrain = ". $stringType[$i]['id'],"","")) {
                            $type = $ObjectBD->GetResult()->fetchArray()["TypeTrain"];
                     }
                    if ($type != "Електричка") {
                        //Carriage
                        if ($ObjectBD->SELECT("Type_C", "Train_Carriage", "idTrain = ". $stringType[$i]['id'],"", "")) {
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
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]['id']);
                            }
                            if ( $Type2 && !$flag_Ca) {
                                $NOS = 36;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]['id']);
                            }
                            if ( $Type3 && !$flag_Ca) {
                                $NOS = 36;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]['id']);
                            }
                            if ( $Type4 && !$flag_Ca) {
                                $NOS = 56;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]['id']);
                            }
                            if ( $Type5 && !$flag_Ca) {
                                $NOS = 60;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]['id']);
                            }
                            if ( $Type6 && !$flag_Ca) {
                                $NOS = 60;
                                $flag_Ca = $this->NOSFunc($NOS,$ObjectBD, $st1, $st2, $stringType[$i]['id']);
                            }
                        }
                    }
                else {
                    $flag_Ca = true;
                }
                if (!$flag_Ca) { //перевірка станцій
                     unset($stringType[$i]);
                }

            }*/
            $Result[0] = count($stringType);
            $Result[1] = $stringType;

            if ($Result[0] == 0) {
                $Result[0] = -3;
                return $Result;
            }
            else {
                //ChromePhp::log($Result);
                $Result = $this->STransfer($ObjectBD, $Result);
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
   
    private function ADD($ObjectBD) {
        $ObjectR = new Route();

        switch($this->Door) {
        case 1: {

             if ($ObjectBD->SELECT("idTrain", "Train", "idTrain = ". $this->Object["num"], "", "")) {
                 $row = $ObjectBD->GetResult()->fetchArray();
                 
                 $ObjectR->SetIdTrain($row["idTrain"]);

                 if ($ObjectR->GetIdTrain() == 0) {

                     if ($ObjectBD->SELECT("Name_S", "Station", "Station.Name_S in ('". $this->Object["St_1"]. "', '". $this->Object["St_2"]. "')","", "")) {
                         $row = $ObjectBD->GetResult()->fetchArray();
                         $r1 = $row["Name_S"];
                         $row = $ObjectBD->GetResult()->fetchArray();
                         $r2 = $row["Name_S"];

                         if ($r1 == "") {
                             return -13;
                         }
                         if ($r2 == "") {
                             return -14;
                         }

                         if ($ObjectBD->InsertTrain($this->Object["num"], $this->Object["St_1"], $this->Object["St_2"], $this->Object["Col"], $this->Object["Time"], $this->Object["Type"], $this->Object["Col_V"])) {
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
                $row = $ObjectBD->GetResult()->fetchArray();

                if ($row["idStation"] != 0) {
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
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $type = $row["TypeTrain"];
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
                        if ($i % 2 == 0) {
                            if ($ObjectBD->UPDATE("Carriage", "Station_number = Station_number","idCarriage = ". $this->Object["cod_V"]. " AND Place = 1")) {
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
        $Time1 = new TimeBase();
        $Time2 = new TimeBase();
        $NewDat = $this->Object["dat"];
        $NewDat[9] = $this->Object["dat"] - 1;
        
        if ($ObjectBD->SELECT("Station_Train.idTrain", "Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation", "Station.Name_S = '". $this->Object["St"]. "'","", "")) {
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $st[$i]['Train'] = $row["idTrain"];   
                $i++;
            }
            
            $col = count($st);
            
            for ($i = 0; $i < $col; $i++) {
                $flag = false;

            if ($ObjectBD->SELECT("Schedul.Start", "Train JOIN Schedul ON Train.idTrain = Schedul.idTrain_S", "Train.idTrain = ". $st[$i]['Train']. " AND (Schedul.Start = '". $this->Object["dat"]. "' OR Schedul.Start = '". $NewDat. "')","", "")) {
                    
            while ($row = $ObjectBD->GetResult()->fetchArray()) { 
                $st[$i]['Start'] = $row["Start"];
                $flag = true;
            }
            
            if (!$flag) {
                unset($st[$i]);
            }
            else {
                if ($ObjectBD->SELECT("Station_Train.Time_of_departure, Station_Train.Arrival_time, Station_Train.Sequence_number", "Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation", "(Station.Name_S = '". $this->Object["St"]. "' OR Station_Train.Sequence_number = 1) AND Station_Train.idTrain = ". $st[$i]['Train'],"", "")) {
                    
                    while ($row = $ObjectBD->GetResult()->fetchArray()) {
                        if ($row['Sequence_number'] == 1) {
                            $Time1->SetTime($row['Time_of_departure']);
                        }
                        else {
                            $Time2->SetTime($row['Arrival_time']);
                        }
                    }
                    $c = $Time2->subTime($Time1);
                    
                    ChromePhp::log($NewDat, $st[$i]['Start'], $this->Object["dat"], $Time2->CheckData());
                    
                    if ($st[$i]['Start'] == $this->Object["dat"]) {
                        if ($st[$i]['Train'] != 0 && !$Time2->CheckData()) {
                            $Result[1][$Result[0]] = $st[$i]['Train']; //запис номерів
                            $Result[0]++;
                        }
                    }
                    else {
                        if ($st[$i]['Train'] != 0 && $Time2->CheckData()) {
                            $Result[1][$Result[0]] = $st[$i]['Train']; //запис номерів
                            $Result[0]++;
                        }
                    }
                }
            }
        }
        else {
           $this->Error(-2, "StFu SidT| ". $ObjectBD->LastError());
        }
        }
    }

        if ($Result[0] == 0) {
             $Result[0] = -32;
             return $Result;
         }

        return $Result;
    }
    
    private function StationFile($Result, $ObjectBD) {
        $IRoute = 0;
        //$Buff;
        //Route::$size_route = $Result[0]; //кількість маршрутів

        for ($i = 0; $i < count($Result[1]); $i++ ) {
           
            if ($Result[1][$i] != " ") {
                $ObjectRoute[] = new Route();
                $num = $Result[1][$i];

                if ($ObjectBD->SELECT("Train.idTrain, Train.TypeTrain, Train.St_1, Train.St_2, Station.Name_S, Station_Train.Arrival_time, Station_Train.Time_of_departure", " Train JOIN Station_Train ON Train.idTrain = Station_Train.idTrain JOIN Station ON Station_Train.idStation_Train = Station.idStation", "Train.idTrain = ". $num. " AND Station.Name_S = '". $this->Object["St"]. "'","", "Station_Train.Sequence_number DESC")) {
                    
                    $row = $ObjectBD->GetResult()->fetchArray();
                        
                    $ObjectRoute[$IRoute]->SetIdTrain($row["idTrain"]);
                    $ObjectRoute[$IRoute]->SetType($row["TypeTrain"]);
                    $ObjectRoute[$IRoute]->SetSt1($row["St_1"]);
                    $ObjectRoute[$IRoute]->SetSt2($row["St_2"]);
                    $Buff["Name_S"] = $row["Name_S"];
                    $Buff["Arrival_time"] = $row["Arrival_time"];
                    $Buff["Time_of_departure"] = $row["Time_of_departure"];
                    
                    $ObjectRoute[$IRoute]->SetTime($Buff["Time_of_departure"], $Buff["Arrival_time"]);
                    $IRoute++;
                }
                else {
                    $this->Error(-1, "StF ST| ". $ObjectBD->LastError());
                    $Result[0] = -1;
                    return $Result;
                }
                $num = "";
            }
        }

        $Result[1] = "";
        //$mas;
        //$mas1;
        $i = $Result[0] - 1;
        for ($j = 0; $j <= $i; $j++) {
            $mas[$j] = $ObjectRoute[$j]->TimeInt();
            $mas1[$j] = $mas[$j] + 0;
        }
        
        $k = 1;

        while (($k!=0) && ($i>0))  {
          $k = 0;

          for ($j = 0; $j <= $i - 1; $j++) {
               if ($mas1[$j] > $mas1[$j+1]) {
                  $a = $mas1[$j];
                  $mas1[$j] = $mas1[$j+1];
                  $mas1[$j+1] = $a;
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

            if ($ObjectBD->SELECT("Sequence_number", "Station_Train", "idStation_Train = ". $this->Object["S"]. " AND idTrain = ". $num,"", "")) {
                $row = $ObjectBD->GetResult()->fetchArray();
                $Sn = $row["Sequence_number"];
                
                if ($ObjectBD->DELETE("Station_Train", "idTrain = ". $num. " AND idStation_Train = ". $this->Object["S"])) {
                    
                   if ($ObjectBD->UPDATE("Train", "Number_of_stations = Number_of_stations - 1","idTrain = ". $num)) {

                       if ($ObjectBD->SELECT("idTrain_Carriage", "Train_Carriage", "idTrain = ". $num,"", "")) {

                           while ($row = $ObjectBD->GetResult()->fetchArray()) {
                                $numC = $row["idTrain_Carriage"];
                               
                                if ($ObjectBD->DELETE("Carriage", " Station_number = ". $Sn. " AND idCarriage = ". $numC)) {

                                    if ($ObjectBD->UPDATE("Station_Train", "Sequence_number = Sequence_number - 1","Sequence_number > ". $Sn. " AND idTrain = ". $num)) {
                                        
                                        if ($ObjectBD->UPDATE("Carriage", "Station_number = Station_number - 1","Station_number > ". $Sn. " AND idCarriage = ". $numC)) {

                                    }
                                    else {
                                        $this->Error(-7, "DSC UC| ". $ObjectBD->LastError());
                                        return -7;
                                    }
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
        if ($this->Object["nC"] != "0") {

            if ($ObjectBD->DELETE("Carriage", "idCarriage = ". $this->Object["nC"])) {
                
                if ($ObjectBD->SELECT("Number_carriage", "Train_Carriage", "idTrain_Carriage = ". $this->Object["nC"],"", "")) {
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $Sn = $row["Number_carriage"];

                    if ($ObjectBD->DELETE("Train_Carriage", "idTrain_Carriage = ". $this->Object["nC"])) {

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
    
    private function SearchMapWayT($ObjectBD, $num, $num2) {
        //$Buff[0] = num;
        $i = 0;

        for ($j = 0; $j < 2; $j++) {
            if ($ObjectBD->SELECT("Station.Name_S, Station.Coordinates, Station_Train.Sequence_number", "Station, Station_Train", "Station_Train.idTrain = ". $num. " AND Station_Train.idStation_Train = Station.idStation", "", "Station_Train.Sequence_number")) {
                while ($row = $ObjectBD->GetResult()->fetchArray()) {
                    $Buff[$i]['Num'] = $num;
                    $Buff[$i]['Name_S'] = $row['Name_S'];
                    $Buff[$i]['Coordinates'] = $row['Coordinates'];
                    $Buff[$i]['Sequence_number'] = $row['Sequence_number'];
                    $i++;
                }  
                $num = $num2;
             }
             else {
                $this->Error(-2, "SMW SS| ". $ObjectBD->LastError());
                return -2;
             }
        }
        ChromePhp::log($Buff);
        return $Buff;
    }
    
    private function SearchMapWay($ObjectBD) {
        $ObjectRoute = new Route();
        $Buff = "";
        $i = 0;

        if ($ObjectBD->SELECT("Station.Name_S, Station.Coordinates, Station_Train.Sequence_number", "Station, Station_Train", "Station_Train.idTrain = ". $this->Object["Num"]. " AND Station_Train.idStation_Train = Station.idStation", "", "Station_Train.Sequence_number")) {
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $ObjectRoute->SetStation($row['Sequence_number'], $row['Coordinates'], $row['Name_S']);
            }  
            $Buff .= $ObjectRoute->WriteRouteMap($this->Object);
            
         }
         else {
            $this->Error(-2, "SMW SS| ". $ObjectBD->LastError());
            return -2;
         }
        ChromePhp::log($Buff);
        return $Buff;
    }
    
    private function Authentication($ObjectBD) {
        //$PIB;

        if ($ObjectBD->SELECT("PIB", "Input", "Login = '". $this->Object["login"]. "' AND Password = '". $this->Object["password"]. "'","", "")) {
            $row = $ObjectBD->GetResult()->fetchArray();
            $PIB = $row['PIB'];

            if ($PIB == "") {
                return -10;
            }
        }
        else {
            $this->Error(-1, "A SI| ". $ObjectBD->LastError());
            return -1;
        }

        return $PIB;
    }
    
    private function WriteFileLog($str) {
        $error = "Not Error";
        if (!$handle = fopen("LOGFile.log", "ab")) {
            $error = "C1 (Not open file)";
        }
        else {
            if (fwrite($handle, $str) === FALSE) {
                $error = "C11 (Not write file)";
            }
        }
        return $error;
    }

    private function Error($number, $String) {
        $this->WriteFile();  
        $error = "Not Error";
        if (!$handle = fopen("Error.log", "ab")) {
            $error = "C1 (Not open file)";
        }
        else {
            if (fwrite($handle, $number. "|". $String. "/\n") === FALSE) {
                $error = "C11 (Not write file)";
            }
        }
        return $error;
    }
    
    private function Edit($ObjectBD) {
        
        if ($ObjectBD->SELECT("idTrain", "Train", "idTrain = ". $this->Object["num"], "", "")) {
            $row = $ObjectBD->GetResult()->fetchArray();
            if ($row['idTrain'] != 0) {

                if ($this->Object["SR"] != 0) {
                    $St1 = 0;
                    $St2 = 0;
                    
                    if ($ObjectBD->SELECT("Name_S", "Station", "idStation = ". $this->Object["SR"], "", "")) {
                        $row = $ObjectBD->GetResult()->fetchArray();
                        $St1 = $row['Name_S'];
                        if ($ObjectBD->SELECT("Name_S", "Station", "idStation = ". $this->Object["SR2"], "", "")) {
                        $row = $ObjectBD->GetResult()->fetchArray();
                        $St2 = $row['Name_S'];
                            
                        if (!empty($St1) && !empty($St2)) {
                            ChromePhp::log("Sequence_number", "Station_Train", "idStation = ". $this->Object["SR2"]. " AND idTrain = ". $this->Object["num"]);
                        
                            if ($ObjectBD->SELECT("Sequence_number", "Station_Train", "idStation_Train = ". $this->Object["SR2"]. " AND idTrain = ". $this->Object["num"], "", "")) {
                                $row = $ObjectBD->GetResult()->fetchArray();

                                $numberSE = $row['Sequence_number'];
                                
                                if ($ObjectBD->UPDATE("Station_Train", "Sequence_number = Sequence_number + 1","Sequence_number > ". $numberSE. " AND idTrain = ". $this->Object["num"])) {
                                    if ($ObjectBD->SELECT("Number_of_stations", "Train", "idTrain = ". $this->Object["num"], "", "")) {
                                        $row = $ObjectBD->GetResult()->fetchArray();

                                        $numberT = $row['Number_of_stations'];
                                        
                                        if ($ObjectBD->UPDATE("Train", "Number_of_stations = Number_of_stations + 1","idTrain = ". $this->Object["num"])) {
                                            $T1 = new TimeBase();
                                            $T2 = new TimeBase();

                                            for ($i = $numberSE + 2; $i < $numberT; $i++) {
                                                if ($ObjectBD->SELECT("Arrival_time, Time_of_departure", "Station_Train", "idTrain = ". $this->Object["num"]. " AND Sequence_number = ". $i, "", "")) {
                                                    $row = $ObjectBD->GetResult()->fetchArray();

                                                    $T1->SetTime($row['Arrival_time']);
                                                    $T2->SetTime($row['Time_of_departure']);

                                                    $T1->add($this->Object["TADD"]);
                                                    $T2->add($this->Object["TADD"]);

                                                    if ($ObjectBD->UPDATE("Station_Train", "Arrival_time = '". $T1->GetTime(). "', Time_of_departure = '". $T2->GetTime(). "'","Sequence_number = ". $i. " AND idTrain = ". $this->Object["num"])) {
                                                    }
                                                    else {
                                                            $this->Error(-44, "ED UPST| ". $ObjectBD->LastError());
                                                            return -44;
                                                    }
                                                }
                                                else {
                                                    $this->Error(-43, "ED SST| ". $ObjectBD->LastError());
                                                    return -43;
                                                }
                                            }
                                            
                                            if ($ObjectBD->InsertST($this->Object["SR"], $this->Object["num"], $numberSE + 1, $this->Object["T1"], $this->Object["T2"])) {
                                                    return 0;
                                             }
                                            else {
                                                $this->Error(-46, "ED IST| ". $ObjectBD->LastError());
                                                return -46;
                                            }
                                        }
                                        else {
                                            $this->Error(-47, "ED UPT| ". $ObjectBD->LastError());
                                            return -47;
                                        }
                                    }
                                    else {
                                        $this->Error(-45, "ED ST| ". $ObjectBD->LastError());
                                        return -45;
                                    }
                                }
                                else {
                                    $this->Error(-35, "ED UPST| ". $ObjectBD->LastError());
                                    return -35;
                                }
                            }
                            else {
                                $this->Error(-25, "ED SST| ". $ObjectBD->LastError());
                                return -25;
                            }
                        }
                        else {
                            return -42;
                        }
                        }
                    }
                    

                }
                if ($this->Object["CR"] != 0) {

                    $fl = true;
                    $NOS = 0;

                    if ($ObjectBD->SELECT("Number_of_stations", "Train", "idTrain = ". $this->Object["num"], "", "")) {
                        $row = $ObjectBD->GetResult()->fetchArray();
                        $COL = $row["Number_of_stations"];
                        
                        $n = $this->Object["Type"];
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
                        $nom = 0;

                        if ($ObjectBD->SELECT("Number_carriage", "Train_Carriage", "idTrain = ". $this->Object["num"], "", "")) {
                            while($row = $ObjectBD->GetResult()->fetchArray()) {
                                $nom = $row['Number_carriage'];
                            }
                            $nom++;
                        }
                        if ($ObjectBD->InsertTC($this->Object["CR"], $this->Object["num"], $nom, $this->Object["Type"], $NOS)) {
                            if ($ObjectBD->SELECT("TypeTrain","Train","idTrain = ". $this->Object["num"],"","")) {
                                $row = $ObjectBD->GetResult()->fetchArray();
                                $type = $row["TypeTrain"];
                                if ($type == "Електричка") {
                                    $fl = false;
                                }
                            }

                            if ($fl) {

                                for ($i = 1; $i <= $COL; $i++) {

                                    for ($j = 1; $j <= $NOS; $j++) {

                                        if (!$ObjectBD->InsertCarriage($this->Object["CR"], $i, $j, "-")) {
                                            $this->Error(-36, "ED IC| ". $ObjectBD->LastError());
                                            return -36;
                                        }
                                    }
                                    if ($i % 2 == 0) {
                                        if ($ObjectBD->UPDATE("Carriage", "Station_number = Station_number","idCarriage = ". $this->Object["CR"]. " AND Place = 1")) {
                                            }
                                    }
                                }
                            }
                            if ($ObjectBD->UPDATE("Train", "Col_Carriage = Col_Carriage + 1","idTrain = ". $this->Object["num"])) {
                                ChromePhp::log("UP");
                                return 0;
                            }
                        }
                        else {
                           $this->Error(-35, "ED ITC| ". $ObjectBD->LastError());
                            return -35;
                        }
                    }
                    else {
                        $this->Error(-40, "ED SET| ". $ObjectBD->LastError());
                        return -40;
                    }

                }
                if ($this->Object["dat"] != 0) {

                    if ($ObjectBD->SELECT("Departure_time, Arrival_time", "Schedul", "idTrain_S = ". $this->Object["num"], "", "")) {

                        $row = $ObjectBD->GetResult()->fetchArray();
                        $T1 = $row['Departure_time'];
                        $T2 = $row['Arrival_time'];
                        ChromePhp::log($this->Object["num"], $this->Object["dat"], $T1, $T2);

                        if ($ObjectBD->InsertSchedule($this->Object["num"], $this->Object["dat"], $T1, $T2)) {
                            return 0;
                        }
                        else {
                           $this->Error(-4, "ED ISch| ". $ObjectBD->LastError());
                            return -4;
                        }
                    }
                    else {
                        $this->Error(-3, "ED SEch| ". $ObjectBD->LastError());
                        return -3;
                    }
                }
            }
            else {
                return -2;
            }
        }
        else {
            $this->Error(-1, "ED SET| ". $ObjectBD->LastError());
            return -1;
        }
        return 0;
    }
    
    private function Station($ObjectBD) {
        
        if ($this->Object["Way"] != 0) {
            if ($ObjectBD->SELECT("idStation", "Station", "idStation = ". $this->Object["ipST"], "", "")) {
                $row = $ObjectBD->GetResult()->fetchArray();
                
                if (!empty($row['idStation'])) {
                    if (!$ObjectBD->DELETE("Station", "idStation = ". $this->Object["ipST"]. "'")) {
                        $this->Error(-4, "S DS| ". $ObjectBD->LastError());
                        return -4;
                    }
                }
                else {
                    return -2;
                }
            }
            else {
                $this->Error(-1, "S SES| ". $ObjectBD->LastError());
                return -1;
            }
        }
        else {
            if ($ObjectBD->SELECT("idStation", "Station", "idStation = ". $this->Object["ipST"], "", "")) {
                $row = $ObjectBD->GetResult()->fetchArray();
                
                if (empty($row['idStation'])) {
                    if (!$ObjectBD->InsertStation($this->Object["ipST"], $this->Object["COST"], $this->Object["NAMEST"], $this->Object["BST"])) {
                           $this->Error(-3, "S ISS| ". $ObjectBD->LastError());
                            return -3;
                    }
                }
                else {
                    return -5;
                }
            }
             else {
                $this->Error(-6, "S SES| ". $ObjectBD->LastError());
                return -6;
            }
        }
        return 0;
    }
    
    private function SelectEmployment($ObjectBD, $date, $Num, $St2, $St1) {
        
        if ($ObjectBD->SELECT("MAX(Train_Carriage.Number_carriage) AS 'Number_of_seats'", "Train_Carriage", "Train_Carriage.idTrain = ". $Num, "", "")) {
            $row = $ObjectBD->GetResult()->fetchArray();
            $count = $row['Number_of_seats'];
                
            for ($i = 1; $i < $count + 1; $i++) {
                    
                if ($ObjectBD->SELECT("Carriage.Employment AS 'Employment', Carriage.Place AS 'Place', Train_Carriage.Number_of_seats AS 'Number_of_seats', Train_Carriage.Type_C AS 'Type_C', Carriage.Station_number AS 'Station_number'", "Carriage JOIN Train_Carriage ON Carriage.idCarriage = Train_Carriage.idTrain_Carriage", "Carriage.Station_number >= (SELECT Station_Train.Sequence_number From Station_Train Where Station_Train.idTrain = ". $Num. " AND Station_Train.idStation_Train = (SELECT Station_Train.idStation_Train From Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation Where Station.Name_S = '". $St1. "')) AND Carriage.Station_number < (SELECT Station_Train.Sequence_number From Station_Train Where Station_Train.idTrain = ". $Num. " AND Station_Train.idStation_Train = (SELECT Station_Train.idStation_Train From Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation Where Station.Name_S = '". $St2. "')) AND Carriage.idCarriage = (SELECT Train_Carriage.idTrain_Carriage From Train_Carriage Where Train_Carriage.Number_carriage = ". $i. " AND Train_Carriage.idTrain = ". $Num. ")", "", "")) {
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $count2 = $row['Number_of_seats'];
                    $type = $row['Type_C'];
                    $mas[0]['Place'] = $row['Place'];
                    $mas[0]['Employment'] = $row['Employment'];
                    $mas[0]['Station_number'] = $row['Station_number'];
                    $n = 1;
                    //ChromePhp::log("mas", $mas);

                    while ($row = $ObjectBD->GetResult()->fetchArray()) {
                        $mas[$n]['Place'] = $row['Place'];
                        $mas[$n]['Employment'] = $row['Employment'];
                        $mas[$n]['Station_number'] = $row['Station_number'];
                        $word = "";
                        
                        if ($mas[$n]['Employment'] != '-') {
                            for ($h = 0; $h < count($mas[$n]['Employment']); $h++) {
                                if ($mas[$n]['Employment'][$h] == ',') {
                                    if ($word == $date) {
                                        $mas[$n]['Employment'] = "+";
                                        break;
                                    }
                                    $word = "";
                                }
                                else {
                                    $word .= $mas[$n]['Employment'][$h];
                                }
                            }
                            
                            if ($mas[$n]['Employment'] != '+') {
                                $mas[$n]['Employment'] = "-";
                            }
                        }
                        $n++;     
                        //ChromePhp::log("mas", $mas);
                    }
                    //ChromePhp::log("mas", $mas);
                    
                    $min = $mas[0]['Station_number'];
                    $max = $mas[0]['Station_number'];
                    
                    for ($q = 0; $q < $n; $q++) {
                        if ($mas[$q]['Station_number'] < $min) {
                            $min = $mas[$q]['Station_number'];
                        }
                        if ($mas[$q]['Station_number'] > $max) {
                            $max = $mas[$q]['Station_number'];
                        }
                    }
                    $col = 0;
                    
                    $k = 1;
                    $r = count($mas);

                    while (($k!=0) && ($r>0))  {
                      $k = 0;

                      for ($j = 0; $j <= $r - 1; $j++) {
                           if ($mas[$j]['Place'] > $mas[$j+1]['Place']) {
                              $a = $mas[$j];
                              $mas[$j] = $mas[$j+1];
                              $mas[$j+1] = $a;
                              $k = 1;
                            }
                      }
                     $r--;
                     }                    
                        
                        /*for ($q = $min; $q < $max + 1; $q++) {
                            
                            if ($mas[$r]['Employment'] == '-' || ) {
                                        //$flag = false;
                            }
                        }*/
                    $q = 0;
                    $flour = 0;
                    //ChromePhp::log($count2, $flour, $mas);
                    for ($h = 1; $h < $count2 + 1; $h++) {
                        $q = 0;   
                        for ($r = $flour; $r < $n + 1; $r++) {
                            if ($h == $mas[$r]['Place'] && $mas[$r]['Employment'] == '-') {
                                $q++;
                                $flour = $r;
                            }
                            //if ($h == $mas[$r]['Place'] && $mas[$r]['Employment'] == '-' && $mas[$r]['Place'] == 10) {
                                //ChromePhp::log("q", $q, $r);
                            //}
                            
                        }
                        //ChromePhp::log("h and q", $h, $q);
                        $size = count($mas);
                        if ($q < ($max - $min + 1)) {
                           for ($r = 0; $r < $size; $r++) {
                               //ChromePhp::log($r);
                                if ($h == $mas[$r]['Place']) {
                                    unset($mas[$r]);
                                    //ChromePhp::log($mas[$r]['Place']);
                                }
                            } 
                        }
                        else {
                            $free[$i][] = $h;
                            $col++;
                        }
                    }
                }
                else {
                    $this->Error(-1, "IP SC| ". $ObjectBD->LastError());
                    return -1;
                }
                
                $typeCar[$i]['Type'] = $type;
                $typeCar[$i]['Col'] = $col;
                    
            }
            if ($this->Door != 24) {
                if (empty($free)) {
                    return -3;
                } 
            } else {
                $i = 0;
                if ($ObjectBD->SELECT("Type_C", "Train_Carriage", "idTrain = ". $Num, "", "")) {
                        while ($row = $ObjectBD->GetResult()->fetchArray()) {
                            $typeCar[$i]['Type'] = $row['Type_C'];
                            ChromePhp::log($typeCar[$i]['Type']);
                            $i++;
                        }
                    }
            }
                
            $Result[0] = $typeCar;
            $Result[1] = $free;  
            ChromePhp::log("Result", $Result);
            
            return $Result;
        }
        else {
            $this->Error(-2, "IP SC| ". $ObjectBD->LastError());
            return -2;
        }
    }
    
    private function InfoPay($ObjectBD) {
        //ChromePhp::log($this->Object, count($this->Object));
        if (count($this->Object) > 6) {
            for ($i = 0; $i < 10; $i++) {
                $date1[$i] = $this->Object['Time11'][$i];
                $date2[$i] = $this->Object['Time21'][$i];
            }
            //ChromePhp::log($this->Object);
            
            $Result = $this->SelectEmployment($ObjectBD, $date1, $this->Object['Num1'], $this->Object['St_2'], $this->Object['St_1']);
            
            if ($Result < 0) {
                return $Result;
            }
            //ChromePhp::log($Result);
            $Result2 = $this->SelectEmployment($ObjectBD, $date2, $this->Object['Num2'], $this->Object['St_3'], $this->Object['St_2']);
            ChromePhp::log($Result);
            if ($Result2 < 0) {
                return $Result2;
            }
            $Result[2] = $Result2[0];
            $Result[3] = $Result2[1];
            
            if ($this->Door == 24) {
                //ChromePhp::log($Result);
                $Result[4] = $this->DateList($ObjectBD, $this->Object['Num1']);
                $Result[5] = $this->DateList($ObjectBD, $this->Object['Num2']);
                //ChromePhp::log($Result);
                $Result[6] = $this->StationList($ObjectBD, $this->Object['Num1']);
                $Result[7] = $this->StationList($ObjectBD, $this->Object['Num2']);
            }
        }
        else {
            //ChromePhp::log($this->Object);
            
            for ($i = 0; $i < 10; $i++) {
                $date[$i] = $this->Object['Time11'][$i];
            }
            
            $Result = $this->SelectEmployment($ObjectBD, $date, $this->Object['Num1'], $this->Object['St_1'], $this->Object['St_2']);
            
            if ($this->Door == 24) {
                $Result[2] = $this->DateList($ObjectBD, $this->Object['Num1']);
                $Result[3] = $this->StationList($ObjectBD, $this->Object['Num1']);
            }
        }
        return $Result;
    }
    
    private function Many($ObjectBD) {            
            if ($ObjectBD->SELECT("Coordinates", "Station", "Name_S = '". $this->Object['St_1']. "' OR Name_S = '". $this->Object['St_2']. "'", "", "")) {
                
                $i = 0;
                while ($row = $ObjectBD->GetResult()->fetchArray()) {
                    $buff[$i] = $row['Coordinates'];
                    $i++;
                }
                
                for ($i = 0; $i < 2; $i++) {
                    $min = explode(', ', $buff[$i]);                     
                    $Coordinates[$i]['Lat'] = $min[0]; 
                    ChromePhp::log($min);
                    
                   /* for ($n = 1; $n < count($min[1]) - 1; $n++) {
                        $Coordinates[$i]['Lng'][$n - 1] = $min[1][$n];
                    }*/
                    $Coordinates[$i]['Lng'] = $min[1]; 
                    ChromePhp::log($min);
                }
                
                $km = $this->getDistance($Coordinates[0]['Lat'], $Coordinates[0]['Lng'], $Coordinates[1]['Lat'], $Coordinates[1]['Lng']) / 3500;
                ChromePhp::log($km);
                
                if ($ObjectBD->SELECT("TypeTrain", "Train", "idTrain = ". $this->Object['Num1'], "", "")) {
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $TypeTrain = $row['TypeTrain'];
                    
                    switch($TypeTrain) {
                        case 'Швидкісний': {
                            $km *= 1.5;
                            break;
                        }
                        case 'Фірмовий': {
                            $km *= 1.3;
                            break;
                        }
                        case 'Швидкий': {
                            $km *= 1.2;
                            break;
                        }
                        case 'Пасажирський': {
                            $km *= 1.05;
                            break;
                        }
                    }
                    
                    switch($this->Object['$TypeTrain']) {
                        case 'СВ': {
                            $km *= 1.6;
                            $km += 30;
                            break;
                        }
                        case 'Люкс': {
                            $km *= 2;
                            $km += 30;
                            break;
                        }
                        case 'Купе': {
                            $km *= 1.4;
                            $km += 30;
                            break;
                        }
                        case 'Плацкарт': {
                            $km *= 1.25;
                            $km += 30;
                            break;
                        }
                        case 'Сидячий 1-го класу': {
                            $km *= 1.15;
                            break;
                        }
                        case 'Сидячий 2-го класу': {
                            $km *= 1.05;
                            break;
                        }
                    }
                    
                    return $km;
                }
            }
        return $Result;
    }
    
    private function Reserve($ObjectBD) {
        for ($i = 0; $i < 10; $i++) {
            $date[$i] = $this->Object['Time11'][$i];
        }
        ChromePhp::log($this->Object['masNum'][$i]);
        for ($i = 0; $i < count($this->Object['masNum']); $i++) {
            
            ChromePhp::log($date);
            ChromePhp::log($this->Object['masNum'][$i]);
            
            ChromePhp::log("Carriage", "Employment = '". $date. ", '","idCarriage = (SELECT idTrain_Carriage FROM Train_Carriage WHERE idTrain = ". $this->Object['Num1']. " AND Number_carriage = ". $this->Object['NumberVagon']. ") AND Place = ". $this->Object['masNum'][$i]. " AND Station_number >= (SELECT Station_Train.Sequence_number From Station_Train Where Station_Train.idTrain = ". $this->Object['Num1']. " AND Station_Train.idStation_Train = (SELECT Station_Train.idStation_Train From Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation Where Station.Name_S = '". $this->Object['St_2']. "')) AND Station_number < (SELECT Station_Train.Sequence_number From Station_Train Where Station_Train.idTrain = ". $this->Object['Num1']. " AND Station_Train.idStation_Train = (SELECT Station_Train.idStation_Train From Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation Where Station.Name_S = '". $this->Object['St_1']. "'))");
            
            if ($ObjectBD->UPDATE("Carriage", "Employment = '". $date. ", '","idCarriage = (SELECT idTrain_Carriage FROM Train_Carriage WHERE idTrain = ". $this->Object['Num1']. " AND Number_carriage = ". $this->Object['NumberVagon']. ") AND Place = ". $this->Object['masNum'][$i]. " AND Station_number >= (SELECT Station_Train.Sequence_number From Station_Train Where Station_Train.idTrain = ". $this->Object['Num1']. " AND Station_Train.idStation_Train = (SELECT Station_Train.idStation_Train From Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation Where Station.Name_S = '". $this->Object['St_2']. "')) AND Station_number < (SELECT Station_Train.Sequence_number From Station_Train Where Station_Train.idTrain = ". $this->Object['Num1']. " AND Station_Train.idStation_Train = (SELECT Station_Train.idStation_Train From Station_Train JOIN Station ON Station_Train.idStation_Train = Station.idStation Where Station.Name_S = '". $this->Object['St_1']. "'))")) {

            }
        }
    }
    
    private function StationList($ObjectBD, $Num) {
        $i = 0;
       if ($ObjectBD->SELECT("Sequence_number, idStation_Train, Arrival_time, Time_of_departure", "Station_Train", "idTrain = ". $Num, "", "Sequence_number ASC")) {
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $Station[$i]['Sequence_number'] = $row['Sequence_number'];
                $Station[$i]['idStation_Train'] = $row['idStation_Train'];
                $Station[$i]['Arrival_time'] = $row['Arrival_time'];
                $Station[$i]['Time_of_departure'] = $row['Time_of_departure'];
                
                $i++;
            }
           
           for ($i = 0; $i < count($Station); $i++) {
               if ($ObjectBD->SELECT("Name_S", "Station", "idStation = ". $Station[$i]['idStation_Train'], "", "")) {
                   $row = $ObjectBD->GetResult()->fetchArray();
                   $Station[$i]['Name_S'] = $row['Name_S'];
               }
           }
           return $Station;
           
       }
        else {
            $this->Error(-1, "SST SL| ". $ObjectBD->LastError());
            return -1;
        }
    }
    
    private function DateList($ObjectBD, $Num) {
        $i = 0;
       if ($ObjectBD->SELECT("Start", "Schedul", "idTrain_S = ". $Num, "", "")) {
            while ($row = $ObjectBD->GetResult()->fetchArray()) {
                $date[$i] = $row['Start'];        
                $i++;
            }
           $year = $this->Object['Date'][0]. $this->Object['Date'][1]. $this->Object['Date'][2]. $this->Object['Date'][3];
           $month = $this->Object['Date'][5]. $this->Object['Date'][6];
           $day = $this->Object['Date'][8]. $this->Object['Date'][9];
           
           $size = count($date);
           
           for ($i = 0; $i < $size; $i++) {
               $y = $date[$i][0]. $date[$i][1]. $date[$i][2]. $date[$i][3];
               $m = $date[$i][5]. $date[$i][6];
               $d = $date[$i][8]. $date[$i][9];
               
               if ($y < $year) {
                   unset($data[$i]);
               } 
               else {
                   if ($y == $year) {
                       if ($m < $month) {
                           unset($data[$i]);
                       }
                       else {
                           if ($m == $month) {
                               if ($d < $day) {
                                    unset($data[$i]);
                               }
                           }
                       }                       
                   } 
               }
           }
           return $date;
       }
        else {
            $this->Error(-1, "SS DL| ". $ObjectBD->LastError());
            return -1;
        }
    }
    
    private function NewToken($ObjectBD) {
        $max = 0;
       // Red = new Redis();
        /*if (Red->Exists($this->Object["ip"])) {
            if (Red->Get($this->Object["ip"]) == "1") {*/
                if ($ObjectBD->SELECT("MAX(Token) AS 'Token'", "Payment", "", "", "")) {
                    $row = $ObjectBD->GetResult()->fetchArray();
                    $max = $row["Token"];
                    $max++;
                    
                    if ($ObjectBD->InsertPayment($max, "", "No", $this->Object["Time"])) {
                        return $max;
                    }
                    else {
                        $this->Error(-2, "IP NT| ". $ObjectBD->LastError());
                        return -2;
                    }
                }
        else {
            $this->Error(-1, "SP NT| ". $ObjectBD->LastError());
            return -1;
        }
        
         //   }
        //}
    }
    
    private function Verification($ObjectBD) {
        $flag = false;
        $password = null;
        
        while (!$flag) {
            $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
            $max=20;
            $size=StrLen($chars)-1; 
            $password=null; 
            while($max--) {
                $password.=$chars[rand(0,$size)];
            }
            
            if ($ObjectBD->SELECT("Token", "Payment", "Code = '". $password. "'", "", "")) {
                $row = $ObjectBD->GetResult()->fetchArray();
                if (empty($row['Token'])) {
                    $flag = true;
                }
            }
        }
        
        if ($ObjectBD->UPDATE("Payment", "Code = '". $password. "', Pay = 'Yes'","Token = ". $this->Object["Token"])) {
            return $password;
        }
        else {
            $this->Error(-1, "UP V| ". $ObjectBD->LastError());
            return -1;
        }
    }
    
    // Расстояние в метрах между двумя точками
    private function getDistance($lat1, $lon1, $lat2, $lon2) {
        ChromePhp::log($lat1, $lon1, $lat2, $lon2);
      $lat1 *= pi() / 180;
      $lat2 *= pi() / 180;
      $lon1 *= pi() / 180;
      $lon2 *= pi() / 180;

      $d_lon = $lon1 - $lon2;

      $slat1 = sin($lat1);
      $slat2 = sin($lat2);
      $clat1 = cos($lat1);
      $clat2 = cos($lat2);
      $sdelt = sin($d_lon);
      $cdelt = cos($d_lon);

      $y = pow($clat2 * $sdelt, 2) + pow($clat1 * $slat2 - $slat1 * $clat2 * $cdelt, 2);
      $x = $slat1 * $slat2 + $clat1 * $clat2 * $cdelt;

      return atan2(sqrt($y), $x) * 6372795;
    }
    
    public function HallWay($POST) {
        $this->Object = $POST;
        $this->Door = $POST['C'];
        //
        //ChromePhp::log($this->Object);
        
        $stringResult = "";
        $ObjectBD = new ProjectBD();
        $ObjectBD->createConnection();

        switch($this->Door) {
            case 0: {
                
                if ($this->Object['trans'] == 1) {
                    $stringResult = $this->TypeFunc($ObjectBD); //пошук маршрутів (1)
                    ChromePhp::log($stringResult);
                    
                    if ($stringResult[0] > 0) {
                       $stringResult = $this->FormationFile($stringResult, $ObjectBD); //запис маршрутів
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
                }
                else {
                    $stringResult = $this->Transfer($ObjectBD); //пошук маршрутів (1)

                    if ($stringResult[0] > 0) {
                       $stringResult = $this->TransferFile($stringResult, $ObjectBD); //запис маршрутів
                       if ($stringResult[0] < 0) {
                           $this->Error($stringResult[1], "Simakin flew");
                           $stringResult[1] = "Not Train";
                       }
                        else {
                            return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
                            $stringResult[1] .= "$";
                        }
                    }
                    else {
                        $stringResult[1] = "Not Train";
                    }
                }
                return $stringResult[1];
            }
            
            case 10: {
            $stringResult = $this->TrainFunc($ObjectBD); //пошук маршрутів (1)
                ChromePhp::log($stringResult);

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
                //ChromePhp::log($stringResult);
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
                
                //ChromePhp::log($stringResult);

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
                        case -32: {
                            $stringResult[1] = "Not dat";
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

            }
            case 6: {
                //ChromePhp::log('start SC');
                $StationInput = $this->StationCheck($ObjectBD);
                $col = 0;
                foreach ($StationInput as $i => $value) {
                    $col++;
                    //ChromePhp::log($col);
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

        case 7: {
            $size = $this->DeleteNumber($ObjectBD);
            $fout = "true";
            if ($size != 0) {
                $fout = "Not Delete (Error DataBase)";
            }
            return $fout;
        }
        case 8: {
            $size = $this->DeleteSC($ObjectBD);
            $fout = "true";
            if ($size != 0) {
                $fout = "Not Delete (Error DataBase)";
            }
            return $fout;
        }
        case 9: {
            $size = $this->DeleteSh($ObjectBD);
            $fout = "true";
            if ($size != 0) {
                $fout = "Not Delete (Error DataBase)";
            }
            return $fout;
        }
        case 111: {
            $p = $this->Authentication($ObjectBD);
                if ($p == -10) {
                    $fout = "Not Authentication";
                }
                else {
                    if ($p == -1) {
                        $fout = "Error server";
                    }
                    else {
                        $fout = "true";
                        $fout2 = "Signed in [". $p. "]\n";
                        $fout2 = $this->WriteFileLog($fout2);
                    }
                }
            return $fout;
        }
        case 20: {
            $stringResult = $this->SearchMapWay($ObjectBD);
            if ($stringResult == -2) {
                 $this->Error($stringResult, "Simakin flew");
                $stringResult = "Error map";
             }
             else {
                  $stringResult .= "$";
             }
            return $stringResult;
        }
        case 21: {
            $stringResult = $this->SearchMapWayT($ObjectBD, $this->Object['Num1'], $this->Object['Num2']);
            if ($stringResult == -2) {
                 $this->Error($stringResult, "Simakin flew");
                $stringResult = "Error map";
             }
             else {
                 return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
                  //$stringResult .= "$";
             }
            return $stringResult;
        }
        case 22: {
            $stringResult = $this->InfoPay($ObjectBD);
            if ($stringResult == -1) {
                 $this->Error($stringResult, " 22Error DB");
                $stringResult = "Error DB";
             }
            if ($stringResult == -2) {
                 $this->Error($stringResult, " 22Error DB");
                $stringResult = "Error DB";
             }
            if ($stringResult == -3) {
                $stringResult = "Not Place";
             }
             else {
                 return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
                  //$stringResult .= "$";
             }
            return $stringResult;
        }
        case 23: {
            $stringResult = $this->Many($ObjectBD);
            if ($stringResult < 0) {
                 $this->Error($stringResult, " 23Error DB");
                $stringResult = "Error DB";
             }
             else {
                 return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
                  //$stringResult .= "$";
             }
            return $stringResult;
        }
        case 24: {
            $stringResult = $this->InfoPay($ObjectBD);
            if ($stringResult == -1) {
                 $this->Error($stringResult, " 24Error DB");
                $stringResult = "Error DB";
             }
            if ($stringResult == -2) {
                 $this->Error($stringResult, " 24Error DB");
                $stringResult = "Error DB";
             }
            if ($stringResult == -3) {
                $stringResult = "Not Place";
             }
             else {
                 return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
                  //$stringResult .= "$";
             }
            return $stringResult;
        }
        case 12: {
            $size = $this->Edit($ObjectBD);
            $fout = "true";
            if ($size != 0) {
                $fout = "(Error DataBase)";
                if ($size == -2) {
                    $fout = "Нема такого номера потягу";
                }
                if ($size == -42) {
                    $fout = "Нема такого коду станції";
                }
                if ($size == -35) {
                    $fout = "Такий вагон все є";
                }
            }
            return $fout;
        }
        case 13: {
            $StationInput = $this->StationCODCheck($ObjectBD);
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
                
        case 14: {
            $size = $this->Station($ObjectBD);
            $fout = "true";
            if ($size != 0) {
                $fout = "(Error DataBase)";
                if ($size == -2) {
                    $fout = "Нема такого коду станції";
                }
                if ($size == -5) {
                    $fout = "Така станція вже є";
                }
            }
            return $fout;
        }
                
        case 50: {
            $stringResult = $this->NewToken($ObjectBD);
            if ($stringResult < 0) {
                 $this->Error($stringResult, " 50Error DB");
                $stringResult = "Error DB";
             }
             /*else {
                 return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
             }*/
            return $stringResult;
        }
                
        case 48: {
            $stringResult = $this->Verification($ObjectBD);
            if ($stringResult < 0) {
                 $this->Error($stringResult, " 48Error DB");
                $stringResult = "Error DB";
             }
             else {
                 return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
             }
            return $stringResult;
        }
        case 49: {
            $this->Object["masNum"] = json_decode($this->Object["masNum"]);
            
            //for ($i = 0; $i < $Object["Col"]; $i++)
            $this->Object["v"] = json_decode($this->Object["v"]);
            
            $this->Reserve($ObjectBD);
            
            $ObjPDF = new PDF();
            $ObjPDF->CreateFile();
            return $ObjPDF->PdfFormation($ObjectBD, $this->Object);
            //$stringResult = $ObjPDF->PdfFormation($ObjectBD, $this->Object);
            /*if ($stringResult < 0) {
                 $this->Error($stringResult, " 49Error DB");
                $stringResult = "Error DB";
             }
             else {
                 //return json_encode($stringResult, JSON_UNESCAPED_UNICODE);
                 return $stringResult;
             }
            return $stringResult;*/
        }

            default: {
                return -1;
            }
        }
        return 0;
    }
}
 
?>
