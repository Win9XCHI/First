<?php

final class ProjectBD {
    private $mysqli;
    private $query;
    private $result;
    private $sqliteerror;

    public function ProjectBD() {
        $this->mysqli = null;
        $this->query = null;
        $this->result = null;
    }

    public function createConnection() {
        $error = "";
        $this->mysqli = new SQLite3("Project.sqlite");
        return $error;
    }
    public function LastError() {
        return $this->mysqli->lastErrorMsg();
    }
    public function GetSize() {
        $i = 0;
        while ($this->GetResult()->fetchArray()) {
                $i++;
        }
        $this->GetResult()->reset();
        return $i;
    }
    public function GetResult() {
        return $this->result;
    }
    public function DELETE($table_name, $definition) {
        $flag = true;

        if ($table_name != "" && $definition != "" ) {
            if ($definition != "") {
                if(!$this->mysqli->exec("DELETE FROM ". $table_name. " WHERE ". $definition. ";")) {
                    $flag = false;
                }
            }
            else {
                if(!$this->mysqli->exec("DELETE FROM ". $table_name. ";")) {
                    $flag = false;
                }
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function SELECT($column, $table, $definition, $limit, $Order_by) {
        $flag = true;

        if ($table != "" ) {

                if ($limit == "") {

                    if ($Order_by == "") {
                        if ($definition == "") {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " ;");
                        }
                        else {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " WHERE ". $definition. " ;");

                        }
                    }
                    else {
                        if ($definition == "") {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " ORDER BY ". $Order_by. ";");
                        }
                        else {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " WHERE ". $definition. " ORDER BY ". $Order_by. ";");
                        }
                    }
                }
                else {
                    if ($Order_by == "") {
                        if ($definition == "") {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " LIMIT ". $limit. " ;");
                        }
                        else {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " WHERE ". $definition. " LIMIT ". $limit + ";");
                        }
                    }
                    else {
                        if ($definition == "") {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " LIMIT ". $limit. " ORDER BY ". $Order_by. ";");
                        }
                        else {
                            $this->result = $this->mysqli->query("SELECT ". $column. " FROM ". $table. " WHERE ". $definition. " LIMIT ". $limit. " ORDER BY ". $Order_by. ";");
                        }
                    }
                }
            }
            else {
                $flag = false;
            }

            return $flag;
    }
    
    public function UPDATE($table, $value, $definition) {
        $flag = true;

        if ($table != "" && $value != "") {

            if ($definition != "") {
                if(!$this->mysqli->exec("UPDATE ". $table. " SET ". $value. " WHERE ". $definition. ";")) {
                    $flag = false;
                }
            }
            else {
                if(!$this->mysqli->exec("UPDATE ". $table. " SET ". $value. ";")) {
                    $flag = false;
                }
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }

    public function InsertTrain($idTrain, $St1, $St2, $Number_of_stations, $Time_on_road, $TypeTrain, $Col_Carriage) {
        $flag = true;

        if ($idTrain != "" && $St1 != "" && $St2 != "" && $Number_of_stations != "" && $Time_on_road != "") {
            
            if(!$this->mysqli->exec("INSERT INTO Train (idTrain, St_1, St_2, Number_of_stations, Time_on_the_road, TypeTrain, Col_Carriage) ".
                              "VALUES (". $idTrain. ", '". $St1. "', '". $St2. "', ". $Number_of_stations. ", '". $Time_on_road. "', '".  $TypeTrain. "', ". $Col_Carriage. ");")) {
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function InsertTC($idTrain_Carriage, $idTrain, $Number_carriage, $Type_C, $Number_of_seats) {
        $flag = true;

        if ($idTrain_Carriage != "" && $idTrain != "" && $Number_carriage != "" && $Type_C != "" && $Number_of_seats != "") {
            
            if(!$this->mysqli->exec("INSERT INTO  Train_Carriage (idTrain_Carriage, idTrain, Number_carriage, Type_C, Number_of_seats) ".
                              "VALUES (". $idTrain_Carriage. ", ". $idTrain. ", ". $Number_carriage. ", '". $Type_C. "', ". $Number_of_seats. ");")) {
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function InsertCarriage($idCarriage, $Station_number, $Place, $Employment) {
        $flag = true;

        if ($idCarriage != "" && $Station_number != "" && $Place != "" && $Employment != "" ) {
            
            if(!$this->mysqli->exec("INSERT INTO  Carriage (idCarriage, Station_number, Place, Employment) ".
                          "VALUES (". $idCarriage. ", ". $Station_number. ", ". $Place. ", '". $Employment. "');")) {
            
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function InsertST($idStation_Train, $idTrain, $Sequence_number, $Arrival_time, $Time_of_departure) {
        $flag = true;

        if ($idStation_Train != "" && $idTrain != "" && $Sequence_number != "" && $Arrival_time != "" && $Time_of_departure != "" ) {
            
            if(!$this->mysqli->exec("INSERT INTO  Station_Train (idStation_Train, idTrain, Sequence_number, Arrival_time, Time_of_departure) ".
                          "VALUES (". $idStation_Train. ", ". $idTrain. ", ". $Sequence_number. ", '". $Arrival_time. "', '". $Time_of_departure. "');")) {
            
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function InsertStation($idStation, $Coordinates, $Name_S, $Branch) {
        $flag = true;

        if ($idStation != "" && $Coordinates != "" && $Name_S != "" && $Branch != "") {
            
            if(!$this->mysqli->exec("INSERT INTO  Station (idStation, Coordinates, Name_S, Branch) ".
                          "VALUES (". $idStation. ", '". $Coordinates. "', '". $Name_S. "', '". $Branch. "');")) {
            
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function InsertSchedule($idTrain_S, $Start, $Departure_time, $Arrival_time) {
        $flag = true;

        if ($idTrain_S != "" && $Departure_time != "" && $Start != "" && $Arrival_time != "") {
            
            if(!$this->mysqli->exec("INSERT INTO  Schedul (idTrain_S, Start, Departure_time, Arrival_time) ".
                          "VALUES (". $idTrain_S. ", '". $Start. "', '". $Departure_time. "', '". $Arrival_time. "');")) {
            
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }
    public function InsertPayment($Token, $Code, $Pay, $Time) {
        $flag = true;

        if ($Token != "" && $Pay != "" && $Time != "") {
            
            if(!$this->mysqli->exec("INSERT INTO  Payment (Token, Code, Pay, Time) ".
                          "VALUES (". $Token. ", '". $Code. "', '". $Pay. "', '". $Time. "');")) {
            
                $flag = false;
            }
        }
        else {
            $flag = false;
        }

        return $flag;
    }

}

?>