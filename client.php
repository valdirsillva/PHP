<?php

$location = 'http://localhost/PHP_OO/APRESENTACAO_CONTROLE/rest.php';
$parameters = [];
$parameters['class'] = 'PessoaServices';
$parameters['method'] = 'getData';
$parameters['id']    = '1';

// Transformar seu array em um formato de querystring, pronta para ser passada para uma URL
$url = $location .'?'. http_build_query($parameters);
echo "<pre>";
var_dump( json_decode( file_get_contents($url)));
echo "</pre>";
?>