<?php

interface iGestorDB{
    /**
     * Get Limit Default
     * @param string $sql
     * @return string
     */
    public function getLimitForColumns($sql);
    
    /***
     * Get SQL Pagination on Gestor
     * @param $request array
     * @param $columns array
     * @param $sql string
     * @return string
     */
    public function getSqlPaginate($request, $columns, $sql);
}