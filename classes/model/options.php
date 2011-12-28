<?php

namespace Users;

class Model_Options extends \Model_Crud
{

	//consider merge with profile, but seprate might be useful for cache
	protected static $_table_name = 'users_options';
	
	protected static $_defaults = array(
			'birthdate'		=> 1,
			'signature'		=> 1,
			'alerts'		=> 1,
			'wallpost'		=> 1,
			'notifications'	=> 1,
	);

	public static function set_form_fields($form, $instance = null)
	{
		$form->add('birthdate', 'Birthdate', array(
				'type'	=> 'checkbox',
				'value' => 1,
		));

		$form->add('signature', 'Signature', array(
				'type'	=> 'checkbox',
				'value' => 1,
		));

		$form->add('alerts', 'Alerts', array(
				'type'	=> 'checkbox',
				'value' => 1,
		));

		$form->add('wallpost', 'Wallpost', array(
				'type'	=> 'checkbox',
				'value' => 1,
		));

		$form->add('notifications', 'Notifications', array(
				'type'	=> 'checkbox',
				'value' => 1,
		));

		$form->add('submit', null, array('value' => 'Save', 'type' => 'submit', 'class' => 'btn primary'));
	}

}

/* End of file options.php */