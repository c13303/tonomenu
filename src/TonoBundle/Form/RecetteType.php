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


class RecetteType extends AbstractType {

         /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        for($i=0;$i<sizeof(Recette::LOURD_NAMES);$i++)
        {
            $choicearray[Recette::LOURD_NAMES[$i]]=$i;
        }
        
        $builder->add('nom')
                ->add('Ingredients', EntityType::class, array(
                    'class' => 'TonoBundle:Ingredient',
                    'choice_label' => 'nom',
                    'multiple' => true,
                ))
                ->add('isvegan', CheckboxType::class, array(
                    'label' => 'Recette vegan ?',
                    'required' => false,
                ))
                ->add('indications',TextareaType::class,array(
                    'label'=>'Recette',
                    'required'=>false
                ))
                 ->add('lourd',ChoiceType::class,array(
                     'label' => 'lourdeur',
                    'choices' => $choicearray
                 ))
                ->add('disabled', CheckboxType::class, array(
                    'label' => 'Exclure des menus ? (coché = désactivé)',
                    'required' => false,
                ))
               
                ->add('save', SubmitType::class, array('label' => 'Add Recette'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'TonoBundle\Entity\Recette'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'tonobundle_recette';
    }

}
