<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

class Jqgrid_model extends CI_Model {
	private $CI;
	function __construct() {
		$this->CI =& get_instance();
	}
	
	function get_data_query($SQL,$countField,$whereFirst=TRUE) {
		$limit      = $this->CI->input->get_post('rows');
        $limitstart = $this->CI->input->get_post('limitstart');
        $filter     = $this->CI->input->get_post('searchField');
        $filtertext = $this->CI->input->get_post('searchString');
        $oper       = $this->CI->input->get_post('searchOper');
        $sortby     = $this->CI->input->get_post('sidx');
        $sortdir    = $this->CI->input->get_post('sord');
        $page       = $this->CI->input->get_post('page');
		
		$response 	= array();
		$arr_oper = array(
			'eq'=>" = ",
			'ne'=>" <> ",
			'lt'=>" < ",
			'le'=>" <= ",
			'gt'=>" > ",
			'ge'=>" >= ",
			'bw'=>" LIKE ",
			'bn'=>" NOT LIKE ",
			'in'=>" IN ",
			'ni'=>" NOT IN ",
			'ew'=>" LIKE ",
			'en'=>" NOT LIKE ",
			'cn'=>" LIKE " ,
			'nc'=>" NOT LIKE " 
		);
		
		$a = ' AND ';
		if ($whereFirst)
			$a = ' WHERE ';
			
		if ($filter && $filtertext)
			$SQL .= $a .$filter .$arr_oper[$oper]. $filtertext;
		
		// COUNT RECORD
		$sql_count = str_replace("{COUNT_STR}",",count($countField) as record_count",$SQL);
		$count = $this->db->query($sql_count)->row()->record_count;
		
		if( $count > 0 )
            $total_pages = ceil($count/$limit);
        else
            $total_pages = 0;
		
        if ($page > $total_pages)
            $page=$total_pages;

        $limitstart = $limit*$page - $limit; // do not put $limit*($page - 1)
        $limitstart = ($limitstart < 0)?0:$limitstart;

		// ORDER BY
		if( empty($sortdir) )
			$sortdir = "ASC";
		if( !empty($sortby) ):
			$SQL .= " ORDER BY " . $sortby . ' ' . $sortdir ;
		endif;
	
		// LIMIT
        if( !isset($limitstart) || $limitstart == '' )
			$limitstart = 0;
        if( isset($limitstart) && !empty($limit) )
			$SQL .= " LIMIT " . $limitstart . ',' . $limit ;
		
		// ARRAY ROW INFO
        $response['records']  = $count;
        $response['total']    = $total_pages;
        $response['page']     = $page;
		
		// MAIN RECORD
		$sql_main = str_replace("{COUNT_STR}"," ",$SQL);
		$response['data'] = $this->db->query($sql_main)->result_array();
		
		// SESSION LAST QUERY
		$querydata = array(
			'dtgQuery'  => $this->db->last_query()
        );
		$this->CI->session->set_userdata($querydata);
     
		// CONVERT ARRAY RESULT TO JSON VIEW
		return $response;
	}
	
