<?php
require_once APPPATH."models".DIRECTORY_SEPARATOR."Datatables".DIRECTORY_SEPARATOR."Filter.php";
/**
 * Class Filter_test
 *
 * @package Reporter
 */
class Filter_test extends PHPUnit_Framework_TestCase{

    private $filter;

    public function setUp(){
//        echo "Creare la instancia\n";
        $this->filter = new Filter();
    }

    public function testDatatimeValue(){
        $varValue = $this->filter->getDefaultValue('datetime', '-7 day');
        $expected = date('Y-m-d H:i:s', strtotime('-7 day'));
        $this->assertEquals(strlen($expected), strlen($varValue), 'Datetime, formato correcto');
        $this->assertEquals(substr($expected,0,16), substr($varValue, 0, 16), "Fechas coinciden");
    }

    public function testDatetimeTwoOperations(){
        $varValue = $this->filter->getDefaultValue('datetime', '-7 day ; -1 day');
        $firstDate = date('Y-m-d H:i:s', strtotime('-7 day'));
        $expected = date('Y-m-d H:i:s', strtotime( $firstDate . ' -1 day'));
        $this->assertEquals(strlen($expected), strlen($varValue), 'Datetime, formato correcto');
        $this->assertEquals(substr($expected, 0,16), substr($varValue, 0, 16), "Fechas coinciden");
    }

    public function testDateValue(){
        $varValue = $this->filter->getDefaultValue('date', '-7 day');
        $expected = date('Y-m-d', strtotime('-7 day'));
        $this->assertEquals($expected, $varValue, "Fechas coinciden");
    }

    public function testDateTwoOperations(){
        $varValue = $this->filter->getDefaultValue('date', '-7 day; -1 day');
        $firstDate = date('Y-m-d', strtotime('-7 day'));
        $expected = date('Y-m-d', strtotime( $firstDate . ' -1 day'));
        $this->assertEquals($expected, $varValue, "Fechas coinciden");
    }

    public function testNumberValue()
    {
        $varValue = $this->filter->getDefaultValue('int', '55');
        $expected= '55';
        $this->assertEquals($expected, $varValue);
    }


    public function testStringConditionValue(){
        $varValue = $this->filter->getDefaultValue('string', 'juan');
        $expected= "juan";
        $this->assertEquals($expected, $varValue);
    }

    public function testSelectValue(){
        $varValue = $this->filter->getDefaultValue('select', 'juan, manuel');
        $expected= "juan,manuel";
        $this->assertEquals($expected, $varValue);
    }

}
