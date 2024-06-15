/*!
 * Creative Elements - live Theme & Page Builder
 * Copyright 2019-2024 WebshopWorks.com
 */

document.addEventListener('DOMContentLoaded', function() {
	var prev;
	$('.elementor').each(function() {
		var uid = (this.className.match(/elementor-(\d+)/) || '')[1];
		if (uid && uid !== prev) {
			prev = uid;
			$(this).addClass('ce-edit-wrapper');
			$('<a class="ce-edit-btn"><i class="ce-icon">').attr({
				href: ceFrontendEdit.editor_url + '&uid=' + uid,
				title: ceFrontendEdit.edit_title,
			}).appendTo(this);
			$('<div class="ce-edit-outline">').appendTo(this);
		}
	});
});
