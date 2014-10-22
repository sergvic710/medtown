{* baloon template tab *}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('baloon_maptemplate')}:</p>
  <p class="pageinput">
    <textarea name="{$actionid}baloon_template">{$map->get_baloon_template()}</textarea>
  </p>
</div>
<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}reset_baloon_template" value="{$mod->Lang('reset')}"/>
  </p>
</div>