	public function get_data($table, $select = array(), $where = array(), $like= array(), $joinmodel = array(), $fields = array(), $prefix = true, $additional = false, $userdata = array())
	{
		// VARIABLE 
		$limit      = $this->CI->input->get_post('rows');
        $limitstart = $this->CI->input->get_post('limitstart');
        $filter     = $this->CI->input->get_post('searchField');
        $filtertext = $this->CI->input->get_post('searchString');
        $oper       = $this->CI->input->get_post('searchOper');
        $sortby     = $this->CI->input->get_post('sidx');
        $sortdir    = $this->CI->input->get_post('sord');
        $page       = $this->CI->input->get_post('page');
		$set_data	= array();
		$response 	= array();
		$search 	= array();
		$fields2	= array();
		
		//$table = "master_kategori";
		
		// SQL
		//$SQL = "SELECT kat_id, kat_nama, kat_master, kat_level FROM ".$table;
		
		// GET TABLE NAME IN JOIN MODE
		$table_search = $table;
		if (false == empty($joinmodel)):		
			foreach ($joinmodel as $arr_join1):
				$get_join_field = $this->db->query('select * from '.$arr_join1['table']);
				foreach($get_join_field->list_fields() as $join_field):
					if ($filter == $join_field):
						$table_search = $arr_join1['table'];
						break;
					elseif ($sortby == $join_field):
						$table_search = $arr_join1['table'];
						break;
					endif;
				endforeach;
			endforeach;
		endif;	
		
		/* COUNT DATA -->*/
		if (true == empty($joinmodel)):
			$this->db->select();
			$this->db->from($table);
		endif;
		
		// FILTERS SEARCH -->
		$get_field = $this->db->list_fields($table);	
		foreach ($get_field as $field):
			$value = $this->CI->input->get_post($field);
			if (false == empty($value)):
				//$search[] = $table .'.' . $field . " like '%" . $value . "%'" ;
				$this->db->like($table_search .'.' . $field, $value );
			endif;
		endforeach;
		
		if( false == empty($filter) && false == empty($filtertext )):
			
            switch ($oper):
				case 'cn':
					$this->db->like( $table_search .'.' . $filter, $filtertext );
                break;
                case 'eq':
					$this->db->where( $table_search .'.' . $filter, $filtertext );
                break;
                case 'ge':
					$this->db->where( $table_search .'.' . $filter . ">=", $filtertext ) ;
                break;
                case 'le':
					$this->db->where( $table_search .'.' . $filter . "<=", $filtertext ) ;
                break;
                default:
					$this->db->like( $table_search .'.' . $filter, $filtertext );
                break;
            endswitch; 
        endif;
		
		if (false == empty($where)):
			if (is_array($where)):
				foreach ($where as $field => $value):
					$this->db->where( $field, $value );
				endforeach;
			endif;
		endif;
		
		// JOIN
		if(false == empty($joinmodel)):
            if(is_array($joinmodel)):            
                foreach($joinmodel as $model):
                    if(isset($model['table']) && isset($model['join'])):
					
                       $this->CI->db->join($model['table']/*tablename*/, $model['join']/*join fields*/, (isset($model['type']))?$model['type']:'inner'/*join type*/);
					   
                       if(isset($model['fields']) && false == empty($model['fields']) && $prefix):
                            foreach($model['fields'] as $field=>$extra):
								if (is_array($extra)):
									foreach ($extra as $func):
										$fields2[] = "$field({$model['table']}.{$func}) AS {$model['table']}_{$func}";
									endforeach;
								else:
									$fields2[] = "{$model['table']}.{$extra} AS {$model['table']}_{$extra}";
								endif;
                                
                            endforeach;       
                        else:
                            if(isset($model['fields']) && false == empty($model['fields'])):
                               foreach($model['fields'] as $field=>$extra):
									if (is_array($extra)):
										foreach ($extra as $func):
											$fields2[] = "$field({$model['table']}.{$func})";
										endforeach;
									else:
										$fields2[] = "{$model['table']}.{$extra}";
									endif;
                               endforeach;
                            endif;
                        endif;
                    endif;
                endforeach;
			endif;
 
            if(empty($fields) && $prefix):
                $fieldstable = $this->CI->db->list_fields($table);
                foreach($fieldstable as $field):                  
                    //$fields = array_push((array)$fields, (array)"{$field} AS {$table}_{$field}" );
                    $fields[] = "{$table}.{$field} AS {$table}_{$field}";
                endforeach;
            else:
                if(empty($fields)):
                    $fieldstable = $this->CI->db->list_fields($table);
                    foreach($fieldstable as $field):
                        //$fields = array_push((array)$fields, (array)"{$field} AS {$table}_{$field}" );
                        $fields[] = "{$table}.{$field}";
                    endforeach;
                endif;               
            endif;

            $fields = array_merge($fields, $fields2);

            $this->CI->db->select($fields);
            $this->CI->db->from($table);
            $jwhere = $this->CI->db->get();
        else:
			$jwhere = $this->CI->db->get();
        endif;
		
		// COUNT ROWS
		$count = $jwhere->num_rows();
		/* <-- END COUNT DATA */
		
		/* GET RESULT DATA --> */
		
		if (true == empty($joinmodel)):
			$this->CI->db->select('*');
			$this->CI->db->from($table);
		endif;
		
		// SQL
		//$SQL = "SELECT kat_id, kat_nama, kat_master, kat_level FROM ".$table;
		
		// FILTERS SEARCH -->
		$get_field = $this->db->list_fields($table);	
		foreach ($get_field as $field):
			$value = $this->CI->input->get_post($field);
			if (false == empty($value)):
				//$search[] = $table .'.' . $field . " like '%" . $value . "%'" ;
				$this->db->like( $table_search .'.' . $field, $value );
			endif;
		endforeach;
		
		if( false == empty($filter) && false == empty($filtertext )):
            switch ($oper):
				case 'cn':
					$this->db->like( $table_search .'.' . $filter, $filtertext );
                break;
                case 'eq':
					$this->db->where( $table_search .'.' . $filter, $filtertext );
                break;
                case 'ge':
					$this->db->where( $table_search .'.' . $filter . ">=", $filtertext ) ;
                break;
                case 'le':
					$this->db->where( $table_search .'.' . $filter . "<=", $filtertext ) ;
                break;
                default:
					$this->db->like( $table_search .'.' . $filter, $filtertext );
                break;
            endswitch; 
        endif;
		
		if (false == empty($where)):
			if (is_array($where)):
				foreach ($where as $field => $value):
					$this->db->where( $field, $value );
				endforeach;
			endif;
		endif;
		
		/* END RESULT DATA */
        
        if( $count > 0 )
            $total_pages = ceil($count/$limit);
        else
            $total_pages = 0;

		// ARRAY ROW INFO
        $response['records']  = $count;
        $response['total']    = $total_pages;
        $response['page']     = $page;
		
        if ($page > $total_pages)
            $page=$total_pages;

        $limitstart = $limit*$page - $limit; // do not put $limit*($page - 1)
        $limitstart = ($limitstart < 0)?0:$limitstart;

		// ORDER BY
		if( empty($sortdir) )
			$sortdir = "ASC";
		if( !empty($sortby) )
			$this->db->order_by($table_search.'.'.$sortby,$sortdir);
			//$SQL .= " ORDER BY " . $sortby . ' ' . $sortdir ;
	
		// LIMIT
        if( !isset($limitstart) || $limitstart == '' )
			$limitstart = 0;
        if( isset($limitstart) && !empty($limit) )
			$this->db->limit($limit,$limitstart);
			//$SQL .= " LIMIT " . $limitstart . ',' . $limit ;
			  		
		// JOIN
		if(false == empty($joinmodel)):
            if(is_array($joinmodel)):            
                foreach($joinmodel as $model):
                    if(isset($model['table']) && isset($model['join'])):
					
                       $this->CI->db->join($model['table']/*tablename*/, $model['join']/*join fields*/, (isset($model['type']))?$model['type']:'inner'/*join type*/);
					   
                       if(isset($model['fields']) && false == empty($model['fields']) && $prefix):
                            foreach($model['fields'] as $field=>$extra):
								if (is_array($extra)):
									foreach ($extra as $func):
										$fields2[] = "$field({$model['table']}.{$func}) AS {$model['table']}_{$func}";
									endforeach;
								else:
									$fields2[] = "{$model['table']}.{$extra} AS {$model['table']}_{$extra}";
								endif;
                                //$fields2[] = "{$model['table']}.{$field} AS {$model['table']}_{$field}";
                            endforeach;       
                        else:
                            if(isset($model['fields']) && false == empty($model['fields'])):
                               foreach($model['fields'] as $field=>$extra):
									if (is_array($extra)):
										foreach ($extra as $func):
											$fields2[] = "$field({$model['table']}.{$func})";
										endforeach;
									else:
										$fields2[] = "{$model['table']}.{$extra}";
									endif;
                               endforeach;
                            endif;
                        endif;
                    endif;
                endforeach;
			endif;
 
            if(empty($fields) && $prefix):
                $fieldstable = $this->CI->db->list_fields($table);
                foreach($fieldstable as $field):                  
                    //$fields = array_push((array)$fields, (array)"{$field} AS {$table}_{$field}" );
                    $fields[] = "{$table}.{$field} AS {$table}_{$field}";
                endforeach;
            else:
                if(empty($fields)):
                    $fieldstable = $this->CI->db->list_fields($table);
                    foreach($fieldstable as $field):
                        //$fields = array_push((array)$fields, (array)"{$field} AS {$table}_{$field}" );
                        $fields[] = "{$table}.{$field}";
                    endforeach;
                endif;               
            endif;
			
			$fields = array_merge($fields, $fields2);

            $this->CI->db->select($fields);
            $this->CI->db->from($table);
            $rs = $this->CI->db->get();
        else:
			$rs = $this->CI->db->get();
        endif;
		
		// ARRAY DATA RESULT 
		$response['data'] = $rs->result_array();
		if ($additional):
			$response['raw_data'] = $rs;
		endif;
		
		if (false == empty($userdata)):
			if ($rs->num_rows() > 0):
				$getrs = $rs->row_array();
				foreach ($userdata as $uinput=>$ufield):
					if (isset($getrs[$ufield]))
						$rdata = $getrs[$ufield];
					else
						$rdata = $ufield;
					$response['userdata'][$uinput] = $rdata;
				endforeach;
			endif;
		endif;
		
		// SESSION LAST QUERY
		$querydata = array(
			'dtgQuery'  => $this->db->last_query()
        );
		$this->CI->session->set_userdata($querydata);
     
		// CONVERT ARRAY RESULT TO JSON VIEW
		return $response;
	}
	
