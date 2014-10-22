<?php

class yam_address_lookup
{
  static private $_lookup_service;
  static private $_lookup_policy;

  static private function initialize()
  {
      if( self::$_lookup_service == '' ) {
          $mod = cge_utils::get_module('YandexMaps');
          if( $mod ) {
              self::$_lookup_service = $mod->GetPreference('lookup_service','GOOGLE');
              self::$_lookup_policy  = $mod->GetPreference('lookup_policy','CACHE');
          }
      }
  }

  static private function cache_lookup($address)
  {
      if( empty($address) ) return FALSE;

      $db = cmsms()->GetDb();
      $query = 'SELECT lon,lat FROM '.cms_db_prefix().'module_yandexmaps_cache WHERE address = ?';
      $tmp = $db->GetRow($query,array($address));
      if( !$tmp || !is_array($tmp) ) return FALSE;

      return array($tmp['lat'],$tmp['lon']);
  }

  static private function cache_address($address,$coords)
  {
      if( !$address || !is_array($coords) || count($coords) != 2 ) return FALSE;

      $db = cmsms()->GetDb();
      $query = 'INSERT INTO '.cms_db_prefix().'module_yandexmaps_cache (address,lat,lon) VALUES (?,?,?)';
      $dbr = $db->Execute($query,array($address,$coords[0],$coords[1]));
      if( !$dbr ) {
          debug_display($db->sql); debug_display($db->ErrorMsg()); die();
          return FALSE;
      }
      return TRUE;
  }


  static public function geo_lookup($address)
  {
      $address = trim($address);
      switch( self::$_lookup_service ) {
      case 'GOOGLE':
      default:
//          $address = str_replace('%20','+',rawurlencode($address));
          //$address = str_replace(' ','+',$address);
//          $url = sprintf('http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=%s',$address);
          $url = sprintf('http://geocode-maps.yandex.ru/1.x/?geocode=%s&format=json',$address);
          $res = cge_http::get($url,'',FALSE);
          if( $res ) {
              $tmp = json_decode($res);
//              if( !isset($tmp->status) || $tmp->status != 'OK' ) {
              if( $tmp->response->GeoObjectCollection->metaDataProperty->GeoResponseMetaData->found > 0) 
                  return array((float)$tmp->results[0]->geometry->location->lat,(float)$tmp->results[0]->geometry->location->lng);
                  else {
                  audit('','yam_address_lookup','Address lookup of '.$address.' returned '.$tmp->status);
                  return FALSE;
              }
          }
          break;
      }

      return FALSE;
  }

  static public function lookup($address)
  {
      if( empty($address) ) return FALSE;

      self::initialize();
      $address = trim($address);
      $coords = FALSE;

      switch( self::$_lookup_policy ) {
      case 'NOCACHE':
          $coords = self::geo_lookup($address);
          break;

      case 'CACHEONLY':
          $coords = self::cache_lookup($address);
          break;

      case 'CACHEFIRST':
      default:
          $coords = self::cache_lookup($address);
          if( !$coords ) {
              $coords = self::geo_lookup($address);
              if( !$coords ) return FALSE;
              self::cache_address($address,$coords);
          }
          break;
      }

      return $coords;
  }

  static public function cache_lookup_multiple($addresses)
  {
      if( !is_array($addresses) || count($addresses) == 0 ) return;

      // eliminate empties
      $db = cmsms()->GetDb();
      $tmp = array();
      foreach( $addresses as $one ) {
          if( !$one ) continue;
          $tmp[] = $db->qstr($one);
      }
      $query = 'SELECT address,lon,lat FROM '.cms_db_prefix().'module_yandexmaps_cache WHERE address IN ('.implode(',',$tmp).')';
      $tmp = $db->GetArray($query);
      if( !is_array($tmp) || count($tmp) == 0 ) return;

      $out = array();
      foreach( $tmp as $row ) {
          $out[$row['address']] = array($row['lat'],$row['lon']);
      }
      return $out;
  }

  static public function lookup_multiple($addresses)
  {
      if( !is_array($addresses) || count($addresses) == 0 ) return;

      $out = array();
      self::initialize();
      switch( self::$_lookup_policy ) {
      case 'NOCACHE':
          foreach( $addresses as $one ) {
//              $tmp = self::geo_lookup($address);
//              if( is_array($tmp) ) $out[$address] = $tmp;
              $tmp = self::geo_lookup($one);
              if( is_array($tmp) ) $out[$one] = $tmp;
          }
          break;

      case 'CACHEONLY':
          $out = self::cache_lookup_multiple($addresses);
          break;

      case 'CACHEFIRST':
      default:
          $missing = $addresses;
          $tmp = self::cache_lookup_multiple($addresses);
          if( is_array($tmp) && count($tmp) ) {
              $missing = array();
              $keys = array_keys($tmp);
              foreach( $addresses as $one ) {
                  if( !in_array($one,$keys) ) $missing[] = $one;
              }
          }
          if( is_array($missing) && count($missing) ) {
              foreach( $missing as $one ) {
                  $coords = self::geo_lookup($one);
                  if( $coords ) {
                      $tmp[$one] = $coords;
                      self::cache_address($one,$coords);
                  }
              }
          }
          return $tmp;
      }
  }
} // end of class

?>