<?php

/**
 *
 * @author JorgeCopia
 *        
 */
class Mysql implements iGestorDB
{
    
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
        return "$sql  limit 1";
    }

    /***
     * Get SQL Pagination on Mysql
     * @param $request array
     * @param $columns array
     * @param $sql string
     * @return string
     */
    public function getSqlPaginate($request, $columns, $sql){
        $limit = '';
        
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }
        
        if(empty($limit)){
            $limit = " LIMIT 1, 10";
        }
        
        return $sql ." " .$limit;
    }
}

