<?php
require_once APPPATH."models".DIRECTORY_SEPARATOR."osiptel".DIRECTORY_SEPARATOR."Alarma7767_m.php";

/**
 * Class Alarma7767_test
 *
 * @package \\${NAMESPACE}
 */
class Alarma7767_test extends PHPUnit_Framework_TestCase
{
    private $alarma = array(
        'fecha_alarma' => '2016-11-29 01:00:00',
        'fecha_cleared' => '2016-11-29 02:00:00',
        'id' => 1,
        'bsc' => 'LIMBSC22',
        'bcf' => 'BCF-524',
        'site_source' => 'LJ27222',
        'nombre_site' => 'CAMPAMENTO TUCTU',
        'alarma' => 7767,
        'site' => 'LJ2722'
    );

    public function test_IntermitenciaSimple()
    {
        $alarma = $this->alarma;
        $alarma2 = $alarma;
        $alarma3 = $alarma;
        $alarma2['id'] = '2';
        $alarma3['id'] = '3';
        $alarma2['fecha_alarma'] = '2016-11-29 2:00:00';
        $alarma2['fecha_cleared'] = '2016-11-29 2:30:00';
        $alarma3['fecha_alarma'] = '2016-11-29 2:35:00';
        $alarma3['fecha_cleared'] = '2016-11-29 3:00:00';

        $data = array( (object) $alarma, (object) $alarma2, (object) $alarma3);

        $a = new Alarma7767_m();
        $a->setLapso(360);
        $rs = $a->agruparAlarmas($data);
        $this->assertEquals($rs['LJ27222']['total']/60, 115);
        $this->assertEquals($rs['LJ27222']['repeticion'], 2);
    }

    public function test_NoIntermitencia()
    {
        $alarma = $this->alarma;
        $alarma2 = $alarma;
        $alarma3 = $alarma;
        $alarma2['id'] = '2';
        $alarma3['id'] = '3';
        $alarma2['fecha_alarma'] = '2016-11-29 05:00:00';
        $alarma2['fecha_cleared'] = '2016-11-29 05:30:00';
        $alarma3['fecha_alarma'] = '2016-11-29 07:30:00';
        $alarma3['fecha_cleared'] = '2016-11-29 08:00:00';
        $data = array( (object) $alarma, (object) $alarma2, (object) $alarma3);
        $a = new Alarma7767_m();
        $a->setLapso(360);
        $rs = $a->agruparAlarmas($data);
        $this->assertEquals($rs['LJ27222']['total']/60, 120, 'lapso');
        $this->assertEquals($rs['LJ27222']['repeticion'], 0,'No intermitente');
        $this->assertEquals($rs['LJ27222']['aparicion'], 3,'Veces de la alarma');
    }

    public function test_IntermitenciaYNoIntermitencia()
    {
        $alarma = $this->alarma;
        $alarma2 = $alarma;
        $alarma3 = $alarma;
        $alarma4 = $alarma;
        $alarma2['id'] = '2';
        $alarma3['id'] = '3';
        $alarma4['id'] = '4';
        $alarma2['fecha_alarma'] = '2016-11-29 03:00:00';
        $alarma2['fecha_cleared'] = '2016-11-29 04:00:00';
        $alarma3['fecha_alarma'] = '2016-11-29 04:02:00';
        $alarma3['fecha_cleared'] = '2016-11-29 04:30:00';
        $alarma4['fecha_alarma'] = '2016-11-29 06:00:00';
        $alarma4['fecha_cleared'] = '2016-11-29 07:00:00';
        $data = array( (object) $alarma, (object) $alarma2, (object) $alarma3, (object) $alarma4);
        $a = new Alarma7767_m();
        $a->setLapso(360);
        $rs = $a->agruparAlarmas($data);
        $this->assertEquals($rs['LJ27222']['total']/60, 208, 'lapso');
        $this->assertEquals($rs['LJ27222']['repeticion'], 1,'No intermitente');
        $this->assertEquals($rs['LJ27222']['aparicion'], 4,'Veces de la alarma');
    }

    public function test_IntermitenciaNoClareada(){
        $alarma = $this->alarma;
        $alarma2 = $alarma;
        $alarma3 = $alarma;
        $alarma2['id'] = '2';
        $alarma3['id'] = '3';
        $alarma2['fecha_alarma'] = '2016-11-29 2:00:00';
        $alarma2['fecha_cleared'] = '2016-11-29 2:30:00';
        $alarma3['fecha_alarma'] = '2016-11-29 2:35:00';
        $alarma3['fecha_cleared'] = '';
        $data = array( (object) $alarma, (object) $alarma2, (object) $alarma3);
        $a = new Alarma7767_m();
        $hora = strtotime('now') - strtotime('2016-11-29 2:35:00');
        $a->setLapso(360);
        $rs = $a->agruparAlarmas($data);
//        $this->assertEquals($rs['LJ27222']['total']/60, (90 + $hora)/60);
        $this->assertEquals($rs['LJ27222']['repeticion'], 2);
    }
}
