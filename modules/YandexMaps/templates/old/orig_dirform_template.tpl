{* directions form template *}
<div class="cggm_directions">
  {$CGGoogleMaps->Lang('prompt_directions')}: 
  <a rel="{$marker_name}" class="cggm_dir_to">{$CGGoogleMaps->Lang('prompt_to_here')}</a>
  <a rel="{$marker_name}" class="cggm_dir_from">{$CGGoogleMaps->Lang('prompt_from_here')}</a>
  <form class="cggm_directions_form" action="http://maps.google.com/maps" method="get" {if $map->directions_dest == 'WINDOW'}target="_blank"{/if} style="display: none;">
    {$CGGoogleMaps->Lang('prompt_directions')}:<br/>
    <span class="cggm_dirfrom">{$CGGoogleMaps->Lang('from')}: <input type="text" size="40" maxlength="40" name="saddr" value=""/><br/></span>
    <span class="cggm_dirto">{$CGGoogleMaps->Lang('to')}: <input type="text" size="40" maxlength="40" name="daddr" value=""/><br/></span>
    <input type="submit" value="{$CGGoogleMaps->Lang('get_directions')}"/>
  </form>
</div>
