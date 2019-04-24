<?php

    include_once('../private/common/inicialization.php');
    include_once('../private/classes/data_class.php');
    
    $server_results['status'] = 'success';
    $server_results['message'] = '';

    if(!isset($_POST['data-verb'])){
        $server_results['status'] = 'error';
        $server_results['message'] = 'Error: No data-verb specified!';
    } 
    else{
        $data = new Data($mysqli);
        
        switch($_POST['data-verb']){
            case 'create': $server_results = $data->createData(); break;
            case 'show-data': $server_results = $data->readAllData(); break;
            default: $server_results['status'] = 'error';
                        $server_results['message'] = 'Error: Unknown data verb!';
        }
    }

    echo $server_results;

?>