<?php

class yam_meta_marker extends yam_marker
{
  private $_desc_override;
  private $_members;

  public function __construct(yam_marker& $marker, $icon = '')
  {
    parent::__construct('meta marker','',$marker->get_latitude(),$marker->get_longitude(),$icon);
    $this->_members = array($marker);
  }


  public function set_map_id($map_id)
  {
    throw new Exception('cannot set mapid on meta marker');
  }


  public function set_marker_id($map_id)
  {
    throw new Exception('cannot set marker on meta marker');
  }


  public function set_description($str)
  {
    parent::set_description($str);
    $this->_desc_override = 1;
  }


  public function get_description()
  {
    if( $this->_desc_override == 1 )
      {
	return parent::get_description();
      }
    
    return $this->_members[0]->get_description();
  }


  public function get_categories($as_array = false)
  {
    $tmp = array();
    for( $i = 0; $i < count($this->_members); $i++ )
      {
	$t1 = $this->_members[$i]->get_categories(true);
	if( is_array($t1) )
	  {
	    $tmp = array_merge($tmp,$this->_members[$i]->get_categories(true));
	  }
      }
    $tmp = array_unique($tmp);

    if( !$as_array )
      {
	return implode(',',$tmp);
      }
    return $tmp;
  }


  public function set_address($addr)
  {
    throw new Exception('cannot set address on meta marker');
  }


  public function get_address()
  {
    throw new Exception('cannot get address on meta marker');
  }


  public function set_coords($latitude,$longitude)
  {
    throw new Exception('cannot set coords on meta marker');
  }


  public function from_array($data)
  {
    throw new Exception('invalid operation from_array on meta marker');
  }


  public function save()
  {
    throw new Exception('invalid operation save on meta marker');
  }


  public function add_marker(yam_marker& $marker)
  {
    $this->_members[] = $marker;
  }


  public function count_markers()
  {
    return count($this->_members);
  }


  public function get_marker($i)
  {
    if( $i >= 0 && $i < $this->count_markers() )
      {
	return $this->_members[$i];
      }

    throw new Exception('attempt to retrieve invalid marker '.$i.' from meta marker');
  }
} // class

?>