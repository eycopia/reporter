<?php
/**
 * Clase para cargar componentes definidos por el usuario
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