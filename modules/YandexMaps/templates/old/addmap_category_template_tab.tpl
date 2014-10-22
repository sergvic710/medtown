<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('category_template')}:</p>
  <p class="pageinput">
    <textarea name="{$actionid}category_template">{$map->get_category_template()}</textarea>
  </p>
</div>
<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}reset_category_template" value="{$mod->Lang('reset')}"/>
  </p>
</div>
