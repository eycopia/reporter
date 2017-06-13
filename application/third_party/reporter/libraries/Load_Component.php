<?php
/**
 * Clase para cargar componentes definidos por el usuario
 * @package Reporter\Libraries
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class Load_Component {

    /**
     * Retorna la instancia al objeto definido por el usuario
     * @param $components
     * @return mixed
     */
    public function getInstance($components){
        require_once APPPATH
            .DIRECTORY_SEPARATOR."controllers"
            .DIRECTORY_SEPARATOR."custom"
            .DIRECTORY_SEPARATOR.$components->nameClass.".php";
        $class= $components->nameClass;
        return new $class();
    }
}
