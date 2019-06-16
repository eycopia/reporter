<?php
/**
 * Name: Customer_m.php
 *
 * Author: Jorge Copia <eycopia@gmail.com>
 *
 * Description:
 */

class Sakila_customer_m extends Grid implements interfaceGrid
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
        $sql = "select c.customer_id, concat(first_name, ' ', last_name) as nombre, 
          email, s.address_id as store_id_direction
        from sakila.customer as c
        left join sakila.store as s on s.store_id = c.store_id";

        return array(
            'columns' => $this->getColumns(),
            'filters' => 'basic',
            'sql' => $sql,
        );
    }

    private function getColumns(){
        return array(
            array('dt' => 'Item', 'db' => 'customer_id', 'table' => 'c'),
            array('dt' => 'Names', 'db' => 'nombre'),
            array('dt' => 'store_id_direction', 'db' => 'store_id_direction')
        );
    }
}