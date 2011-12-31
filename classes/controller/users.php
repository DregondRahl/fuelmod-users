<?php

namespace Users;

use Response;
use Session;
use Sentry;

class Controller_Users extends \Controller_App
{

	/**
	 * The index action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		$users = Model_User::find(function($query) {
			return $query->join('users_profiles', 'LEFT')
				->on('users.id', '=', 'users_profiles.user_id');
		});

		if ( ! $users)
		{
			throw new \HttpNotFoundException();
		}

		$this->template->title = 'Users - Index';
		$this->template->content = \View::forge('users/index')->set('users', $users);
	}

	/**
	 * The view action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_view($id = null)
	{
		$id or Response::redirect('users');

		/* Consider making model method insted */
		$user = Model_User::find_one(function($query) use ($id) {
			return $query->select('users.*', 'users_profiles.*', 'users_follows.follow_id')
				->join('users_profiles', 'LEFT')
				->on('users.id', '=', 'users_profiles.user_id')
				->join('users_follows', 'LEFT')
				->on('users.id', '=', 'users_follows.follow_id')
				->on('users_follows.user_id', '=', \DB::expr(Sentry::user()->get('id')))
				->where('users.id', $id)
				->limit(1);
		});

		if (!$user)
		{
			throw new \HttpNotFoundException();
		}

