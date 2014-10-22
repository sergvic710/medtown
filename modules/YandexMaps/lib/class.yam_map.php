<?php

final class yam_map extends friendly
{
  private $_map_id;
  private $_name;
  private $_owner_id;
  private $_data = array();
  private static $_types = array();
  private $_markers;
  private $_kml_files;
  private $_unsaved_map_template;
  private $_unsaved_directions_template;

  public function __construct()
  {
    $this->add_friend('yam_map_operations,YandexMaps,yam_map');
    if( !count(self::$_types) ) {
      $mod = cge_utils::get_module('YandexMaps');
      $yam_map_fields = array();
      include_once(__DIR__.'/map_fields.dat');
      if( count($yam_map_fields) ) {
	foreach( $yam_map_fields as $key => &$rec ) {
	  if( !isset($rec['type']) ) $rec['type'] = 'TEXT';
	  switch($rec['type']) {
	  case 'SELECT':
	    $tmp = cge_array::explode_with_key($rec['options'],'=>',',');
	    foreach( $tmp as $k => &$v ) {
	      if( startswith($v,'k:') ) {
		$t2 = substr($v,2);
		$v = $mod->Lang($t2);
	      }
	    }
	    $rec['options'] = cge_array::implode_with_key($tmp,'=>',',');
	    break;
	  }
	}
	self::$_types = $yam_map_fields;
      }
    }

    if( count(self::$_types) ) {
      // sets some defaults for the member variables.
      foreach( self::$_types as $prop => $info ) {
	if( $prop == 'id' || $prop == 'map_id' ) continue; // no default for you.
	if( isset($info['dflt']) ) $this->$prop = $info['dflt'];
      }
    }
  }


  public function set_id($id)
  {
    $this->is_friendly();
    $this->_map_id = (int)$id;
  }


  public function get_id()
  {
    return $this->_map_id;
  }


  public function set_owner_id($owner_id)
  {
    $this->is_friendly();
    $this->_owner_id = (int)$owner_id;
  }


  public function get_owner_id()
  {
    return $this->_owner_id;
  }


  public function set_name($name)
  {
    $this->is_friendly();
    $this->_name = (string)$name;
  }


  public function get_name()
  {
    return $this->_name;
  }


  public function get_data()
  {
    $this->is_friendly();
    return serialize($this->_data);
  }


  public function set_data($data)
  {
    $this->is_friendly();
    if( is_string($data) ) {
      $tmp = unserialize($data);
      if( $tmp && is_array($tmp) ) {
	// todo: check for keys.
	$this->_data = $tmp;
      }
    }
  }


  public function get_fields()
  {
    return self::$_types;
  }


  public function field_exists($key)
  {
    if( isset(self::$_types[$key]) ) return TRUE;
    return FALSE;
  }


  public function __get($key)
  {
    switch( $key ) {
    case 'name':
      return $this->_name;
    case 'id':
      return $this->_map_id;
    case 'owner_id':
      return $this->_owner_id;
    default:
      if( in_array($key,array_keys(self::$_types)) ) {
	if( isset($this->_data[$key]) ) {
	  return $this->_data[$key];
	}
	else {
	  return;
	}

	$trace = debug_backtrace();
        trigger_error('Undefined property via __get(): '.$key.' in '.$trace[0]['file'].' on line ' . $trace[0]['line'], E_USER_NOTICE);
      }
      break;
    }
  }


  public function __set($key,$value)
  {
      switch( $key ) {
      case 'name':
          return $this->set_name($value);
      case 'id':
          return $this->set_id($value);
      case 'owner_id':
          return $this->set_owner_id($value);
      default:
          if( in_array($key,array_keys(self::$_types)) ) {
              // todo: type checking.
              $this->_data[$key] = $value;
              return;
          }

          $trace = debug_backtrace();
          throw new Exception('Undefined property via __set(): '.$key.' in '.$trace[0]['file'].' on line '.$trace[1]['line']);
      }
  }


  public function save($deep = false)
  {
    if( empty($this->_map_id) )	return yam_map_operations::insert($this,$deep);

    return yam_map_operations::update($this,$deep);
  }


