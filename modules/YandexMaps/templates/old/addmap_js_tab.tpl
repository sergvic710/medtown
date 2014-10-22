<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('js_template')}:</p>
  <p class="pageinput">
    <textarea name="{$actionid}js_template">{$map->get_js_template()}</textarea>
  </p>
</div>
<div class="pageoverflow">
  <p class="pageinput">
    <input type="submit" name="{$actionid}reset_js_template" value="{$mod->Lang('reset')}"/>
  </p>
</div>