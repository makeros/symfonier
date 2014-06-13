<?php

namespace Symfonier\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\EqualTo;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);

        // $builder->add('user', new UserType());
        // $builder->add(
        //     'terms',
        //     'checkbox',
        //     array('property_path' => 'termsAccepted')
        // );
        
        $builder->add('Register', 'submit');

    

  //       $builder->add('firstName', 'text', array('required' => true, 'constraints' => new NotBlank()));
  //       $builder->add('lastName', 'text', array('required' => true, 'constraints' => new NotBlank()));
  //       $builder->add('housing', 'document', array(
  //       	'required' => true,
  //   		'class' => 'SymfonierApiBundle:Housing',
		//     'property' => 'name'
		// ));
        


        // not mapped, in RegistrationFormHandler onSuccess convert to user.Address
		// $builder->add('_streetName', 'text', array('mapped' => false, 'constraints' => new NotBlank()));
		// $builder->add('_houseNumber', 'text', array('mapped' => false, 'constraints' => new NotBlank()));
  //       $builder->add('_postalCode', 'text', array('mapped' => false, 'constraints' => new NotBlank()));
  //       $builder->add('_flatNumber', 'text', array('mapped' => false));
  //       $builder->add('_building', 'hidden', array('mapped' => false));
  //       $builder->add('_housing', 'hidden', array('mapped' => false));
    }

    public function getName()
    {
        return 'symfonier_user_registration';
    }
}