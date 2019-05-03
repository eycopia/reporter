<?php
$sep = DIRECTORY_SEPARATOR;
require_once APPPATH."third_party{$sep}reporter{$sep}core{$sep}SyntaxAnalyze.php";

class SyntaxAnalyze_test extends PHPUnit_Framework_TestCase
{    
    public function test_SqlSinWhere()
    {
        $sql = "select * from un_from";
        $syntax = new SyntaxAnalyze($sql);
        $matches = $syntax->getPositions();
        $this->assertEquals(0, $matches ['SELECT']);
        $this->assertEquals(9, $matches ['FROM']);
    }

    public function test_SqlConWhereInterno(){
        $sql = "select * from un_from where y = (select y from x where y = 1 limit 1)";
        $syntax = new SyntaxAnalyze($sql);
        $par = $syntax->getPositions();
        $this->assertEquals(0, $par['SELECT']);
        $this->assertEquals(22, $par['WHERE']);
    }

    public function test_AgregandoWhere(){
        $sql = "Select * FROM un_from";
        $incluir = 'p = 1 and j = 4';
        $syntax = new SyntaxAnalyze($sql);
        $newSql = $syntax->addSql('where', $incluir);
        $resultSql = "Select * FROM un_from WHERE p = 1 and j = 4";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_AgregandoWhere2(){
        $sql = "Select * FROM un_from ORDER BY a desc";
        $incluir = 'p = 1 and j = 4';
        $syntax = new SyntaxAnalyze($sql);
        $newSql = $syntax->addSql('where', $incluir);
        $resultSql = "Select * FROM un_from WHERE p = 1 and j = 4 ORDER BY a desc";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_AgregandoWhereSimple(){
        $sql = "select p.* from interna_2.regiones as r join interna_2.personal as p  on r.id_area = p.id_area group by p.id_personal";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = 'p = 1 and j = 4';
        $newSql = $syntax->addSql('where', $incluir);
        $resultSql = "select p.* from interna_2.regiones as r join interna_2.personal as p  on r.id_area = p.id_area WHERE p = 1 and j = 4 group by p.id_personal";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_AgregandoWhereSqlComplejo(){
        $sql = "select x.id_personal, p.nombre_remedy, x.id_area, r.grupo_soporte, r.organizacion_soporte, sum(if(x.incidencia_remedy is null, 0, 1)) as 'incidencias', sum(if(x.incidencia_remedy is not null and x.fecha_correccion is not null, 1, 0)) as 'incidencia cerradas', sum(if(x.estado = 'cancelado', 1, 0)) as 'incidencia canceladas', sum(if(x.fecha_respuesta_sms is null and x.auto_response = 0, 0, 1)) as sms_contestados, sum(notificaciones) as 'total notificaciones', sum(n_incidencias) as 'notificaciones sin incidencia', sum(n_alarma) as 'notificaciones por incidencia' from (select count(*) as notificaciones, sum(if(a.incidencia_remedy is null, 1, 0)) as 'n_incidencias', sum(if(a.incidencia_remedy is not null, 1, 0)) as 'n_alarma', a.estado, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area from alarma as a join notificacion as n on n.idAlarma = a.id where a.fecha between '2016-01-01' and '2016-01-06' group by n.idAlarma, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area ) x join interna.regiones as r on r.id_area = x.id_area join interna.personal as p on p.id_personal = x.id_personal group by x.id_area, x.id_personal";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = 'p = 1 and j = 4';
        $newSql = $syntax->addSql('where', $incluir);
        $resultSql = "select x.id_personal, p.nombre_remedy, x.id_area, r.grupo_soporte, r.organizacion_soporte, sum(if(x.incidencia_remedy is null, 0, 1)) as 'incidencias', sum(if(x.incidencia_remedy is not null and x.fecha_correccion is not null, 1, 0)) as 'incidencia cerradas', sum(if(x.estado = 'cancelado', 1, 0)) as 'incidencia canceladas', sum(if(x.fecha_respuesta_sms is null and x.auto_response = 0, 0, 1)) as sms_contestados, sum(notificaciones) as 'total notificaciones', sum(n_incidencias) as 'notificaciones sin incidencia', sum(n_alarma) as 'notificaciones por incidencia' from (select count(*) as notificaciones, sum(if(a.incidencia_remedy is null, 1, 0)) as 'n_incidencias', sum(if(a.incidencia_remedy is not null, 1, 0)) as 'n_alarma', a.estado, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area from alarma as a join notificacion as n on n.idAlarma = a.id where a.fecha between '2016-01-01' and '2016-01-06' group by n.idAlarma, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area ) x join interna.regiones as r on r.id_area = x.id_area join interna.personal as p on p.id_personal = x.id_personal WHERE p = 1 and j = 4 group by x.id_area, x.id_personal";
        $this->assertEquals($newSql, $resultSql);
    }

    /**
     * Agrega condiciones al where
     * Las condiciones a agregar se colocal al principio del where y se une con un AND
     */
    public function test_AgregandoCondicionesAlWhere(){
        $sql = "Select * FROM un_from where juan x = 3";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = 'p = 1 and j = 4';
        $newSql = $syntax->addSql('where', $incluir);
        $resultSql = "Select * FROM un_from WHERE p = 1 and j = 4 AND juan x = 3";
        $this->assertEquals($newSql, $resultSql);
    }

    /**
     * Agrega condiciones al order by
     * Las condiciones a agregar se colocal al principio del where y se une con un AND
     */
    public function test_AgregandoCondicionesAlOrder(){
        $sql = "Select * FROM un_from where juan x = 3 order by x";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = 'y';
        $newSql = $syntax->replace('order by', $incluir);
        $resultSql = "Select * FROM un_from where juan x = 3 ORDER BY y";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_AgregaOrderComplejoSQL(){
        $sql = "select x.id_personal, p.nombre_remedy, x.id_area, r.grupo_soporte, r.organizacion_soporte, sum(if(x.incidencia_remedy is null, 0, 1)) as 'incidencias', sum(if(x.incidencia_remedy is not null and x.fecha_correccion is not null, 1, 0)) as 'incidencia cerradas', sum(if(x.estado = 'cancelado', 1, 0)) as 'incidencia canceladas', sum(if(x.fecha_respuesta_sms is null and x.auto_response = 0, 0, 1)) as sms_contestados, sum(notificaciones) as 'total notificaciones', sum(n_incidencias) as 'notificaciones sin incidencia', sum(n_alarma) as 'notificaciones por incidencia' from (select count(*) as notificaciones, sum(if(a.incidencia_remedy is null, 1, 0)) as 'n_incidencias', sum(if(a.incidencia_remedy is not null, 1, 0)) as 'n_alarma', a.estado, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area from alarma as a join notificacion as n on n.idAlarma = a.id where a.fecha between '2015-11-01' and '2016-01-06' group by n.idAlarma, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area ) x join interna.regiones as r on r.id_area = x.id_area join interna.personal as p on p.id_personal = x.id_personal where (`x`.`id_personal` LIKE '%hua%' OR `p`.`nombre_remedy` LIKE '%hua%' OR `x`.`id_area` LIKE '%hua%' OR `r`.`grupo_soporte` LIKE '%hua%' OR `r`.`organizacion_soporte` LIKE '%hua%') group by x.id_area, x.id_personal";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = "a.id desc, b.l asc";
        $newSql = $syntax->replace('order by', $incluir);
        $resultSql = "select x.id_personal, p.nombre_remedy, x.id_area, r.grupo_soporte, r.organizacion_soporte, sum(if(x.incidencia_remedy is null, 0, 1)) as 'incidencias', sum(if(x.incidencia_remedy is not null and x.fecha_correccion is not null, 1, 0)) as 'incidencia cerradas', sum(if(x.estado = 'cancelado', 1, 0)) as 'incidencia canceladas', sum(if(x.fecha_respuesta_sms is null and x.auto_response = 0, 0, 1)) as sms_contestados, sum(notificaciones) as 'total notificaciones', sum(n_incidencias) as 'notificaciones sin incidencia', sum(n_alarma) as 'notificaciones por incidencia' from (select count(*) as notificaciones, sum(if(a.incidencia_remedy is null, 1, 0)) as 'n_incidencias', sum(if(a.incidencia_remedy is not null, 1, 0)) as 'n_alarma', a.estado, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area from alarma as a join notificacion as n on n.idAlarma = a.id where a.fecha between '2015-11-01' and '2016-01-06' group by n.idAlarma, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area ) x join interna.regiones as r on r.id_area = x.id_area join interna.personal as p on p.id_personal = x.id_personal where (`x`.`id_personal` LIKE '%hua%' OR `p`.`nombre_remedy` LIKE '%hua%' OR `x`.`id_area` LIKE '%hua%' OR `r`.`grupo_soporte` LIKE '%hua%' OR `r`.`organizacion_soporte` LIKE '%hua%') group by x.id_area, x.id_personal ORDER BY a.id desc, b.l asc";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_AgregaOrderComplejoSQL2(){
        $sql = "select x.id_personal, p.nombre_remedy, x.id_area, r.grupo_soporte, r.organizacion_soporte, sum(if(x.incidencia_remedy is null, 0, 1)) as 'incidencias', sum(if(x.incidencia_remedy is not null and x.fecha_correccion is not null, 1, 0)) as 'incidencia cerradas', sum(if(x.estado = 'cancelado', 1, 0)) as 'incidencia canceladas', sum(if(x.fecha_respuesta_sms is null and x.auto_response = 0, 0, 1)) as sms_contestados, sum(notificaciones) as 'total notificaciones', sum(n_incidencias) as 'notificaciones sin incidencia', sum(n_alarma) as 'notificaciones por incidencia' from (select count(*) as notificaciones, sum(if(a.incidencia_remedy is null, 1, 0)) as 'n_incidencias', sum(if(a.incidencia_remedy is not null, 1, 0)) as 'n_alarma', a.estado, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area from alarma as a join notificacion as n on n.idAlarma = a.id where a.fecha between '2015-11-01' and '2016-01-06' group by n.idAlarma, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area ) x join interna.regiones as r on r.id_area = x.id_area join interna.personal as p on p.id_personal = x.id_personal where (`x`.`id_personal` LIKE '%hua%' OR `p`.`nombre_remedy` LIKE '%hua%' OR `x`.`id_area` LIKE '%hua%' OR `r`.`grupo_soporte` LIKE '%hua%' OR `r`.`organizacion_soporte` LIKE '%hua%') group by x.id_area, x.id_personal";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = "incidencias asc";
        $newSql = $syntax->replace('order by', $incluir);
        $resultSql = "select x.id_personal, p.nombre_remedy, x.id_area, r.grupo_soporte, r.organizacion_soporte, sum(if(x.incidencia_remedy is null, 0, 1)) as 'incidencias', sum(if(x.incidencia_remedy is not null and x.fecha_correccion is not null, 1, 0)) as 'incidencia cerradas', sum(if(x.estado = 'cancelado', 1, 0)) as 'incidencia canceladas', sum(if(x.fecha_respuesta_sms is null and x.auto_response = 0, 0, 1)) as sms_contestados, sum(notificaciones) as 'total notificaciones', sum(n_incidencias) as 'notificaciones sin incidencia', sum(n_alarma) as 'notificaciones por incidencia' from (select count(*) as notificaciones, sum(if(a.incidencia_remedy is null, 1, 0)) as 'n_incidencias', sum(if(a.incidencia_remedy is not null, 1, 0)) as 'n_alarma', a.estado, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area from alarma as a join notificacion as n on n.idAlarma = a.id where a.fecha between '2015-11-01' and '2016-01-06' group by n.idAlarma, n.idAlarma, a.incidencia_remedy, a.inc_repetida, a.fecha_correccion, n.fecha_respuesta_sms, n.auto_response, n.id_personal, a.id_area ) x join interna.regiones as r on r.id_area = x.id_area join interna.personal as p on p.id_personal = x.id_personal where (`x`.`id_personal` LIKE '%hua%' OR `p`.`nombre_remedy` LIKE '%hua%' OR `x`.`id_area` LIKE '%hua%' OR `r`.`grupo_soporte` LIKE '%hua%' OR `r`.`organizacion_soporte` LIKE '%hua%') group by x.id_area, x.id_personal ORDER BY incidencias asc";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_replaceSimple(){
        $sql = "select juna, manuel, menaa from mena where a = 1";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = "*";
        $newSql = $syntax->replace('from', $incluir);
        $resultSql = "select juna, manuel, menaa FROM * where a = 1";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_replaceSimple2(){
        $sql = "select juna, manuel, menaa from mena where a = 1";
        $syntax = new SyntaxAnalyze($sql);
        $incluir = "*";
        $newSql = $syntax->replace('select', $incluir);
        $resultSql = "SELECT * from mena where a = 1";
        $this->assertEquals($newSql, $resultSql);
    }

    public function test_queriagrupaciones(){
        $sql = "select s.STARTTIME, s.CI, s.LAC,
                (sum(s.ALERTINGCOUNT)/sum(s.ATTEMPTCOUNT))*100 as Call_Success_Rate,
                (sum(s.ASSNSUCCCOUNT)/sum(s.ASSNCOUNT))*100 as TCH_Assignment_Success_Rate,
                (sum(s.ANSWERCOUNT)/sum(s.ATTEMPTCOUNT))*100 as Call_Answer_Rate ,
                (sum(s.DROPCOUNT)/sum(s.ANSWERCOUNT))*100 as  Call_Drop_Rate_After_Answer,
                (sum(s.TDROPCOUNT)/sum(s.ANSWERCOUNT))*100 as Abnormal_Call_Release_Rate_After_Answer,
                sum(s.ANSWERDURATION)/1000 as Answered_Call_Traffic_Volume,
                sum(s.ALERTINGCOUNT) ALERTINGCOUNT,
                sum(s.ATTEMPTCOUNT) ATTEMPTCOUNT,
                sum(s.ASSNSUCCCOUNT) ASSNSUCCCOUNT,
                sum(s.ASSNCOUNT) ASSNCOUNT,
                sum(s.DROPCOUNT) DROPCOUNT,
                sum(s.ANSWERCOUNT) ANSWERCOUNT,
                sum(s.TDROPCOUNT) TDROPCOUNT,
                sum(s.ANSWERDURATION) ANSWERDURATION
                from sdr2016091518 as s
                where s.CALLTYPE=0 and s.PROTOCOL=0 and s.SRVTYPE=0
                AND s.CI != 0
                GROUP BY s.STARTTIME, s.CI, s.LAC
                ORDER BY s.CI DESC
                LIMIT 30";
    }

}
