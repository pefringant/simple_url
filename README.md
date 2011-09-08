# CakePHP SimpleUrl

Tired of passing massive arrays to create links in CakePHP views or controllers ? This plugin provides a quicker way to generate url anywhere in your application.

Requires PHP 5.3+

## Installation

- Clone from github : in your plugin directory type

    `git clone git://github.com/kalt/simple_url.git`

- Or download the archive and extract to `{app}/plugins/simple_url`

## Setup

Simply add the following line above the declaration of the AppController class :

	<?php
	// {app}/app_controller.php
	App::import('Lib', 'SimpleUrl.Url');
	
	class AppController extends Controller {
	...

## Usage

You can now call from anywhere :

Url::plugin_controller_prefix_action($args);

## Examples

	// /posts
	echo $this->Html->url(array(
		'controller' => 'posts',
		'action' => 'index'
	));
	
	// Becomes
	echo Url::posts_index();
	// Or even better
	echo Url::posts();
	
---

	// /posts/download/1
	echo $this->Html->url(array(
		'controller' => 'posts',
		'action' => 'download',
		$post['Post']['id']
	));
	
	// Becomes
	echo Url::posts_download($post['Post']['id']);

---

	// /posts/view/1/number-one-slug
	echo $this->Html->url(array(
		'controller' => 'posts',
		'action' => 'view',
		'id' => $post['Post']['id'],
		'slug' => $post['Post']['slug']
	));
	
	// Becomes
	echo Url::posts_view(array(
		'id' => $post['Post']['id'],
		'slug' => $post['Post']['slug']
	));
	
---

	// /admin/posts/edit/1
	echo $this->Html->url(array(
		'controller' => 'posts',
		'action' => 'edit',
		'admin' => true,
		$post['Post']['id']
	));
	
	// Becomes
	echo Url::posts_admin_edit($post['Post']['id']);

---

	// /my_plugin/beers/drink/qty:1
	echo $this->Html->url(array(
		'plugin' => 'my_plugin',
		'controller' => 'beers',
		'action' => 'drink',
		'qty' => 1
	));
	
	// Becomes
	echo Url::my_plugin_beers_drink(array('qty' => 1));
	
---

	// /customer/my_plugin/beers/buy/1
	echo $this->Html->url(array(
		'plugin' => 'my_plugin',
		'controller' => 'beers',
		'action' => 'drink',
		'customer' => true,
		$beer['Beer']['id']
	));
	
	// Becomes
	echo Url::my_plugin_beers_customer_buy($beer['Beer']['id']);