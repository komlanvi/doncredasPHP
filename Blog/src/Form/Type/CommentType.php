<?php

namespace Blog\Form\type;

use Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\AbstractType;

/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 03/04/15
 * Time: 1.53
 */

class CommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea');
    }

    public function getName()
    {
        return 'comment';
    }

}