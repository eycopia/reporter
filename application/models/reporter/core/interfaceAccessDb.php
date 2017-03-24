<?php

/**
 * Esta interfaz define los metodos que utiliza
 * la clase Grid para poder extraer los datos
 */

interface interfaceAccessDb{

    function setDbConnection();

    /*
     * Devuelve un objeto con un solo resultado
     * @param string $sql the sql sentences
     * @return object
     */
    function row($sql);

    /**
     * Retorna los datos en arrays assiativos
     * @param string $sql the sql sentences
     * @return array
     */
    function result_array($sql);

    /**
     * Retorna los datos en arrays objetos
     *
     * @param string $sql
     *
     * @return array
     */
    function result($sql);

}
