<?php
date_default_timezone_set('America/Sao_Paulo');

/* Library loader */
require_once 'Lib/Livro/Core/ClassLoader.php';
$al = new Livro\Core\ClassLoader;
$al->addNamespace('Livro','Lib/Livro');     /* operation add namespace load */
$al->register();

/* App loader */
require_once 'Lib/Livro/Core/AppLoader.php';
$al = new Livro\Core\AppLoader;
$al->addDirectory('App/Control');          /* Load file Control in directory */
$al->addDirectory('App/Model');            /* Load file Model in directory */
$al->register();

// load path vendor


// Vendor
$loader  = require 'vendor/autoload.php';
$loader->register();


use Livro\Session\Session;

$content = '';

new Session;
if (Session::getValue('logged')) {
    $template = file_get_contents('App/Templates/template.html');
    $class = '';
}
else {
    $template = file_get_contents('App/Templates/login.html');
    $class = 'LoginForm';
}

if (isset($_GET['class']) AND Session::getValue('logged'))
{
    $class = $_GET['class'];
}

if (class_exists($class))
{
    try
    {
        $pagina = new $class;
        ob_start();
        $pagina->show();
        $content = ob_get_contents();
        ob_end_clean();
    }
    catch (Exception $e)
    {
        $content = $e->getMessage() . '<br>' .$e->getTraceAsString();
    }
}
$output = str_replace('{content}', $content, $template);
$output = str_replace('{class}',   $class, $output);
echo $output;






// // Lê o conteúdo do template
// $template = file_get_contents('App/Templates/template.html');
// $content  = '';
// $class    = 'Home';

// if ($_GET) {
//     $class = $_GET['class'];
//     /* checks if the class exists */
//     if (class_exists($class)) {
//     	try {
//     		// $page = new $class;
//     	   $pagina = new $class; 
//     	   ob_start();     // inicia controle de output
//     	   $pagina->show();  //exibe página
//     	   $content = ob_get_contents(); // lê conteúdo gerado
//     	   ob_end_clean(); // Finaliza controle de output


//     	} catch(Exception $e) {
//           $content = $e->getMessage() . '<br/>' . $e->getTraceAsString();
//     	}

//     } else {
//        $content = "Class <b>{$class}</b> not found";
//     }
// }

// // injeta conteúdo gerado dentro do template

// $output = str_replace('{content}', $content, $template);
// $output = str_replace('{class}',   $class,   $output);
// // exibe saída gerada
// print $output;


