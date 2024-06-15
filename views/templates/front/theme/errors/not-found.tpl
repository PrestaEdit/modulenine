{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($CE_LISTING_NO_RESULTS)}
	{$CE_LISTING_NO_RESULTS|cefilter}
{elseif file_exists("{$smarty.const._PS_THEME_DIR_}templates/errors/not-found.tpl")}
	{include '[1]errors/not-found.tpl'}
{elseif $smarty.const._PARENT_THEME_NAME_}
	{include 'parent:errors/not-found.tpl'}
{/if}