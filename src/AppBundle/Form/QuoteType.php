<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accepted')
            ->add('annealing'       , MoneyType::class)
            ->add('cementation'     , MoneyType::class)
            ->add('comissions'      , MoneyType::class)
            ->add('customer_id')
            ->add(
                'date',
                DateType::class,
                array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd'
                ))
            ->add('description')
            ->add('drill'           , MoneyType::class)
            ->add('forge'           , MoneyType::class)
            ->add('generic_concepts', CollectionType::class, array(
                'entry_type' => QuoteGenericConceptType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('grinding'        , MoneyType::class)
            ->add('hardening'       , MoneyType::class)
            ->add('id')
            ->add('lathe'           , MoneyType::class)
            ->add('milling'         , MoneyType::class)
            ->add('number_of_day')
            ->add('price'           , MoneyType::class)
            ->add('saw'             , MoneyType::class)
            ->add('shipping'        , MoneyType::class)
            ->add('threading'       , MoneyType::class)
            ->add('unit_price'      , MoneyType::class)
            ->add('weight'          , NumberType::class)
            ->add('zinc_plating'    , MoneyType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }
}