{$formstart}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('prompt_lookup_service')}:</p>
  <p class="pageinput">
    <select name="{$actionid}lookup_service">
    {html_options options=$lookup_services selected=$lookup_service}
    </select>
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('prompt_lookup_policy')}:</p>
  <p class="pageinput">
    <select name="{$actionid}lookup_policy">
    {html_options options=$lookup_policies selected=$lookup_policy}
    </select>
  </p>
</div>

<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">{$submit}<input type="submit" name="{$actionid}clearcache" value="{$mod->Lang('clearcache')}"/></p>
</div>
{$formend}