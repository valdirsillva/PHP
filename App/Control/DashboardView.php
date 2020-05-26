<?php

use Livro\Control\Page;
use Livro\Widgets\Container\HBox;
// 12/05/2020
class DashboardView extends Page 
{
    public function __construct() 
    {
    	parent::__construct();

    	$hbox = new HBox;
    	$hbox->add( new VendasMesChart )->style.='width:48px;';
    	$hbox->add( new VendasTipoChart )->style.='width:48px;';
    	parent::add($hbox);
    }
}