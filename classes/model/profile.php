<?php

namespace Users;

class Model_Profile extends \Model_Crud
{

	protected static $_table_name	= 'users_profiles';

	protected static $_primary_key	= 'user_id';

	public static function set_form_profile($form, $instance = null)
	{
		$days	= array_combine($days	= range(1, 31), $days);
		$months = array_combine($months = range(1, 12), $months);
		$years	= array_combine($years	= range(date('Y'), date('Y') - 80), $years);

		$form->add('gender', 'Gender', array(
			'type'		=> 'select',
			'options'	=> array('private' => 'Private', 'male' => 'Male', 'female' => 'Female')
		));

		$form->add('dob_day', 'Day', array(
			'type'		=> 'select',
			'options'	=> $days,
		));

		$form->add('dob_month', 'Month', array(
			'type'		=> 'select',
			'options'	=> $months,
		));

		$form->add('dob_year', 'Year', array(
			'type'		=> 'select',
			'options'	=> $years,
		));

		$form->add('location', 'Location', array('type' => 'text'), array(
			array('min_length', 5), array('max_length', 50)
		));

		$form->add('contact_email', 'E-mail', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 120), array('valid_email')
		));

		$form->add('contact_gtalk', 'Gtalk', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 120), array('valid_email')
		));

		$form->add('contact_skype', 'Skype', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 50), array('valid_string', array('alpha', 'numeric', 'spaces'))
		));

		$form->add('contact_msn', 'MSN', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 120), array('valid_email')
		));

		$form->add('contact_yim', 'Yahoo', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 120), array('valid_email')
		));

		$form->add('social_twitter', 'Twitter', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 50), array('valid_string', array('alpha', 'numeric'))
		));

		$form->add('social_facebook', 'Facebook', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 50), array('valid_string', array('alpha', 'numeric', 'dots'))
		));

		$form->add('social_gplus', 'G+', array('type' => 'text'), array(
			array('min_length', 6), array('max_length', 50), array('valid_string', array('alpha', 'numeric'))
		));

		$form->add('about', 'About Me', array('type' => 'textarea'), array(
			array('min_length', 10), array('max_length', 255)
		));

		$form->add('submit', null, array('value' => 'Save', 'type' => 'submit', 'class' => 'btn primary'));
	}

	public static function set_form_options($form, $instance = null)
	{
		$form->add('show_birthdate', 'Show Birthdate', array(
			'type'	=> 'checkbox',
			'value' => 1,
		));

		$form->add('show_alerts', 'Show Alerts', array(
			'type'	=> 'checkbox',
			'value' => 1,
		));

		$form->add('show_signatures', 'Show Signature', array(
			'type'	=> 'checkbox',
			'value' => 1,
		));

		$form->add('show_notifications', 'Show Notifications', array(
			'type'	=> 'checkbox',
			'value' => 1,
		));

		$form->add('allow_wallposts', 'Allow Wallposts', array(
			'type'	=> 'checkbox',
			'value' => 1,
		));

		$form->add('submit', null, array('value' => 'Save', 'type' => 'submit', 'class' => 'btn primary'));
	}
}