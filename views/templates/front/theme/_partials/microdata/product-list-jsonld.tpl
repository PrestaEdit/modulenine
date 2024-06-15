{**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 *}
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "ItemList",
	"itemListElement": [
	{foreach $listing.products as $position => $item}
		{
			"@type": "ListItem",
			"position": {$position|intval},
			"name": "{$item.name}",
			"url": "{$item.url}"
		},
	{/foreach}
	]
}
</script>