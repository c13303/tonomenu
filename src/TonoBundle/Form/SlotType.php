<?php

namespace TonoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use TonoBundle\Entity\Recette;

class SlotType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        for($i=0;$i<sizeof(Recette::LOURD_NAMES);$i++)
        {
            $choicearray[Recette::LOURD_NAMES[$i]]=$i;
        }
        $builder->add('position')->add('nom') ->add('lourdeur',ChoiceType::class,array(
                     'label' => 'lourdeur',
                    'choices' => $choicearray
                 ))
                ->add('save', SubmitType::class, array('label' => 'Add Slot'))
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TonoBundle\Entity\Slot'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tonobundle_slot';
    }


}
