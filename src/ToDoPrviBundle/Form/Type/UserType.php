<?php

namespace ToDoPrviBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('username', 'text', [
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'username'
            ]
        ])
                ->add('email', 'email', [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'email'
                    ]
                ])
                ->add('password', 'repeated', array(
                    'first_name'  => 'password',
                    'second_name' => 'confirm',
                    'type'        => 'password',
        ));

//        <input class="form-control" id="inputPassword"
//        placeholder="Password" type="password">

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ToDoPrviBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}