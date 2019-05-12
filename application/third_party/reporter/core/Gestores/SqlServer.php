<?php

/**
 *
 * @author JorgeCopia
 *        
 */
class Sqlserver implements iGestorDB{
    
    /**
     * The data report
     * @var object
     */
    private $report;
    
    public function __construct($report){
        $this->report = $report;
    }
    
    
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
        $syntaxAnalyze = new SyntaxAnalyze($sql);
        $field = trim( isset($this->report->field_for_paginate) ? $this->report->field_for_paginate : null);
        if(empty($field) ||  is_null($field)){
            $limit = isset($this->report->items_per_page) ? $this->report->items_per_page : 10;
            $sql = $syntaxAnalyze->addSql("select", "top $limit");
        }
        else{
            
            if ( isset($request['start']) && $request['length'] != -1 ) {
                $start = $request['start'];
                $length = $request['length'] + $start;
            }else {
                $start = 1;
                $length = 10;
            }
            $positions = $syntaxAnalyze->getPositions();
            $sql = substr($sql, $positions['SELECT'] + 6, strlen($sql));
            $sql = "SELECT  *
                FROM ( SELECT ROW_NUMBER() OVER ( ORDER BY {$field} ) AS RowNum,
                          {$sql} ) AS RowConstrainedResult
                WHERE   RowNum >= {$start} AND RowNum < {$length}
                ORDER BY RowNum";
        }
        return $sql;
    }
}