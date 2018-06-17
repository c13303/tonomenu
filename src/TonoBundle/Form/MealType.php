<?php

namespace TonoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // ← this line
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use TonoBundle\Entity\Meal;


class MealType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder->add('slot',ChoiceType::class,array(
            'label'=>'menu'
        ))
                ->add('recette' , EntityType::class,array(
                    'class'=>'TonoBundle:Recette',
                    'choice_label' => 'nom',
                    'multiple'=>false,
                     
                ))
                ->add('save', SubmitType::class, array('label' => 'Add Menu'))
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TonoBundle\Entity\Meal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tonobundle_meal';
    }


}
