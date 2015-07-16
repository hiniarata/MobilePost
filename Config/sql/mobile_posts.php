<?php

/* SVN FILE: $Id$ */
/* MobilePosts schema generated on: 2013-03-22 21:03:08 : 1363957088 */

class MobilePostsSchema extends CakeSchema {

	public $name = 'MobilePosts';

	public $file = 'mobile_posts.php';

	public $connection = 'plugin';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $mobile_posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'key' => 'primary'),
  	'blog_content_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'blog_post_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'file' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'file_position' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

}
