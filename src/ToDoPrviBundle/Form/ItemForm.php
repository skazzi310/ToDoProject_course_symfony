<?php

namespace ToDoPrviBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ToDoPrviBundle\Entity\Item;

/**
 * Created by PhpStorm.
 * User: vezbe
 * Date: 8/13/2015
 * Time: 2:27 PM
 */
class ItemForm extends  AbstractType
{
    /**
 * {@inheritdoc}
 */
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add("Name", "text")
        ->add("CreationTime", "date")
        ->add("DueDate", "date")
        ->add("Location", "text")
        ->add("submit", "submit", ["label" => "Ubaci"]);
}

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setDefaults([
           "data_class" => Item::class
       ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "ItemForm";
        // TODO: Implement getName() method.
    }
}