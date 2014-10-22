{* sidebar template *}
<ul class="cggm_sidebar_list">
{foreach from=$sidebar_items key='marker_name' item='item'}
  <li class="cggm_sidebar_item" rel="{$marker_name}">
    <div>
    <span class="cggm_sidebar_item_title">{if $map->info_window}<a rel="{$map->get_id()}::{$marker_name}" class="cggm_sidebar_title_link">{$item->get_title()}</a>{else}{$item->get_title()}{/if}</span>
    {$item->get_description()}
    {* this is used for hiding sidebar items when categories are disabled *}
    <input type="hidden" name="categories" value="{$item->get_categories()}"/>
    </div>
  </li>
{/foreach}
</ul>