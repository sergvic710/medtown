{foreach from=$fields_directions key='name' item='field'}
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
      <input type="text" name="{$actionid}{$name}" size="{$field.size|default:40}" maxlength="{$field.maxlength|default:255}" value="{$field.value}"/>
    {/if}

    {if isset($field.info_key)}
    <br/>
    {$mod->Lang($field.info_key)}
    {/if}
  </p>
</div>
{/foreach}