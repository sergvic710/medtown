<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('map_template')}:</p>
  <p class="pageinput">
    <textarea name="{$actionid}map_template">{$map->get_map_template()}</textarea>
  </p>
</div>
<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}reset_map_template" value="{$mod->Lang('reset')}"/>
  </p>
</div>