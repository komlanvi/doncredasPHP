<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 04/04/15
 * Time: 16.25
 */

namespace Blog\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('content', 'textarea');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article';
    }

}