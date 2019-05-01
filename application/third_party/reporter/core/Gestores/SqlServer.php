<?php

/**
 *
 * @author JorgeCopia
 *        
 */
class Sqlserver implements iGestorDB{
    /**
     * Get Limit Default
     * @param string $sql
     * @return string
     */
    public function getLimitForColumns($sql){
        $syntaxAnalyze = new SyntaxAnalyze($sql);
        return $syntaxAnalyze->addSql('select', " top 1 ");
    }
    
    /***
     * Get SQL Pagination on Gestor
     * @param $request array
     * @param $columns array
     * @param $sql string
     * @return string
     */
    public function getSqlPaginate($request, $columns, $sql){
        return $sql;
    }
}