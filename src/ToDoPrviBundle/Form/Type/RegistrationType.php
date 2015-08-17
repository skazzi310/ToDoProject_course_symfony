<?php
/**
 * Created by PhpStorm.
 * User: skazzi
 * Date: 17.8.15.
 * Time: 13.31
 */

namespace ToDoPrviBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UserType());
        $builder->add(
            'terms',
            'checkbox', [
            'property_path' => 'termsAccepted',
        ]
        );
        $builder->add('Register', 'submit');
    }

    public function getName()
    {
        return 'registration';
    }
}