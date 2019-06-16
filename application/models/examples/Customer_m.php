<?php
/**
 * Name: Customer_m.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */

class Customer_m extends Grid implements interfaceGrid
{

    public function __construct()
    {
        parent::__construct(new ModelReporter());
    }

    /**
 * Define los componentes a cargar en la grilla
 * @return array
 */
    public function gridDefinition()
    {
        $downloadUrl = site_url("{$this->report->url}/download/{$this->report->idReport}");
        $showUrl = site_url("{$this->report->url}/show/{$this->report->idReport}");
        $sql = "select c.customer_id, concat(first_name, ' ', last_name) as nombre, email, s.address_id as store_id_direction
        from sakila.customer as c
        left join sakila.store as s on s.store_id = c.store_id";

        return array(
            'title' => $this->report->title,
            'description' => $this->report->details,
            'data_url' => $showUrl,
            'filters' => 'basic',
            'columns' => $this->getColumns(),
            'pagination' => $this->report->pagination,
            'sql' => $sql,
            'utilities' => array(
                'auto_reload' => $this->report->auto_reload,
                'items_per_page' => $this->report->items_per_page,
                'download_all' => $downloadUrl,
                'donwload_view' => true,
                'show_columns' => true
            )
        );
    }

    private function getColumns(){
        return array(
            array('dt' => 'id', 'db' => 'customer_id', 'table' => 'c'),
            array('dt' => 'nombre', 'db' => 'nombre'),
            array('dt' => 'store_id_direction', 'db' => 'store_id_direction')
        );
    }
}