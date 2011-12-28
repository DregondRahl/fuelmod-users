<?php

namespace Users;

use Response;
use Session;
use Sentry;

class Controller_Auth extends \Controller_App
{

	/**
	 * The login action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_login()
	{
		Sentry::check() and Response::redirect('/');

		$user = Model_User::forge();

		$form = \Fieldset::forge('user')->add_model('Users\\Model_User', null, 'set_form_login')->populate($user, true);

		if ($form->validation()->run())
		{
			try
			{
				if (Sentry::login($form->validated('email'), $form->validated('password'), $form->validated('remember')))
				{
					Session::set_flash('success', 'Welcome back <span style="font-weight:bold;">' . Sentry::user()->get('username') . '</span>.');
					Response::redirect('/');
				}
				else
				{
					Session::set_flash('error', 'Unable to login, please check your password');
				}
			}
			catch (\SentryAuthException $e)
			{
				Session::set_flash('error', $e->getMessage());
			}
		}

		$this->template->title = 'User - Login';
		$this->template->content = \View::forge('auth/login')->set('form', $form, false);
	}

	/**
	 * The logout action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_logout()
	{
		Sentry::logout();
		Session::set_flash('success', 'You have successfully logged out');
		Response::redirect('/');
	}

	/**
	 * The register action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_register()
	{
		Sentry::check() and Response::redirect('/');

		$user = Model_User::forge();

		$form = \Fieldset::forge('user')->add_model('Users\\Model_User', null, 'set_form_register')->populate($user, true);

		if ($form->validation()->run())
		{
			try
			{
				if (\Config::get('sentry.activation'))
				{
					\Package::load('email');

					$hash_link = Sentry::user()->register(array(
							'username'	=> $form->validated('username'),
							'password'	=> $form->validated('password'),
							'email'		=> $form->validated('email'),
					));

					$view = \View::forge('email/activation')
							->set('user', $form->validated('username'))
							->set('hash_link', $hash_link, false);

					$email = \Email::forge()
							->from('raziel8@gmail.com', 'Dregond Rahl')
							->to($form->validated('email'), $form->validated('username'))
							->subject('Activation Email')
							->html_body($view);

					try
					{
						if ($email->send())
						{
							Session::set_flash('success', 'Registration was successful, an activation email has been sent');
							Response::redirect('users/auth/login');
						}
					}
					catch (\EmailValidationFailedException $e)
					{
						Session::set_flash('error', $e->getMessage());
					}
					catch (\EmailSendingFailedException $e)
					{
						Session::set_flash('error', $e->getMessage());
					}
				}
				else
				{
					$user_id = Sentry::user()->create(array(
							'username'	=> $form->validated('username'),
							'password'	=> $form->validated('password'),
							'email'		=> $form->validated('email'),
					));

					try
					{
						if (Sentry::user($user_id)->add_to_group('users'))
						{
							Session::set_flash('success', 'Registration was successful you may now logon');
							Response::redirect('users/auth/login');
						}
						else
						{
							Session::set_flash('success', 'Registration failed.');
						}
					}
					catch (\SentryGroupException $e)
					{
						Session::set_flash('error', $e->getMessage());
					}
				}
			}
			catch (\SentryUserException $e)
			{
				Session::set_flash('error', $e->getMessage());
			}
		}

		$this->template->title = 'User - Register';
		$this->template->content = \View::forge('auth/register')->set('form', $form, false);
	}

	/**
	 * The activate action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_activate($hash_login = null, $hash_code = null)
	{
		Sentry::check() and Response::redirect('/');

		if ($hash_login and $hash_code)
		{
			try
			{
				$activate = Sentry::activate_user($hash_login, $hash_code);

				if ($activate)
				{
					try
					{
						if ($activate->add_to_group('users'))
						{
							Session::set_flash('success', 'Activation was successful, you may now login');
							Response::redirect('users/auth/login');
						}
					}
					catch (\SentryGroupException $e)
					{
						Session::set_flash('error', $e->getMessage());
						Response::redirect('/');
					}
				}
				else
				{
					Session::set_flash('error', 'Activation failed');
					Response::redirect('/');
				}
			}
			catch (\SentryAuthException $e)
			{
				Session::set_flash('error', $e->getMessage());
				Response::redirect('/');
			}
		}
		else
		{
			Session::set_flash('error', 'Missing activation code or email.');
			Response::redirect('/');
		}
	}

	/**
	 * The forgot password action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_forgot_password()
	{
		Sentry::check() and Response::redirect('/');

		$user = Model_User::forge();

		$form = \Fieldset::forge('user')->add_model('Users\\Model_User', null, 'set_form_forgot_password')->populate($user, true);

		if ($form->validation()->run())
		{
			try
			{
				$reset = Sentry::reset_password($form->validated('email'), $form->validated('password'));

				if ($reset)
				{
					$view = \View::forge('email/forgot')
							->set('email', $reset->get('email'))
							->set('hash_link', $reset->get('password_reset_link'), false);

					$email = \Email::forge()
							->from('raziel8@gmail.com', 'Dregond Rahl')
							->to($reset->get('email'), $reset->get('username'))
							->subject('Reset Password Email')
							->html_body($view);

					try
					{
						if ($email->send())
						{
							Session::set_flash('success', 'Password reset email was dispatched successfully, please check your inbox and junk.');
							Response::redirect('/');
						}
					}
					catch (\EmailValidationFailedException $e)
					{
						Session::set_flash('error', $e->getMessage());
					}
					catch (\EmailSendingFailedException $e)
					{
						Session::set_flash('error', $e->getMessage());
					}
				}
				else
				{
					Session::set_flash('error', 'Password reset failed');
				}
			}
			catch (\SentryAuthException $e)
			{
				Session::set_flash('error', $e->getMessage());
			}
		}

		$this->template->title = 'User - Forgot Password';
		$this->template->content = \View::forge('auth/login')->set('form', $form, false);
	}

	/**
	 * The reset password action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_reset_password($hash_login = null, $hash_code = null)
	{
		Sentry::check() and Response::redirect('/');

		if ($hash_login and $hash_code)
		{
			try
			{
				if (Sentry::reset_password_confirm($hash_login, $hash_code))
				{
					Session::set_flash('success', 'Password reset was successful you may now login with your new password');
					Response::redirect('users/auth/login');
				}
				else
				{
					Session::set_flash('error', 'Password reset failed');
					Response::redirect('/');
				}
			}
			catch (\SentryAuthException $e)
			{
				Session::set_flash('error', $e->getMessage());
				Response::redirect('/');
			}
		}
	}

	/**
	 * The change password action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_change_password()
	{
		! Sentry::check() and Response::redirect('/');

		$user = Model_User::forge();

		$form = \Fieldset::forge('user')->add_model('Users\\Model_User', null, 'set_form_change_password')->populate($user, true);

		if ($form->validation()->run())
		{
			try
			{
				if (Sentry::user()->change_password($form->validated('password'), $form->validated('old_password')))
				{
					Session::set_flash('success', 'Password has been changed succesfully');
					Response::redirect('/');
				}
				else
				{
					Session::set_flash('error', 'Unable to update your password');
				}
			}
			catch (\SentryUserException $e)
			{
				Session::set_flash('error', $e->getMessage());
			}
		}

		$this->template->title = 'User - Change Password';
		$this->template->content = \View::forge('auth/change_password')->set('form', $form, false);
	}

}