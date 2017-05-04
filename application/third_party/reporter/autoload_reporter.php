<?php
/**
 * Name: autoload_reporter.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description: Carga las clases necearias para crear una grilla
 */
$path =  APPPATH . "third_party/reporter/";
require_once "{$path}models/core/interfaceGrid.php";
require_once "{$path}models/core/Grid.php";
require_once "{$path}models/core/ModelReporter.php";
require_once "{$path}models/core/SyntaxAnalyze.php";
