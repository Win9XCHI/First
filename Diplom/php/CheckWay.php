<?php
include 'ChromePhp.php';
include ('SearchTrain.php');

ChromePhp::log('1');
    $Search = new SearchTrain();   
    echo $Search->HallWay($_POST); 
//echo $_POST;

               
    
               

    /*$error = "";
    if ($_POST['C'] == "0") {
        $content = 'St_1 '. $_POST['St_1']. ' '. 'St_2 '. $_POST['St_2']. ' '. 'dat '. $_POST['dat']. ' '. 'v1 '. $_POST['v1']. ' '. 'v2 '. $_POST['v2']. ' '. 'v3 '. $_POST['v3']. ' '. 'v4 '. $_POST['v4']. ' '. 'v5 '. $_POST['v5']. ' '. 't1 '. $_POST['t1']. ' '. 't2 '. $_POST['t2']. ' '. 't3 '. $_POST['t3']. ' '. 't4 '. $_POST['t4']. ' '. 't5 '. $_POST['t5']. ' '. 't6 '. $_POST['t6']. ' '. 'trans '. $_POST['trans']. ' '. 'sort '. $_POST['sort']. "\n";
    }

    if ($_POST['C'] == "5") {
            $content = 'C '. $_POST['C']. ' '. 'dat '. $_POST['dat']. ' '. 'St '. $_POST['St']. "\n";
    }
    if ($_POST['C'] == "6") {
            $content = 'C '. $_POST['C']. ' '. 'value '. $_POST['value']. "\n";
    }
    if ($_POST['C'] == "10") {
            $content = 'C '. $_POST['C']. ' '. 'Fil '. $_POST['Fil']. ' '. 'v1 '. $_POST['v1']. ' '. 'v2 '. $_POST['v2']. ' '. 'v3 '. $_POST['v3']. ' '. 'v4 '. $_POST['v4']. ' '. 'v5 '. $_POST['v4']. "\n";
    }


    if (!$handle = fopen("dataIndex.txt", "wb")) {
        $error = "C1 (Not open file)";
        exit;
    }
    else {
        if (fwrite($handle, $content) === FALSE) {
            $error = "C11 (Not write file)";
            exit;
        }
        else {
            fclose($handle);
            $programC = system('SearchWay.exe', $retval);
            echo $error;
            
            $stream = new FileReader("File.txt");
            echo $stream->ReadAll();
            exit;
        }
    }*/
?>