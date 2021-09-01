<?php

function generatePdf($form, $fields){
    $command_string = "python3 generate_pdf.py ".$form." ";
    foreach ($fields as $key => $value) {
        $command_string .= $key.":".$value." ";
    }
    $command_string = escapeshellcmd($command_string);
    ob_start();
    passthru($command_string);
    $output = ob_get_clean();
    $response = json_decode($output, true);
    if($response["status"] == 1){
        http_response_code(404);
        // echo $response["message"];
        error_log("Python PDF generator error. Returned message: ".$response["message"]);
        die();
    } else {
        $fileContent = $response["response_body"];
        header('Content-Type: application/pdf');
        echo base64_decode($fileContent);
    }
}

generatePdf("pit-example", array(
    'nip' => '122222',
    'urzad' => rawurlencode('Test case'),
));

exit;
?>