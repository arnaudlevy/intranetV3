<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Form/ApcApprentissageCritiqueType.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 13/10/2020 09:00

namespace App\Form;

use App\Entity\ApcApprentissageCritique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApcApprentissageCritiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('niveau');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApcApprentissageCritique::class,
        ]);
    }
}
