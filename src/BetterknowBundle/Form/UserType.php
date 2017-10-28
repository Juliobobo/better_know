<?php
namespace BetterknowBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
            ->add('lastName')
            ->add('username')
            ->add('gender')
            ->add('age')
            ->add('email', EmailType::class)
            ->add('plainPassword');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BetterknowBundle\Entity\User',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getName()
    {
        return 'bk_user_registration';
    }
}