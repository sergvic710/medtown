<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('dirform_maptemplate')}:</p>
  <p class="pageinput">
    <textarea name="{$actionid}dirform_template">{$map->get_dirform_template()}</textarea>
  </p>
</div>
<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}reset_dirform_template" value="{$mod->Lang('reset')}"/>
  </p>
</div>
