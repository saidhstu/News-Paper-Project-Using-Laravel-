// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */
// window.Vue = require('vue');
// const app = new Vue({el: '#app'});
//Materialize
require('materialize-css');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHTTPRequest';
let metaTag;
if (metaTag = document.querySelector('meta[name="csrf-token]'))
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = metaTag.content;

/**
 * CUSTOM SCRIPTS
 */
//Global
const IsIE = (function detectIE() {
	const userAgent = window.navigator.userAgent;
	if (userAgent.indexOf('Trident/') > 0) {
		const rv = userAgent.indexOf('rv:');
		return parseInt(userAgent.substring(rv + 3, userAgent.indexOf('.', rv)), 10);
	}
	return false;
})();

new M.Sidenav(document.querySelector('.sidenav'));
const selects = document.querySelectorAll('select:not(.excludeSelect)');
for (let i = 0; i < selects.length; ++i) {
	if (IsIE)
		selects[i].className += " browser-default";
	else
		new M.Select(selects[i]);
}
const modals = document.querySelectorAll('.modal');
for (let i = 0; i < modals.length; ++i)
	new M.Modal(modals[i]);
tinymce.init({selector: "#fakeFeedBox", menubar: false});
const charCounters = document.querySelectorAll(".charCount");
for (let i = 0; i < charCounters.length; ++i)
	new M.CharacterCounter(charCounters[i]);