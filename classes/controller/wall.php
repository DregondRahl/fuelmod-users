<?php

namespace Users;

class Controller_Wall extends \Controller_App
{

	/**
	 * The index action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		if ( ! $wall = Model_Wall::find()->where('user_id', $this->user_id)->get())
		{
			throw new \HttpNotFoundException();
		}

		$this->template->title = 'Users - Wall - Index';
		$this->template->content = \View::forge('wall/index')->set('wall', $wall)->set('title', 'Users - Wall - Index');
	}

	/**
	 * The view action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_view($id = false)
	{
		$id or \Response::redirect('wall');

		if ( ! $post = Model_Wall::find()->where('id', $id)->where('state', 1)->get_one())
		{
			throw new \HttpNotFoundException();
		}

		$this->template->title = 'User - Wall - Post';
		$this->template->content = \View::forge('wall/view')->set('post', $post)->set('title', 'User - Wall - Post');
	}

	/**
	 * The add action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_add($id = false)
	{
		$id or \Response::redirect('wall');

		// Check user access
		$user = \DB::select('user_id')->from('users_options')->where('user_id', $id);

		if ($id != $this->user->id)
		{
			$user->where('wallpost', 1);
		}

		if ( ! $user->as_object()->execute()->current())
		{
			throw new \HttpNotFoundException();
		}

		$wall = Model_Wall::forge();

		$form = \Fieldset::forge('wall')->add_model('Users\\Model_Wall')->populate($wall, true);

		if ($form->validation()->run())
		{
			$wall->user_id		= $this->user->id;
			$wall->username		= $this->user->name;
			$wall->parent_id	= $user->user_id;
			$wall->status		= $form->validated('status'); //owner only can have locked post
			$wall->content		= $form->validated('content');

			if ($wall->save())
			{
				//if own wall update wall_id in profile.
				\Session::set_flash('success', 'Created Wall post');
			}
			\Session::set_flash('error', 'Could not create Wall post');
		}

		$this->template->title = 'User - Wall - Add';
		$this->template->content = \View::forge('wall/add-edit')->set('form', $form, false)->set('title', 'User - Wall - Add');
	}

	/**
	 * The edit action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_edit($id = false)
	{
		$id or \Response::redirect('wall');

		if ( ! $wall = Model_Wall::find()->where('id', $id)->where('user_id', $this->user->id)->get_one()) //check group so admin can edit
		{
			throw new \HttpNotFoundException();
		}

		$form = \Fieldset::forge('wall')->add_model('Users\\Model_Wall')->populate($wall, true);

		if ($form->validation()->run())
		{
			$wall->status	= $form->validated('status'); //owner only can have locked post
			$wall->content	= $form->validated('content');

			if ($wall->save())
			{
				\Session::set_flash('success', 'Updated Wall post');
			}
			\Session::set_flash('error', 'Could not update Wall post');
		}

		$this->template->title = 'User - Wall - Edit';
		$this->template->content = \View::forge('wall/add-edit')->set('form', $form, false)->set('title', 'User - Wall - Edit');
	}

	/**
	 * The delete action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_delete($id = false)
	{
		$id or \Response::redirect('wall');

		if ( ! $wall = Model_Wall::find()->where('id', $id)->where('user_id', $this->user->id)->get_one()) //check permisson user_id/group
		{
			throw new \HttpNotFoundException();
		}

		if (\Input::method() == 'POST')
		{
			$wall->state = ($wall->state == 1) ? 0 : 1;

			if ($wall->save())
			{
				\Session::set_flash('success', 'Deleted Wall post');
			}
			\Session::set_flash('error', 'Could not delete Wall post');
		}

		$this->template->title = 'User - Wall - Delete';
		$this->template->content = \View::forge('wall/delete')->set('title', 'User - Wall - Delete');
	}

	#consider adding action for action_comment() insted of add_wall in \Comments
}

/* End of file wall.php */