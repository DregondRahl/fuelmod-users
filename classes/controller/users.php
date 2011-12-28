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
				return $query->join('users_profiles')->on('users.id', '=', 'users_profiles.user_id');
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

		$user = Model_User::find_one(function($query) use ($id) {
				return $query->join('users_profiles', 'LEFT')
						->on('users.id', '=', 'users_profiles.user_id')
						->where('users.id', $id)
						->limit(1);
		});
		
		if ( ! $user)
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
		if ( ! $user = Model_Profile::find_one_by_user_id(Sentry::user()->get('id')))
		{
			throw new \HttpNotFoundException();
		}

		$form = \Fieldset::forge('profile')->add_model('Users\\Model_Profile')->populate($user, true);

		if ($form->validation()->run())
		{
			$user->gender		= $form->validated('gender');
			$user->location		= $form->validated('location');
			$user->signature	= $form->validated('signature');
			$user->about		= $form->validated('about');

			if ($user->save())
			{
				Session::set_flash('success', 'Updated Profile.');
				Response::redirect('users/view/'. Sentry::user()->get('id'));
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
		if ( ! $user = Model_Options::find_one_by_user_id(Sentry::user()->get('id')))
		{
			throw new \HttpNotFoundException();
		}

		$form = \Fieldset::forge('options')->add_model('Users\\Model_Options')->populate($user, true);

		if ($form->validation()->run())
		{
			$user->birthdate		= $form->validated('birthdate');
			$user->signature		= $form->validated('signature');
			$user->alerts			= $form->validated('alerts');
			$user->wallpost			= $form->validated('wallpost');
			$user->notifications	= $form->validated('notifications');

			if ($user->save())
			{
				Session::set_flash('success', 'Updated Options');
				Response::redirect('users/view/'. Sentry::user()->get('id'));
			}
			else
			{
				Session::set_flash('error', 'Could not update Options');
			}
		}

		$this->template->title = 'User - Options';
		$this->template->content = \View::forge('users/edit')->set('form', $form, false);
	}

	/**
	 * The avatar action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_avatar($delete = false)
	{
		if ( ! $user = Model_User::find_by_pk(Sentry::user()->get('id')))
		{
			throw new \HttpNotFoundException();
		}

		$form = \Fieldset::forge('avatar');
		$form->set_config('form_attributes', array('enctype' => 'multipart/form-data'));
		$form->add('files', 'Files', array('type' => 'file'));
		$form->add('submit', '', array('type' => 'submit', 'value' => 'Upload'));

		$avatar_path = 'assets' . DS . 'img' . DS . 'avatar';
		$upload_path = rtrim(DOCROOT, '/') . DS . $avatar_path;

		if ($delete AND $user->avatar == 1)
		{
			if (\File::delete(DOCROOT . $avatar_path . DS . Sentry::user()->get('id') . '.jpg') AND \File::delete(DOCROOT . $avatar_path . DS . Sentry::user()->get('id') . '-tn.jpg'))
			{
				$user->avatar = 0;

				if ($user->save())
				{
					Session::set_flash('success', 'Avatar Removed');
				}
			}
			else
			{
				Session::set_flash('error', 'Could not remove Avatar');
			}
		}
		else
		{
			\Upload::process(array(
					'path'			=> $upload_path,
					'create_path'	=> true,
					'new_name'		=> Sentry::user()->get('id'),
					'max_size'		=> \Num::bytes('2MB'),
					'ext_whitelist' => array('jpg', 'png', 'gif'),
					'overwrite'		=> true,
			));

			if (\Upload::is_valid())
			{
				\Upload::save();

				$arr = \Upload::get_files();

				if (isset($arr[0]))
				{
					if ($user->avatar == 1)
					{
						\File::delete($upload_path . DS . Sentry::user()->get('id') . '.jpg');
						\File::delete($upload_path . DS . Sentry::user()->get('id') . '-tn.jpg');
					}

					\Image::load($upload_path . DS . $arr[0]['saved_as'])->crop_resize(125, 125)->save_pa('', '', 'jpg');
					\Image::load($upload_path . DS . $arr[0]['saved_as'])->crop_resize(50, 50)->save_pa('', '-tn', 'jpg');

					if ($user->avatar == 0)
					{
						$user->avatar = 1;
						$user->save();
					}

					Session::set_flash('success', 'Avatar Added to Profile');
				}
			}

			foreach (\Upload::get_errors() as $file)
			{
				Session::set_flash('error', $file['errors'][0]['message']);
			}
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
	public function action_follow($id = false)
	{
		$id or Response::redirect('users');

		//Use DB methods and consider batch mode like xenforo
		$follow_check	= Model_Follow::find()->where('user_id', Sentry::user()->get('id'))->where('follow_id', $id);
		$follow_user	= Model_Profile::find_by_user_id($id);

		if (!$follow_user or $follow_check->count() > 0)
		{
			throw new \HttpNotFoundException();
		}

		$user = Model_Profile::find_by_user_id($this->user->id);
		$follow = Model_Follow::forge();

		$follow->user_id = $this->user->id;
		$follow->follow_id = $id;

		if ($follow->save())
		{
			$follow_user->followers = $follow_user->followers + 1;
			$follow_user->save();

			$user->follows = $user->follows + 1;
			$user->save();
		}
	}

	/**
	 * The unfollow action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_unfollow($id = false)
	{
		$id or Response::redirect('users');

		//Use DB methods
		$follow_check	= Model_Follow::find()->where('user_id', Sentry::user()->get('id'))->where('follow_id', $id);
		$follow_user	= Model_Profile::find_by_user_id($id);

		if (!$follow_user or $follow_check->count() == 0)
		{
			throw new \HttpNotFoundException();
		}

		$user = Model_Profile::find_by_user_id($this->user->id);
		$follow = $follow_check->get_one();

		if ($follow->delete())
		{
			$follow_user->followers = $follow_user->followers - 1;
			$follow_user->save();

			$user->follows = $user->follows - 1;
			$user->save();
		}
	}

	/**
	 * The following action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_followers($id = false)
	{
		$id or Response::redirect('users');

		if (!$followers = Model_Follow::find()->where('follow_id', $id)->related('followers')->get())
		{
			throw new \HttpNotFoundException(); //consider change to page.
		}

		$user = Model_User::find()->select('id', 'username')->where('id', $id)->get_one();

		$this->template->title = $user->username . ' - Followers';
		$this->template->content = \View::forge('users/followers')->set('followers', $followers)->set('title', $user->username . ' - Followers');
	}

	/**
	 * The followers action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_follows($id = false)
	{
		$id or Response::redirect('users');

		if (!$follows = Model_Follow::find()->where('user_id', $id)->related('follows')->get())
		{
			throw new \HttpNotFoundException(); //consider change to page.
		}

		$user = Model_User::find()->select('id', 'username')->where('id', $id)->get_one();

		$this->template->title = $user->username . ' - Follows';
		$this->template->content = \View::forge('users/follows')->set('follows', $follows)->set('title', $user->username . ' - Follows');
	}

}

/* End of file users.php */