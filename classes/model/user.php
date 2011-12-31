<?php

namespace Users;

class Model_User extends \Model_Crud
{

	protected static $_table_name = 'users';

	protected static function pre_find($query)
	{
		if ( ! \Sentry::user()->is_admin())
		{
			$query->where(static::$_table_name . '.activated', 1);
		}

		return $query;
	}

	public static function set_form_register($form, $instance = null)
	{
		$form->add('username', 'Username', array('type' => 'text'), array(
			array('required'), array('min_length', 6), array('max_length', 30), array('valid_string', array('alpha', 'numeric', 'spaces'))
		));

		$form->add('password', 'Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50)
		));

		$form->add('repassword', 'Confirm Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50), array('match_field', 'password')
		));

		$form->add('email', 'E-mail', array('type' => 'text'), array(
			array('required'), array('min_length', 6), array('max_length', 120), array('valid_email')
		));

		$form->add('submit', null, array('value' => 'Register', 'type' => 'submit', 'class' => 'btn primary'));
	}

	public static function set_form_login($form, $instance = null)
	{
		$form->add('email', 'E-Mail', array('type' => 'text'), array(
			array('required'), array('max_length', 120), array('valid_email')
		));

		$form->add('password', 'Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50)
		));

		$form->add('remember', 'Remember', array(
			'type'  => 'checkbox',
			'value' => 1,
		));

		$form->add('submit', null, array('value' => 'Login', 'type' => 'submit', 'class' => 'btn primary'));
	}

	public static function set_form_change_password($form, $instance = null)
	{
		$form->add('old_password', 'Old Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50)
		));

		$form->add('password', 'New Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50)
		));

		$form->add('confirm_password', 'Confirm Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50), array('match_field', 'password')
		));

		$form->add('submit', null, array('value' => 'Save', 'type' => 'submit', 'class' => 'btn primary'));
	}

	public static function set_form_forgot_password($form, $instance = null)
	{
		$form->add('email', 'E-Mail', array('type' => 'text'), array(
			array('required'), array('max_length', 120), array('valid_email')
		));

		$form->add('password', 'New Password', array('type' => 'password'), array(
			array('required'), array('min_length', 6), array('max_length', 50)
		));

		$form->add('submit', null, array('value' => 'Reset', 'type' => 'submit', 'class' => 'btn primary'));
	}

}