{* category template *}
<form class="category_form" action="javascript:return(false);">
{foreach from=$map_category_names item=name}
{if isset($categories.$name)}
  {assign var=cat value=$categories.$name}
  <div class="category">
    <input type="checkbox" name="{$name}" class="category_checkbox" value="1" checked="checked"/>    
    {assign var='tmp' value=$cat.icon}
    <img src="{$icons.$tmp.url}" alt="{$cat.name}"/> {$name}
    {if isset($cat.info) && $cat.info != ''}
    <br/>{$cat.info}
    {/if}
  </div>
{else}
 {* no category information supplied *}
 <div class="category">
   <input type="checkbox" name="{$name}" class="category_checkbox" value="1" checked="checked"/> {$name}
 </div>
{/if}
{/foreach}
</form>