<?php

class SyntaxAnalyze {

    /**
     * Orden de las sentencias sql
     * @var array
     */
    private $reservedWords = array ( "SELECT", "FROM",
        "WHERE", "GROUP BY", "HAVING", "ORDER BY" );

    private $positions = array();


    /**
     * @var SQL en proceso
     */
    public $sql = '';

    public function __construct($sql)
    {
        $this->setSQl($sql);
    }

    /**
     * Put a SQL and analyze the positions of sql keywords
     * @param string $sql if you set new sql
     * @throws Exception For SQL Sintax
     * @return void
     */
    public function setSQL($sql){
        $this->sql = $sql;
        $keywords = join('|', $this->reservedWords);
        $regex = "/(\(([^()]+|(?1))*\))(*SKIP)(*F)|\b(?:{$keywords})/im";
        preg_match_all($regex, $this->sql, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] as $match) {
            $this->positions[strtoupper($match[0])] = $match[1];
        }
    }

    /**
     * Return the positions for each sql keywords
     * @return array
     */
    public function getPositions(){
        return $this->positions;
    }


    /**
     * Reemplaza un bloque del sql, por ejemplo toda la sentencia order by
     * @param  string $where lugar a reemplazar
     * @param  string $new nuevo texto a incluir
     * @return string
     */
	public function replace($where, $new){
        $where = strtoupper($where);
        $new = trim($new);
        $rigth = '';
        if( isset($this->positions[$where])){
            $left = trim(substr($this->sql, 0, $this->positions[$where]));
            $nextKeyword = $this->nextKeyword($where);
            if(strlen($nextKeyword)>0){
                $nexPosition = trim($this->positions[$nextKeyword]);
                $rigth =trim(substr($this->sql, $nexPosition, strlen($this->sql)));
            }
        }else{
            $position = $this->searchLocation($where);
            $left = trim(substr($this->sql, 0, $position));
        }
        return trim("$left $where $new $rigth");
	}

    public function nextKeyword($keyword){
        $keyword = strtoupper($keyword);
        $keywordPosition = array_search($keyword, $this->reservedWords);
        if(isset($this->reservedWords[$keywordPosition  + 1])){
           $rs = $this->reservedWords[$keywordPosition  + 1];
        }else{
            $rs = '';
        }
        return $rs;
    }


    /**
     * Include $addText inner $where place
     * @param string $where the place where is include $addText
     * @param  string $addText   texto a incluir
     * @return string the new SQL
     */
    public function addSql($where, $addText){
        $where = strtoupper($where);
        if( isset($this->positions[$where])){
            $left = trim(substr($this->sql, 0, $this->positions[$where]));
            if($where == 'WHERE'){
                $addText .= ' AND';
            }
            $position = $this->positions[$where] + strlen($where);
        }else{
            $position = $this->searchLocation($where);
            $left = trim(substr($this->sql, 0, $position));
        }
        $rigth = trim(substr($this->sql, $position, strlen($this->sql)));
        return trim("$left $where $addText $rigth");
    }

    /**
     * Busca el keyword anterior al que se busca
     * @param $where
     * @return int|null
     */
    private function searchLocation($where){
        $posWhere = array_search($where, $this->reservedWords);
        $lastPosition = null;
        foreach(array_reverse($this->positions) as $key => $position){
            $positionKey = array_search($key, $this->reservedWords);
            if($positionKey < $posWhere){
                $position = is_null($lastPosition) ? strlen($this->sql) : $lastPosition;
                break;
            }else{
                $lastPosition = $this->positions[$key];
            }
        }
        return $position;
    }
}
