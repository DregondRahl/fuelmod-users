<?php

namespace Users;

class Model_Profile extends \Model_Crud
{

	protected static $_table_name = 'users_profiles';

	protected static $_primary_key = 'user_id';

	protected static $_defaults = array(
			'location' => 'miniTheatre',
	);

	public static function set_form_fields($form, $instance = null)
	{
		$form->add('gender', 'Gender', array(
				'type' => 'select',
				'options' => array('private' => 'Private', 'male' => 'Male', 'female' => 'Female')
		));

		$form->add('location', 'Location', array('type' => 'text'), array(
				array('min_length', 5), array('max_length', 50)
		));

		$form->add('about', 'About Me', array('type' => 'textarea'), array(
				array('min_length', 10), array('max_length', 500)
		));

		$form->add('signature', 'Signature', array('type' => 'textarea'), array(
				array('min_length', 10), array('max_length', 255)
		));

		$form->add('submit', null, array('value' => 'Save', 'type' => 'submit', 'class' => 'btn primary'));
	}

}

/* End of file profile.php */