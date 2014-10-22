<script type="text/javascript">
var all_icons = new Array();
{foreach from=$all_icons key='name' item='url'}
all_icons['{$name}'] = '{$url}';
{/foreach}
{literal}
jQuery(document).ready(function(){
  jQuery('.icon_sel').each(function(){
    var val = jQuery(this).val();
    var img = all_icons[val];
    jQuery(this).next('img').attr('src','../' + img);
  });
  jQuery('.icon_sel').change(function(){
    var val = jQuery(this).val();
    var img = all_icons[val];
    jQuery(this).next('img').attr('src','../' + img);
  });
});
{/literal}
</script>

<div class="pageoverflow">
  <p class="pagetext">*{$mod->Lang('map_name')}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}name" value="{$map->get_name()}" size="40" maxlength="80"/>
</div>

{foreach from=$fields key='name' item='field'}
{* hmmm, i can see some kind of library coming here... with a template for controlling the display of fields *}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang($field.prompt_key)}:</p>
  <p class="pageinput">
    {if $field.type == 'TEXTAREA'}
      {cge_textarea prefix=$actionid name=$name wysiwyg=$field.wysiwyg content=$field.value}
    {elseif $field.type == 'ICON'}
      <select class="icon_sel" name="{$actionid}{$name}">
      {html_options options=$iconsbyname selected=$field.value}
      </select>
      <img src=""/>
    {elseif $field.type == 'BOOL'}
      <select name="{$actionid}{$name}">
      {cge_yesno_options selected=$field.value}
      </select>
    {elseif $field.type == 'SELECT'}
      <select name="{$actionid}{$name}">
      {cge_str_to_assoc input=$field.options delim1=',' delim2='=>' assign='sel_options'}
      {html_options options=$sel_options selected=$field.value}
      </select>
    {else}
      {* handles FLOAT and INT fields too *}
      <input type="text" name="{$actionid}{$name}" size="{$field.size|default:40}" maxlength="{$field.maxlength|default:255}" value="{$field.value|default:$field.dflt}"/>
    {/if}

    {if isset($field.info_key)}
    <br/>
    {$mod->Lang($field.info_key)}
    {/if}
  </p>
</div>
{/foreach}