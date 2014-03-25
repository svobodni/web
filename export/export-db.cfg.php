<?php
use V6ak\DB\Export\DSL as D;

return call_user_func(function () {
	$route = D\ok("id, page_id, route_id, extendedPage_id");
	$page = D\ok("id, page_id, extendedMainRoute_id");
	$unsecuredPages = "page.secured = 0 AND page.adminSecured = 0";
	return array(

		'language' => D\ok("id, name, short, alias"),

		'layout' => D\ok("id, file, locked, name"),

		// TODO: check
		'route' => D\ok("id, page_id, language_id, route_id, layout_id, photo_id, author_id, dir_id, type, url, localUrl, params, paramCounter, published, text, created, updated, expired, released, name, notation, title, keywords, description, robots, changefreq, priority, copyLayoutFromParent, cacheMode, copyCacheModeFromParent, copyLayoutToChildren, class, childrenLayout_id"),

		'static403_route' => $route,
		'static404_route' => $route,
		'static500_route' => $route,
		'static403_page' => $page,
		'static404_page' => $page,
		'static500_page' => $page,

		'tags_page' => D\ok("id | page_id | itemsPerPage | extendedMainRoute_id"),
		'tags_route' => $route,

		'menu_page' => D\ok("id | page_id | itemsPerPage | extendedMainRoute_id"),
		'menu_route' => $route,

		'sitemap_page' => D\ok("id | page_id | maxDepth | maxWidth | rootPage_id | extendedMainRoute_id"),
		'sitemap_route' => $route,

		'static_page' => D\filtered("EXISTS (SELECT * FROM page WHERE page.id = page_id AND ($unsecuredPages))", "id, page_id, extendedMainRoute_id"),
		'static_route' => D\filtered("EXISTS (SELECT * FROM page WHERE page.id = page_id AND ($unsecuredPages))", "id | page_id | route_id | extendedPage_id"),

		'redirect_page' => D\filtered("EXISTS (SELECT * FROM page WHERE page.id = page_id AND ($unsecuredPages))", "id, page_id, extendedMainRoute_id, redirect_id, redirectUrl"),
		'redirect_route' => D\filtered("EXISTS (SELECT * FROM page WHERE page.id = page_id AND ($unsecuredPages))", "id | page_id | route_id | extendedPage_id"),

		'search_route' => $route,
		'search_page' => D\ok("id | page_id | itemsPerPage | extendedMainRoute_id"),

		'rss_page' => $page,
		'rss_route' => $route,
		'rss_rss' => D\filtered("EXISTS (SELECT * FROM page WHERE page.id = page_id AND ($unsecuredPages))", "id | page_id | route_id | class | items | extendedPage_id "),

		'page' => D\filtered($unsecuredPages, "id | parent_id | previous_id | route_id | language_id | dir_id | position | created             | updated             | published | navigationTitle                  | navigationShow | special | class                                     | secured | adminSecured | positionString"),

		'element' => D\filtered("EXISTS (SELECT * FROM page WHERE page.id = page_id AND ($unsecuredPages))", "id | layout_id | page_id | route_id | language_id | name | nameRaw | mode | langMode | class "),

		// We don't want to export real users
		'users' => D\customData(array(
			array(
				'id' => 0,
				'page_id' => null,
				'route_id' => null,
				'email' => 'example@svobodni.cz',
				'name' => 'example user',
				'notation' => 'example notation',
				'password' => '<no password>',
				'enableByKey' => '',
				'published' => 1,
				'salt' => 'blabla',
				'socialType' => null,
				'socialData' => null,
				'created' => '2014-01-01 00:00:00',
				'class' => '',
				'resetKey' => null,
				'extendedPage_id' => null
			),
			array(
				'page_id' => null, // I can use a different order
				'id' => 1,
				'route_id' => null,
				'email' => 'example2@svobodni.cz',
				'name' => 'example user 2',
				'notation' => 'example notation 2',
				'password' => '<no password>',
				'enableByKey' => '',
				'published' => 1,
				'salt' => 'blabla',
				'socialType' => null,
				'socialData' => null,
				'created' => '2014-02-02 00:00:00',
				'class' => '',
				'resetKey' => null,
				'extendedPage_id' => null
			),
		)),

		'tags' => D\csv(__DIR__ . "/tags.csv", "id | page_id | route_id | name  | extendedPage_id"),

		'userlist_page' => D\ok(" id | page_id | itemsPerPage | extendedMainRoute_id"),
		'userlist_route' => D\ok(" id | page_id | route_id | extendedPage_id "),

		// some data might be needed, but it is not critical; On the other hand, we should be careful about sensitive data
		'file' => D\skipData(),
		'file_read' => D\skipData(),
		'file_write' => D\skipData(),
		'users_roles' => D\skipData(),
		'user_page' => D\skipData(),
		'user_route' => D\skipData(),
		'permission' => D\skipData(),
		'userlist_page_roles' => D\skipData(),
		'default_user' => D\skipData(),
		'directory' => D\skipData(),
		'dir_read' => D\skipData(),
		'dir_write' => D\skipData(),
		'thumbnail_element' => D\skipData(),
		'text_element' => D\skipData(), // Likely useful, but potential privacy issues must be resolved first
		'image_element' => D\skipData(), // Likely useful, but potential privacy issues must be resolved first
		'routes_tags' => D\skipData(),
		'role' => D\skipData(),
		'profile_page' => D\skipData(),
		'profile_route' => D\skipData(),
		'cms_rssentity_pageentity' => D\skipData(),
		'route_translation' => D\skipData(),
		'login_page' => D\skipData(),
		'login_provider' => D\skipData(),
		'login_route' => D\skipData(),
		'registration_page' => D\skipData(),
		'registration_route' => D\skipData(),
		'page_admin_permission' => D\skipData(),
		'page_admin_permission_roles' => D\skipData(),
		'page_permission' => D\skipData(),
		'page_permission_roles' => D\skipData(),
		'page_translation' => D\skipData(),


		// likely unneeded:
		'cms_pageentity_roleentity' => D\skipData(),
		'version' => D\skipData(),
		'user_page_roles' => D\skipData(),

		// likely contains something sensitive
		'login' => D\skipData(),
		'log' => D\skipData(), // Log data would be a garbage with some potentially sensitive information

	);
});
