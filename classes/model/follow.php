<?php

namespace Users;

class Model_Follow extends \Orm\Model
{

	protected static $_table_name = 'users_follows';

	protected static $_observers = array(
			'Orm\\Observer_CreatedAt' => array('before_insert'),
	);

	protected static $_properties = array(
			'id'			=> array('type' => 'int'),
			'user_id'		=> array('type' => 'int'),
			'follow_id'		=> array('type' => 'int'),
			'created_at'	=> array('type' => 'int'),
	);

	protected static $_belongs_to = array(
			'follows' => array(
					'key_from'			=> 'follow_id',
					'model_to'			=> 'Users\Model_Profile',
					'key_to'			=> 'user_id',
					'cascade_save'		=> true,
					'cascade_delete'	=> false,
			),
			'followers' => array(
					'key_from'			=> 'user_id',
					'model_to'			=> 'Users\Model_Profile',
					'key_to'			=> 'user_id',
					'cascade_save'		=> true,
					'cascade_delete'	=> false,
			),
	);

}

/* End of file follow.php */