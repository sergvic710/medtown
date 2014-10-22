<?php

final class yam_marker_operations
{
  private function __construct() {}

  public static function load_marker_from_data($data)
  {
    $marker = new yam_marker('junk','junk');
    $marker->from_array($data);
    return $marker;
  }


  public static function load_markers_for_map(yam_map& $map)
  {
    $gCms = cmsms();
    $db = $gCms->GetDb();

    $query = 'SELECT * FROM '.cms_db_prefix().'module_yandexmaps_points WHERE map_id = ? ORDER BY marker_id';
    $tmp = $db->GetArray($query,array($map->get_id()));
    if( !is_array($tmp) ) return FALSE;

    foreach( $tmp as $row ) {
      $marker = self::load_marker_from_data($row);
      $marker->set_static();
      $map->add_marker($marker);
    }
    return TRUE;
  }


  public static function insert(yam_marker& $marker)
  {
    $gCms = cmsms();
    $db = $gCms->GetDb();

    $query = 'INSERT INTO '.cms_db_prefix().'module_yandexmaps_points
               (map_id,name,info,address,lat,lon,tooltip,icon)
              VALUES (?,?,?,?,?,?,?,?)';
    $dbr = $db->Execute($query,
			array($marker->get_map_id(),
			      $marker->get_title(),
			      $marker->get_description(),
			      $marker->get_address(),
			      $marker->get_latitude(),
			      $marker->get_longitude(),
                  $marker->get_tooltip(),
			      $marker->get_icon()));

    if( !$dbr ) return FALSE;
    $marker_id = $db->Insert_Id();
    $marker->set_marker_id($marker_id);
    return TRUE;
  }


  public static function update(yam_marker& $marker)
  {
    $gCms = cmsms();
    $db = $gCms->GetDb();

    $query = 'UPDATE '.cms_db_prefix().'module_yandexmaps_points
              SET name = ?, info = ?, address = ?, lat = ?, lon = ?, icon = ?, tooltip = ?
              WHERE marker_id = ? AND map_id = ?';
    $dbr = $db->Execute($query,
			array($marker->get_title(),
			      $marker->get_description(),
			      $marker->get_address(),
			      $marker->get_latitude(),
			      $marker->get_longitude(),
			      $marker->get_icon(),
                  $marker->get_tooltip(),
			      $marker->get_marker_id(),
			      $marker->get_map_id()));

    if( $dbr ) return TRUE;
    return FALSE;
  }
} // end of class

?>