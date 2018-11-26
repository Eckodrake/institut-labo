<?php

namespace App\Form;

use App\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'type', ChoiceType::class, [
				'choices' => array_flip( Promotion::PROMO )
			] )
			->add( 'name' )
			->add( 'description' )
			->add( 'price' )
			->add( 'datetime' );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class'         => Promotion::class,
			'translation_domain' => 'forms'
		] );
	}
}
