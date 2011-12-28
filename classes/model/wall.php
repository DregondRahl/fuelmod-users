<?php

namespace Users;

class Model_Wall extends \Orm\Model
{
    
    protected static $_observers = array(
        'Orm\\Observer_CreatedAt'   => array('before_insert'),
        'Orm\\Observer_UpdatedAt'   => array('before_save'),
        'Orm\\Observer_Typing'      => array('before_save', 'after_load')
    );
    
    protected static $_properties = array(
        'id'                => array('type' => 'int'),
        'parent_id'         => array('type' => 'int'),
        'user_id'           => array('type' => 'int'),
        'username'          => array('type' => 'int'),
        'content'           => array('type' => 'text'),
        'state'             => array('type' => 'int', 'default' => 1),
        'status'            => array('type' => 'int', 'default' => 1),
        'likes_count'       => array('type' => 'int', 'default' => 0),
        'comments_count'    => array('type' => 'int', 'default' => 0),
        'cache'             => array('type' => 'text', 'data_type' => 'json', 'default' => array()),
        'created_at'        => array('type' => 'int'),
        'updated_at'        => array('type' => 'int')
    );
    
    public static function set_form_fields($form, $instance = null)
    {
        $form->add('content', 'Content', 
                array('type' => 'textarea'),
                array(array('required'), array('min_length', 3))
        );
        
        $form->add('status', 'Status', array(
                'type'      => 'select',
                'options'   => array('Closed', 'Open')
        ));
        
        $form->add('submit', null, array('value' => 'Save', 'type' => 'submit', 'class' => 'btn primary'));
    }
    
}

/* End of file wall.php */