		$this->template->title = $user->username;
		$this->template->content = \View::forge('users/view')->set('user', $user);
	}

	/**
	 * The edit action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_edit()
	{
		Sentry::check() or Response::redirect('/');

		if ( ! $profile = Model_Profile::find_one_by_user_id(Sentry::user()->get('id')))
		{
			throw new \HttpNotFoundException();
		}

		if ($profile->birthdate > 0)
		{
			$profile->dob_day   = date('j', $profile->birthdate);
			$profile->dob_month = date('n', $profile->birthdate);
			$profile->dob_year  = date('Y', $profile->birthdate);
		}

		$form = \Fieldset::forge('profile')
			->add_model('Users\\Model_Profile', null, 'set_form_profile')
			->populate($profile, true);

		if ($form->validation()->run())
		{
			/* contact */
			$profile->contact_email = $form->validated('contact_email');
			$profile->contact_gtalk = $form->validated('contact_gtalk');
			$profile->contact_skype = $form->validated('contact_skype');
			$profile->contact_msn   = $form->validated('contact_msn');
			$profile->contact_yim   = $form->validated('contact_yim');

			/* social */
			$profile->social_facebook = $form->validated('social_facebook');
			$profile->social_twitter  = $form->validated('social_twitter');
			$profile->social_gplus    = $form->validated('social_gplus');

			/* profile */
			$profile->gender    = $form->validated('gender');
			$profile->location  = $form->validated('location');
			$profile->about     = $form->validated('about');
			$profile->birthdate = mktime(0, 0, 0, 
				$form->validated('dob_month'), 
				$form->validated('dob_day'), 
				$form->validated('dob_year')
			);

			unset($profile->dob_day);
			unset($profile->dob_month);
			unset($profile->dob_year);

			if ($profile->save())
			{
				Session::set_flash('success', 'Updated Profile.');
				Response::redirect('users/view/' . Sentry::user()->get('id'));
			}
			else
			{
				Session::set_flash('error', 'Nothing to update.');
			}
		}

		$this->template->title = 'User - Edit';
		$this->template->content = \View::forge('users/edit')->set('form', $form, false);
	}

	/**
	 * The options action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_options()
	{
		Sentry::check() or Response::redirect('/');

		if ( ! $profile = Model_Profile::find_one_by_user_id(Sentry::user()->get('id')))
		{
			throw new \HttpNotFoundException();
		}

		$form = \Fieldset::forge('profile')
			->add_model('Users\\Model_Profile', null, 'set_form_options')
			->populate($profile, true);

		if ($form->validation()->run())
		{
			/* options */
			$profile->show_birthdate     = $form->validated('show_birthdate');
			$profile->show_alerts        = $form->validated('show_alerts');
			$profile->show_signatures    = $form->validated('show_signatures');
			$profile->show_notifications = $form->validated('show_notifications');
			$profile->allow_wallposts    = $form->validated('allow_wallposts');

			if ($profile->save())
			{
				Session::set_flash('success', 'Updated user options.');
				Response::redirect('users/view/' . Sentry::user()->get('id'));
			}
			else
			{
				Session::set_flash('error', 'Nothing to update.');
			}
		}

		$this->template->title = 'User - Options';
		$this->template->content = \View::forge('users/options')->set('form', $form, false);
	}

	/**
	 * The avatar action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_avatar($delete = null)
	{
		Sentry::check() or Response::redirect('/');

		$form = \Fieldset::forge('avatar');
		$form->set_config('form_attributes', array('enctype' => 'multipart/form-data'));
		$form->add('files', 'Files', array('type' => 'file'));
		$form->add('submit', '', array('type' => 'submit', 'value' => 'Upload'));

		$upload_path = rtrim(DOCROOT, '/') . DS . 'assets' . DS . 'img' . DS . 'avatar';
		$avatar_path = $upload_path . DS . Sentry::user()->get('id');

		if ($delete)
		{
			try
			{
				\File::delete($avatar_path . '.jpg');
				\File::delete($avatar_path . '-tn.jpg');

				Session::set_flash('success', 'Avatar removed.');
			}
			catch (\InvalidPathException $e)
			{
				Session::set_flash('error', 'No avatar found to delete.');
			}

			Response::redirect('users/avatar');
		}

		\Upload::process(array(
			'path'          => $upload_path,
			'create_path'   => true,
			'new_name'      => time(),
			'max_size'      => \Num::bytes('2MB'),
			'ext_whitelist' => array('jpg', 'png'),
			'overwrite'     => true,
		));

		if (\Upload::is_valid())
		{
			\Upload::save();

			$arr = \Upload::get_files();

			if (isset($arr[0]))
			{
				try
				{
					\File::delete($avatar_path . '.jpg');
					\File::delete($avatar_path . '-tn.jpg');
				}
				catch (\InvalidPathException $e){}

				try
				{
					\Image::load($upload_path . DS . $arr[0]['saved_as'])
						->crop_resize(125, 125)
						->save($avatar_path . '.jpg');

					\Image::load($upload_path . DS . $arr[0]['saved_as'])
						->crop_resize(50, 50)
						->save($avatar_path . '-tn.jpg');

					if (\File::delete($upload_path . DS . $arr[0]['saved_as']))
					{
						Session::set_flash('success', 'Avatar updated.');
					}
				}
				catch (\InvalidPathException $e)
				{
					Session::set_flash('error', 'Could not update avatar.');
				}
			}
		}

		foreach (\Upload::get_errors() as $file)
		{
			Session::set_flash('error', $file['errors'][0]['message']);
		}

		$this->template->title = 'User - Avatar';
		$this->template->content = \View::forge('users/avatar')->set('form', $form, false);
	}

	/**
	 * The follow action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_follow($id = null)
	{
		Sentry::check() or $id or Response::redirect('/');

		if (Sentry::user()->get('id') == $id)
		{
			Session::set_flash('error', 'You cannot follow yourself.');
			Response::redirect('users/view/' . $id);
		}

		if ( ! $user = Model_User::find_by_pk($id))
		{
			throw new \HttpNotFoundException();
		}

		$follow = Model_Follow::find_one_by(array(
			'user_id'   => Sentry::user()->get('id'),
			'follow_id' => $user->id,
		));

		if ( ! $follow)
		{
			$follow_me = new Model_Follow(array(
				'user_id'   => Sentry::user()->get('id'),
				'follow_id' => $user->id,
			));

			if ($follow_me->save())
			{
				Session::set_flash('success', 'You are now following ' . $user->username);
			}
			else
			{
				Session::set_flash('error', 'Unable to follow ' . $user->username);
			}
		}
		else
		{
			Session::set_flash('error', 'You are already following ' . $user->username);
		}

		Response::redirect('users/view/' . $user->id);
	}

	/**
	 * The unfollow action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_unfollow($id = null)
	{
		Sentry::check() or $id or Response::redirect('/');

		if (Sentry::user()->get('id') == $id)
		{
			Session::set_flash('error', 'You are stuck with yourself.');
			Response::redirect('users/view/' . $id);
		}

		if ( ! $user = Model_User::find_by_pk($id))
		{
			throw new \HttpNotFoundException();
		}

		//consider join insted?
		$follow = Model_Follow::find_one_by(array(
			'user_id'   => Sentry::user()->get('id'),
			'follow_id' => $user->id,
		));

		if ($follow)
		{
			if ($follow->delete())
			{
				Session::set_flash('success', 'You are no longer following ' . $user->username);
			}
			else
			{
				Session::set_flash('error', 'Unable to unfollow ' . $user->username);
			}
		}
		else
		{
			Session::set_flash('error', 'You are not following ' . $user->username);
		}

		Response::redirect('users/view/' . $user->id);
	}

	/**
	 * The followers action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_followers()
	{
		$followers = Model_Follow::find(function($query) {
			return $query->join('users', 'INNER')
				->on('users.id', '=', 'users_follows.user_id')
				->where('users_follows.follow_id', \DB::expr(Sentry::user()->get('id')));
		});

		if ( ! $followers)
		{
			throw new \HttpNotFoundException();
		}

		$this->template->title = 'Users - Followers';
		$this->template->content = \View::forge('users/followers')->set('followers', $followers);
	}

	/**
	 * The following action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_following()
	{
		$following = Model_Follow::find(function($query) {
			return $query->join('users', 'INNER')
				->on('users.id', '=', 'users_follows.follow_id')
				->where('users_follows.user_id', \DB::expr(Sentry::user()->get('id')));
		});

		if ( ! $following)
		{
			throw new \HttpNotFoundException(); // consider message insted
		}

		$this->template->title = 'Users - Following';
		$this->template->content = \View::forge('users/following')->set('following', $following);
	}

}