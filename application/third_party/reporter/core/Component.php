<?php
/**
 * Permite cargar componentes
 * @package Reporter\Core
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class Component extends  CI_Controller{
    public function download($idComponent){
        $this->load->model('component_m');
        $this->load->library('Load_Component');
        $components = $this->component_m->findComponentDownload($idComponent);
        if(count($components) > 0 ) {
            $obj = $this->load_component->getInstance($components);
            $filename = $components->fileName . date('Ymd_His').'.'.$components->fileExtension;
            $obj->download($components->idReport, $filename);
        }else{
            $message = "No se encontro ningún componente, por favor notificar al desarrollador.";
            show_error($message, 1, $heading = 'Imposible cargar componente');
        }
    }
}
