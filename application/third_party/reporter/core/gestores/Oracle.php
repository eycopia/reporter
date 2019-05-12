<?php

/**
 *
 * @author JorgeCopia
 *        
 */
class Oracle implements iGestorDB {
    
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
        return $syntaxAnalyze->addSql('where', " rownum <= 1 ");
    }
    
    /***
     * Get SQL Pagination on Gestor
     * @param $request array
     * @param $columns array
     * @param $sql string
     * @return string
     */
    public function getSqlPaginate($request, $columns, $sql){
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $start = $request['start'];
            $length = $request['length'] + $start;
        }else {
            $start = 1;
            $length = 10;
        }
        $sql =  "SELECT i.*
              FROM (SELECT i.*
                      FROM (SELECT i.*, ROWNUM AS rn
                              FROM ( {$sql} ) i
                             WHERE ROWNUM <= {$length}
                           ) i
                     WHERE rn > {$start}
                   ) i
             ORDER BY rn";
        return $sql;
    }
}