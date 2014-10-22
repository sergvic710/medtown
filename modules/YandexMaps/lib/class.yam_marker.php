<?php

class yam_marker
{
  private $_marker_id;
  private $_map_id;
  private $_title;
  private $_description;
  private $_address;
  private $_lat;
  private $_long;
  private $_icon;
  private $_tooltip;
  private $_categories;
  private $_meta_description;
  private $_no_sv;
  private $_static = FALSE;

  public function __construct($title = null,$address = '',$lat = '',$long = '',$icon = '')
  {
      $address = trim($address);
      $title = trim($title);

      $this->_title   = $title;
      $this->_address = $address;
      $this->_lat     = $lat;
      $this->_long    = $long;
      $this->_icon    = $icon;  // should look this up somehow.
  }


  public function set_static($flag = TRUE)
  {
    $this->_static = (bool)$flag;
  }

  public function is_static()
  {
    return $this->_static;
  }

  public function set_marker_id($marker_id)
  {
    $this->_marker_id = $marker_id;
  }

  public function get_marker_id()
  {
    return $this->_marker_id;
  }

  public function set_map_id($map_id)
  {
    $this->_map_id = $map_id;
  }

  public function get_map_id()
  {
    return $this->_map_id;
  }

  public function get_title()
  {
    return $this->_title;
  }

  public function set_title($title)
  {
    $this->_title = $title;
  }

  public function set_description($description)
  {
    $this->_description = $description;
  }

  public function add_description($name,$description)
  {
    if( $this->_description ) {
      $this->_meta_description = array('orig'=>$this->_description);
      $this->_description = null;
    }
    if( !is_array($this->_meta_description) ) $this->_meta_description = array();

    $this->_meta_description[$name] = $description;
  }

  public function get_description()
  {
    if( is_array($this->_meta_description) )
      {
	return implode("\n",$this->_meta_description);
      }
    return $this->_description;
  }

  public function is_metapoint()
  {
    if( is_array($this->_meta_description) ) return TRUE;
    return FALSE;
  }

  public function set_desc($description)
  {
    $this->_meta_description = null;
    $this->_description = $description;
  }

  public function get_desc()
  {
    return $this->_description;
  }

  public function set_address($addr)
  {
    if( $addr != '' ) {
      $this->_address = $addr;
      $this->_lat = '';
      $this->_long = '';
    }
  }

  public function get_address()
  {
    return $this->_address;
  }

  public function set_coords($latitude,$longitude)
  {
    if( $latitude != '' && $longitude != '' ) {
      $this->_address = '';
      $this->_lat = (float)$latitude;
      $this->_long = (float)$longitude;
    }
  }

  public function get_latitude()
  {
    return (float)$this->_lat;
  }

  public function get_longitude()
  {
    return (float)$this->_long;
  }

  public function set_icon($icon)
  {
    $this->_icon = $icon;
  }

  public function get_icon()
  {
    return $this->_icon;
  }

  public function set_tooltip($tooltip)
  {
    if( !$tooltip ) {
      $this->_tooltip = null;
    }
    else {
      $this->_tooltip = $tooltip;
    }
  }

  public function get_tooltip()
  {
    return $this->_tooltip;
  }

  public function hide_streetview($flag = true)
  {
    $this->_no_sv = $flag;
  }

  public function has_streetview()
  {
    return !$this->_no_sv;
  }

  public function add_category($category)
  {
    if( !is_array($this->_categories) )	$this->_categories = array();
    if( !is_array($category) ) $category = explode(',',$category);
    $this->_categories = array_unique(array_merge($this->_categories,$category));
  }

  public function count_categories()
  {
    return count($this->_categories);
  }

  public function get_categories($as_array = false)
  {
    if( is_array($this->_categories) ) {
      if( $as_array ) return $this->_categories;
      return implode(',',$this->_categories);
    }
  }

  public function get_categories_as_array()
  {
    if( is_array($this->_categories) ) return $this->_categories;
  }

  public function has_category($catname)
  {
    if( !is_array($this->_categories) ) return FALSE;
    if( !$catname ) return FALSE;

    if( !is_array($catname) ) $catname = explode(',',$catname);
    foreach( $catname as $one ) {
      if( in_array($catname,$this->_categories) ) return TRUE;
    }
    return FALSE;
  }

  public function from_array($data)
  {
    if( isset($data['map_id']) ) $this->set_map_id($data['map_id']);
    if( isset($data['marker_id']) ) $this->set_marker_id($data['marker_id']);
    if( isset($data['name']) ) {
      $this->set_title($data['name']);
    }
    else {
      $this->set_title($data['title']);
    }
    $this->set_desc($data['info']);
    $this->set_tooltip($data['tooltip']);
    $this->set_address($data['address']);
    if( isset($data['no_sv']) ) $this->hide_sv($data['no_sv']);
    if( isset($data['latitude']) && isset($data['longitude']) && $data['latitude'] != $data['longitude'] ) {
      $this->set_coords($data['latitude'],$data['longitude']);
    }
    else if( isset($data['lat']) && isset($data['lon']) && $data['lat'] != $data['lon'] ) {
      $this->set_coords($data['lat'],$data['lon']);
    }
    $this->set_icon($data['icon']);
    if( isset($data['categories']) ) $this->add_category($data['categories']);
  }

  public function save()
  {
    if( !$this->_marker_id ) {
      return yam_marker_operations::insert($this);
    }
    else {
      return yam_marker_operations::update($this);
    }
  }

} // end of class

?>