	function set_data($table,$id_field,$where = FALSE,$prefix = FALSE)
	{				
		$get_field = $this->db->list_fields($table);	
		foreach ($get_field as $field):
			if ($prefix):
				$value = $this->CI->input->get_post($table.'_'.$field);
			else:
				$value = $this->CI->input->get_post($field);
			endif;
			if (false == empty($value)):
				$data[$field] = $value;
			endif;
		endforeach;
		
		$id   = $this->CI->input->post('id');	// THIS default id
		$oper   = $this->CI->input->post('oper');
		
		if (is_array($where))
			$r = array_merge($where,$data);
		else
			$r = $data;
					
		//log_message("error", "set_data (".$oper.") : Table = ".$table." | ID = ".$id_field." | JSON = ".json_encode($r)." | GET = ".json_encode($_GET)." | POST = ".json_encode($_POST));
		
		unset($data[$id_field]);
	
        switch ($oper):
			case 'add' :
				if(false == empty($data))
					$this->db->insert($table, $data);
				echo'';
				return;
			break;
			case 'edit' : 
				if (is_array($where)):
					foreach ($where as $field=>$value):
						$this->db->where($field,$value);
					endforeach;
				else:
					$this->db->where($id_field, $id);
				endif;
				$this->db->update($table, $data);
				echo'';
				return;
			break;
			case 'del' :
				if (is_array($where)):
					foreach ($where as $field=>$value):
						$this->db->where($field,$value);
					endforeach;
				else:
					$this->db->where($id_field, $id);
				endif;
				$this->db->delete($table);
				echo'';
				return;
			break;
        endswitch;
	}

}