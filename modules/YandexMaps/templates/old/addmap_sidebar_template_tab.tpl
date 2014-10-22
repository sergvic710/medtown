<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('sidebar_template')}:</p>
  <p class="pageinput">
    <textarea name="{$actionid}sidebar_template">{$map->get_sidebar_template()}</textarea>
  </p>
</div>
<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}reset_sidebar_template" value="{$mod->Lang('reset')}"/>
  </p>
</div>
