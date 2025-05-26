<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
					'label' => 'Email',
					'attr' => [
						'class' => 'form-control',
						'placeholder' => 'exemple@domaine.com'
					],
					'constraints' => [
						new NotBlank([
							'message' => 'Veuillez entrer une adresse email',
						]),
						new Email([
							'message' => 'L\'adresse email n\'est pas valide',
                    ])
                    ]
                ])
            ->add('firstName', TextType::class, [
					'label' => 'Prénom',
					'required' => false,
					'attr' => [
						'class' => 'form-control',
						'placeholder' => 'Votre prénom'
					]
                ])
            ->add('lastName', TextType::class, [
					'label' => 'Nom',
					'required' => false,
					'attr' => [
						'class' => 'form-control',
						'placeholder' => 'Votre nom'
					]
                ])
    

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
