<?php

class yam_icon
{
  private $_name;
  private $_url;
  private $_width = '';
  private $_height = '';
  private $_anchor_x = 'x';
  private $_anchor_y = 'x';
  private $_info_anchor_x = 'x';
  private $_info_anchor_y = 'x';
  private $_shadow_icon = '';
  private $_shadow_width = '';
  private $_shadow_height = '';


  public function __construct($name,$url,$anchor_x = 'x',$anchor_y = 'x',$info_anchor_x = 'x',$info_anchor_y = 'x')
  {
    $this->_name = $name;
    $this->_url = $url;
    $this->_anhor_x = $anchor_x;
    $this->_anhor_y = $anchor_y;
    $this->_info_anhor_x = $info_anchor_x;
    $this->_info_anhor_y = $info_anchor_y;
  }


  public function set_name($name)
  {
    $this->_name = $name;
  }


  public function get_name($name)
  {
    return $this->_name;
  }


  public function set_url($url)
  {
    $this->_url = $url;
  }


  public function get_url($url)
  {
    return $this->_url;
  }


  public function set_width($width)
  {
    $this->_width = $width;
  }


  protected function _get_iamge_info($url)
  {
      $gCms = cmsms();
    $config = $gCms->GetConfig();
    $fn = $config['root_path'].'/'.$url;
    if( !file_exists($fn) ) return FALSE;

    $tmp = getimagesize($fn);
    return $tmp;
  }

  public function get_width()
  {
    if( $this->_width == '' || $this->_height == '' )
      {
	$tmp = $this->_get_image_info($this->get_url());
	if( $tmp === FALSE ) return FALSE;
	$this->_width = $tmp[0];
	$this->_height = $tmp[1];
      }
    return $this->_width;
  }


  public function set_height($height)
  {
    $this->_height = $height;
  }


  public function get_height()
  {
    if( $this->_width == '' || $this->_height == '' )
      {
	$tmp = $this->_get_image_info($this->get_url());
	if( $tmp === FALSE ) return FALSE;
	$this->_width = $tmp[0];
	$this->_height = $tmp[1];
      }
    return $this->_height;
  }


  public function set_anchor_x($anchor_x)
  {
    $this->_anchor_x = $anchor_x;
  }


  public function get_anchor_x($anchor_x)
  {
    return $this->_anchor_x;
  }


  public function set_anchor_y($anchor_y)
  {
    $this->_anchor_y = $anchor_y;
  }


  public function get_anchor_y($anchor_y)
  {
    return $this->_anchor_y;
  }


  public function set_info_anchor_y($info_anchor_y)
  {
    $this->_info_anchor_y = $info_anchor_y;
  }


  public function get_info_anchor_y($info_anchor_y)
  {
    return $this->_info_anchor_y;
  }


  public function set_info_anchor_x($info_anchor_x)
  {
    $this->_info_anchor_x = $info_anchor_x;
  }


  public function get_info_anchor_x($info_anchor_x)
  {
    return $this->_info_anchor_x;
  }


  public function from_array($data)
  {
    foreach($data as $key => $value )
      {
	if( isset($this->$key) )
	  {
	    $this->$key = $value;
	  }
      }
  }


  public function set_shadow_icon($shadow_icon)
  {
    $this->_shadow_icon = $shadow_icon;
  }


  public function get_shadow_icon($shadow_icon)
  {
    return $this->_shadow_icon;
  }


  public function set_shadow_width($shadow_width)
  {
    $this->_shadow_width = $shadow_width;
  }


  public function get_shadow_width()
  {
    if( $this->_shadow_width == '' || $this->_shadow_height == '' )
      {
	$tmp = $this->_get_image_info($this->get_shadow_icon());
	if( $tmp === FALSE ) return FALSE;
	$this->_shadow_width = $tmp[0];
	$this->_shadow_height = $tmp[1];
      }
    return $this->_shadow_width;
  }


  public function set_shadow_height($shadow_height)
  {
    $this->_shadow_height = $shadow_height;
  }


  public function get_shadow_height($shadow_height)
  {
    if( $this->_shadow_width == '' || $this->_shadow_height == '' )
      {
	$tmp = $this->_get_image_info($this->get_shadow_icon());
	if( $tmp === FALSE ) return FALSE;
	$this->_shadow_width = $tmp[0];
	$this->_shadow_height = $tmp[1];
      }
    return $this->_shadow_height;
  }

} // end of class

?>