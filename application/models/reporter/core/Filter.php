<?php

/**
 * Class Filter
 *
 * @package \\${NAMESPACE}
 */
class Filter
{
    private $functionTypes = array(
        'date' => "makeDateValue",
        'datetime' => "makeDateTimeValue",
        'int' => "makeNumberValue",
        'string' => "makeValue",
        'select' => "makeSelectValue",
        'multiple' => "makeSelectValue"
    );

    /**
     * The var for the report
     * @var array
     */
    protected $filters = array();


    public function init($varTypes, $filters){
        $this->setTypes($varTypes);
        $this->setVar($filters);
    }

    public function getFilters(){
        return $this->filters;
    }

    public function applyFilterOnSql($sql){
        if(count($this->filters) > 0 && isset($_REQUEST['vars'])) {
            foreach ($_REQUEST['vars'] as $var) {
                $this->setValueOnFilters($var['name'], $var['value']);
            }
            $sql = $this->setFiltersOnSql($sql);
        }else if(is_array($this->filters )){
            $sql = $this->setFiltersOnSql($sql);
        }
        return $sql;
    }

    /**
     * Set vars for grid
     */
    protected function setVar($filters){
        if(!is_array($filters)){ return;}
        foreach( $filters as $var){
            $type = strtolower($var['type']);
            array_push($this->filters, array('name' => $var['name'],
                'value' => $this->getDefaultValue($type, $var['default']),
                'label' => ucwords(str_replace(array('-','_'), ' ', $var['name'])),
                'class' => $this->varTypes[$type]->frontendClass,
                'type'=> $type));
        }
    }

    private function setValueOnFilters($name, $value){
        $value = trim($value);
        for($i=0; $i < count($this->filters); $i++){
            if($this->filters[$i]['name'] == $name && !empty($value)){
                if(!isset($this->filters[$i]['veces'])){
                    $this->filters[$i]['veces'] = 0;
                }
                if($this->filters[$i]['veces'] > 0 and $this->filters[$i]['type']=='multiple'){
                    $this->filters[$i]['value'] .= ",". $value;
                }else{
                    $this->filters[$i]['value'] = $value;
                }
                $this->filters[$i]['veces']++;
                break;
            }
        }
    }

    private function setFiltersOnSql($sql){
        foreach ($this->filters as $filter) {
            $sql = str_replace('{' . $filter['name']. '}',
                $this->formatVar( $filter['type'], $filter['value']),
                $sql);
        }
        return $sql;
    }

    /**
     * Dependiendo de $type se selecciona el método que debe ejecutarse.
     *
     * @param string $type
     * @param string $defaultValue
     *
     * @return mixed
     * @throws \Exception Type not supported
     */
    public function getDefaultValue($type, $defaultValue){
        if(isset($this->functionTypes[$type])){
            $method = $this->functionTypes[$type];
            $defaultValue = trim($defaultValue);
            try{
               $rs = call_user_func(array($this, $method), $defaultValue);
            }catch(Exception $e){
                $rs = $e->getMessage();
            }
            return $rs;
        }else{
            throw new Exception("Type $type not supported" );
        }
    }


    private function makeDateTimeValue($input){
        return $this->dateOperation('Y-m-d H:i:s', $input);
    }

    private function makeDateValue($input){
        return $this->dateOperation('Y-m-d', $input);
    }

    private function dateOperation($format, $input){
        $values = explode(';', $input);
        $datetime = null;
        foreach($values as $value){
            if(!is_null($datetime)){
                $value = $datetime ." " . $value;
            }
            $datetime = date($format, strtotime($value));
        }
        return $datetime;
    }

    private function makeNumberValue($input){
        if(!is_numeric($input)){
            throw new Exception('The input value is not a number');
        }
        return $input;
    }

    private function makeSelectValue($input){
        $data = explode(',', $input);
        $rs = array();
        foreach ($data as $item) {
            $item = trim($item);
            array_push($rs, "{$item}");
        }
        return join(',', $rs);
    }

    private function makeValue($input){
        return $input;
    }

    /**
     * Format the var types
     *
     * @param $types array
     */
    private function setTypes($types){
        foreach( $types as $type){
            $this->varTypes[strtolower($type->name)] = $type;
        }
    }

    /**
     * @param $type
     * @param $value
     *
     * @return string
     */
    private function formatVar($type, $value){
        $value = trim(str_replace(array("\"", "'"), '',$value));
        switch($type){
            case 'multiple':
            case 'select':
                $value = explode(',', $value);
                $rs = "'" . join("','", $value) . "'";
                break;
            case 'string':
                $rs = "'" . $value . "'";
                break;
            default:
                $rs = $value;
                break;
        }
        return $rs;
    }
}
