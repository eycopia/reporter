<?php
/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @package Reporter\Core
 * @author Jorge Copia Silva <eycopia@gmail.com>
 * @license https://github.com/eycopia/reporter/blob/master/LICENSE
 */

class DatatablesSSP {

	/**
	 * Create the data output array for the DataTables rows
	 *
	 *  @param  array $columns Column information array
	 *  @param  array $data    Data from the SQL get
	 *  @return array          Formatted data in a row based format
	 */
	static function data_output ( $columns, $data )
	{
		$out = array();

		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();
			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				// Is there a formatter?
				if ( isset( $column['formatter'] ) ) {
					$row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
				}
				else {
					$row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
				}
			}
			$out[] = $row;
		}
		return $out;
	}

	public static function getQuery($request, $sql, $columns){
		$bindings = array();
		$syntaxAnalyze = new SyntaxAnalyze($sql);
		$where  = DatatablesSSP::filter($request,  $columns, $bindings);
//		$having  = DatatablesSSP::having($request,  $columns);

        if(!empty($where)){
            $sql = $syntaxAnalyze->addSql('where', $where);
        }

        if(!empty($having)){
			$syntaxAnalyze->setSQL($sql);
            $sql = $syntaxAnalyze->addSql('having', $having);
        }
        return $sql;
	}

	public static function getSqlOrder($request, $sql, $columns){
		$order  = DatatablesSSP::order($request,  $columns, $sql);
		if(!empty($order)){
            $syntaxAnalyze = new SyntaxAnalyze($sql);
			$sql = $syntaxAnalyze->replace('order by', $order);
		}
		return $sql;

	}

    /***
     * Custom limit for MYSQL DATABASES
     * @param $request array
     * @param $columns array
     * @param $sql string
     * @return string
     */
    static function limit_mysql($request, $columns, $sql){
        $limit = '';

        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }

        if(empty($limit)){
            $limit = " LIMIT 1, 10";
        }

        return $sql ." " .$limit;
    }

    /**
     * Custom limit for Oracle Database
     * @param $request the user request
     * @param $columns columns for sql
     * @return string
     */
    static function limit_oracle($request, $columns, $sql){
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

	/**
	 * Paging
	 *
	 * Construct the LIMIT clause for server-side processing SQL query
	 *
     *  @param object $driver connection for database
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @param string $sql the query
	 *  @return string SQL limit clause
	 */
	static function limit ( $driver, $request, $columns, $sql )
	{
		return call_user_func("self::limit_".$driver, $request, $columns, $sql);
	}


	/**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL order by clause
	 */
	static function order ( $request, $columns )
	{
		$order = '';
		if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];
				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';
                    $column = $columns[$columnIdx];
                    $field = (isset($column['table']) && strlen($column['table']) > 0) ? $column['table'] . "." . $column['db'] : $column['db'];
					$orderBy[] = ''.$field.' '.$dir;
				}
			}
			$order = implode(', ', $orderBy);
		}
		return $order;
	}


	/**
	 * Searching / Filtering
	 *
	 * Construct the WHERE clause for server-side processing SQL query.
	 *
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here performance on large
	 * databases would be very poor
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL where clause
	 */
	static function filter ( $request, $columns )
	{
		$globalSearch =  array();
		$columnSearch = array();
		$dtColumns = self::pluck( $columns, 'db' );
		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = trim($request['search']['value']);
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$column = $dtColumns[$i];
				if(isset($columns[$i]['table']) && !empty($columns[$i]['table'])){
					if ( $requestColumn['searchable'] == 'true' ) {
						$globalSearch[] = $columns[$i]['table'].".".$column." LIKE '%".$str."%'";
					}
				}
			}
		}

		// Individual column filtering
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				if(isset($columns[$i]['table']) && !empty($columns[$i]['table'])){
					$requestColumn = $request['columns'][$i];
					$column = $dtColumns[$i];
					$str = $requestColumn['search']['value'];
					if ( $requestColumn['searchable'] == 'true' &&
					 $str != '' ) {
						$columnSearch[] = $columns[$i]['table'] .".".$column." LIKE '%".$str."%'";
					}
				}
			}
		}

		// Combine the filters into a single string
		$where = '';

		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}


		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}
		return  $where;
	}

	static function having($request, $columns){
		$columnSearch = array();
		$dtColumns = array();

		foreach ($columns as $c) {
			if($c['type'] == 'no_column'){
				array_push($dtColumns, $c['db']);
			}
		}
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				if(!isset($dtColumns[$i])) { continue; }
				$column = $dtColumns[$columnIdx];
				$str = $requestColumn['search']['value'];
				if ( $requestColumn['searchable'] == 'true' &&
				 $str != '' && !isset($columns[$i]['table'])) {
				 	$columnSearch[] = "`".$column."` LIKE '%".$str."%'";
				}
			}
		}
		$having = '';
		if ( count( $columnSearch ) ) {
			$having = $having === '' ?
				implode(' AND ', $columnSearch) :
				$having .' AND '. implode(' AND ', $columnSearch);
		}
		return $having;
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Internal methods
	 */

	/**
	 * Throw a fatal error.
	 *
	 * This writes out an error message in a JSON string which DataTables will
	 * see and show to the user in the browser.
	 *
	 * @param  string $msg Message to send to the client
	 */
	static function fatal ( $msg )
	{
		echo json_encode( array(
			"error" => $msg
		) );

		exit(0);
	}

	/**
	 * Pull a particular property from each assoc. array in a numeric array,
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck ( $a, $prop )
	{
		$out = array();
		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			if(isset($a[$i]['table'])){
				$out[] = $a[$i][$prop];
			}else{
				$out[] = "";
			}
		}
		return $out;
	}


	/**
	 * Return a string from an array or a string
	 *
	 * @param  array|string $a Array to join
	 * @param  string $join Glue for the concatenation
	 * @return string Joined string
	 */
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
}

