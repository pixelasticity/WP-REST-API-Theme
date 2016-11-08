(function () {
	'use strict';

	var base_url = '/wp-json/wp/v2/';
	
	// 1. Define route components.
	// These can be imported from other files
	var Posts = Vue.extend({
		name: 'posts-list',
		template: '#test',
		data: function () {
			return {
				posts: []
			};
		},
		methods: {
			getPosts: function () {
				this.$http.get(base_url + 'posts').then(function (response) {
					this.posts = response.data;
				}, function (response) {
					console.log(response);
				});
			}
		},
		filters: {
			formatDate: function (isoDate) {
				var lang = ["en-US"], //using an array because of quirk in Chrome
					date = new Date(isoDate),
					options = {
						weekday: undefined,
						year: "numeric",
						month: "long",
						day: "numeric"
					},
					result;

				result = date.toLocaleDateString(lang, options);
				console.log(result);
				return result;
			}
		},
		mounted: function () {
			this.$nextTick(function () {
				this.getPosts();
			});
		}
	});

	// 2. Define some routes
	// Each route should map to a component. The "component" can
	// either be an actual component constructor created via
	// Vue.extend(), or just a component options object.
	// We'll talk about nested routes later.
	var routes = [{ path: '/', name: 'frontpage', component: Posts }];

	// 3. Create the router instance and pass the `routes` option
	// You can pass in additional options here, but let's
	// keep it simple for now.
	var router = new VueRouter({
		routes: routes,
		mode: 'history'
	});

	// 4. Create and mount the root instance.
	// Make sure to inject the router with the router option to make the
	// whole app router-aware.
	var app = new Vue({
		router: router,
		mounted: function () {
			this.$nextTick(function () {
				this.updateTitle('');
			// Code assumes this.$el is in-document
			});
		},
		methods: {
			updateTitle: function (pageTitle) {
				document.title = (pageTitle ? pageTitle + ' - ' : '') + wp.site_name;
			}
		},
		events: {
			'page-title': function (pageTitle) {
				this.updateTitle(pageTitle);
			}
		}
	}).$mount('#app');
	
	var wp = document.getElementById("main");
	wp.remove(wp.selectedIndex); // Remove the server-rendered WP instance from the DOM
	
}());