  public function add_kml($href)
  {
    if( !is_array($this->_kml_files) ) $this->_kml_files = array();

    $this->_kml_files[] = $href;
  }


  public function get_kml_files()
  {
    if( !is_array($this->_kml_files) || count($this->_kml_files) == 0 ) return;

    return $this->_kml_files;
  }


  protected function generate_marker_name($title)
  {
    // clean the title
    $name = munge_string_to_url($title);
    $name = str_replace('-','_',$name);
    $tmp = '';
    $n = 0;
    while( $n < 10 ) {
      $tmp = str_replace('__','_',$name);
      if( $tmp == $name ) break;
      $name = $tmp;
      $n++;
    }

    // generate an unused name.
    $n = 1;
    $tmp = $name;
    while( isset($this->_markers[$tmp]) && $n < 100 ) {
      $n++;
      $tmp = $name.'_'.$n;
    }
    $name = $tmp;

    if( $n == 100 ) return FALSE;
    return $name;
  }


  public function add_marker(yam_marker& $marker)
  {
    if( !is_array($this->_markers) ) $this->_markers = array();

    $name = $this->generate_marker_name($marker->get_title());
    if( !$name ) return FALSE;
    $this->_markers[$name] = $marker;
    return $name;
  }


  public function delete_marker($name)
  {
    if( !is_array($this->_markers) ) return FALSE;
    if( !isset($this->_markers[$name]) ) return FALSE;
    unset($this->_markers[$name]);
    return TRUE;
  }

  public function delete_unsaved_markers()
  {
    if( !is_array($this->_markers) ) return FALSE;
    foreach( $this->_markers as $name => $obj ) {
      if( $obj->get_marker_id() > 0 ) continue;
      unset($this->_markers[$name]);
    }
  }

  public function &get_marker_by_idx($idx)
  {
    $this->is_friendly();
    if( !is_array($this->_markers) ) return;
    if( $idx >= count($this->_markers) || $idx < 0 ) return;

    $tmp = $trace = debug_backtrace();
    if(isset($trace[1]['class']) && $trace[1]['class'] == 'yam_map_operations') return $this->_markers[$idx];
  }


  public function &get_marker_by_id($marker_id)
  {
    if( !is_array($this->_markers) ) return FALSE;
    foreach( $this->_markers as $name => $marker ) {
      if( $marker->get_marker_id() == $marker_id ) return $marker;
    }
    return FALSE;
  }


  public function &get_marker_by_name($name)
  {
    if( !is_array($this->_markers) ) return FALSE;
    if( !isset($this->_markers[$name]) ) return FALSE;
    return $this->_markers[$name];
  }


  public function get_marker_names()
  {
    if( !is_array($this->_markers) ) return FALSE;
    return array_keys($this->_markers);
  }


  public function count_markers()
  {
    return count($this->_markers);
  }


  public function get_map_template_name()
  {
    $name = 'maptemplate_'.$this->_map_id;
    return $name;
  }

  public function get_map_template($unsaved = false)
  {
    $mod = cge_utils::get_module('YandexMaps');
    if( $this->_unsaved_map_template ) return $this->_unsaved_map_template;

    if( $unsaved ) return;
    if( $this->_map_id ) {
      $txt = $mod->GetTemplate($this->get_map_template_name());
      return $txt;
    }
    else {
      $fn = dirname(__DIR__).'/templates/orig_map_template.tpl';
      return file_get_contents($fn);
    }
  }

  public function set_map_template($data)
  {
    $this->_unsaved_map_template = $data;
  }

  public function get_directions_template_name()
  {
    $name = 'directionstemplate_'.$this->_map_id;
    return $name;
  }

  public function get_directions_template($unsaved = false)
  {
    $mod = cge_utils::get_module('YandexMaps');
    if( $this->_unsaved_directions_template ) return $this->_unsaved_directions_template;

    if( $unsaved ) return;
    if( $this->_map_id ) {
      $txt = $mod->GetTemplate($this->get_directions_template_name());
      return $txt;
    }
    else {
      $fn = dirname(__DIR__).'/templates/orig_directions_template.tpl';
      return file_get_contents($fn);
    }
  }

  public function set_directions_template($data)
  {
    $this->_unsaved_directions_template = $data;
  }

} // end of class

?>