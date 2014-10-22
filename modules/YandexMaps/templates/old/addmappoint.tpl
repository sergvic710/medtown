{literal}
<script type="text/javascript">
/*<![CDATA[*/

var iconsbyname = new Array();
{/literal}
{foreach from=$iconsbyname key='name' item='url'}
iconsbyname['{$name}'] = '../{$url}';
{/foreach}
{literal}

function onselicon()
{
  var sel = document.getElementById('sel_icon');
  var idx = sel.selectedIndex;
  var val = sel[idx].value;

  var img = document.getElementById('img_icon');
  img.src = iconsbyname[val];
}

function onpointtype()
{
  var sel = document.getElementById('point_type');
  var idx = sel.selectedIndex;
  var val = sel[idx].value;

  var addr = document.getElementById('addr');
  var latlon = document.getElementById('latlon');
  if( val == 'addr' )
    {
      addr.style.display = 'block';
      latlon.style.display = 'none';
    }
  else
    {
      addr.style.display = 'none';
      latlon.style.display = 'block';
    }
}

/*]]> */
</script>
{/literal}

<h3>{$title}</h3>
{$formstart}{$hidden|default:''}
<p class="pageoverflow">
</p>
<div class="pageoverflow">
  <p class="pagetext">{$prompt_name}:</p>
  <p class="pageinput">{$input_name}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('icon')}:</p>
  <p class="pageinput">
    <select id="sel_icon" name="{$actionid}icon" onchange="onselicon();">
      {html_options options=$icons selected=$sel_icon}
    </select>
    <br/>
    <img style="margin-top: 2px;" id="img_icon" src="../{$iconsbyname.$sel_icon}" alt=""/>
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$prompt_info}:</p>
  <p class="pageinput">{$input_info}</p>
</div>
{assign var='categories' value=$marker->get_categories()}
{if $marker_categories}
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('prompt_categories')}:</p>
  <p class="pageinput">
    <select name="{$actionid}categories[]" multiple="multiple" size="5">
      {html_options options=$marker_categories selected=$marker->get_categories_as_array()}
    </select>
  </p>
</div>
{/if}

<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">{$info_address_latlong}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$mod->Lang('point_type')}:</p>
  <p class="pageinput">
    <select id="point_type" name="point_type" onchange="onpointtype();">
      <option value="addr">{$mod->Lang('address')}</option>
      <option value="latlon" {if !isset($point_address)}selected="selected"{/if}>{$mod->Lang('latlong')}</option>
    </select>
  </p>
</div>
<div id="addr" class="pageoverflow" {if !isset($point_address)}style="display: none;"{/if}>
  <p class="pagetext">{$prompt_address}:</p>
  <p class="pageinput">{$input_address}</p>
</div>

<div id="latlon" {if isset($point_address)}style="display: none;"{/if}">
<div class="pageoverflow">
  <p class="pagetext">{$prompt_lat}:</p>
  <p class="pageinput">{$input_lat}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$prompt_lon}:</p>
  <p class="pageinput">{$input_lon}</p>
</div>
</div>

<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">{$submit}{$cancel}</p>
</div>
{$formend}