<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Form/UfrType.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 05/07/2020 08:09

namespace App\Form;

use App\Entity\Personnel;
use App\Entity\Site;
use App\Entity\Ufr;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UfrType
 * @package App\Form
 */
class UfrType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, ['label' => 'label.libelle'])
            ->add('responsable', EntityType::class, [
                'class'         => Personnel::class,
                'label'         => 'label.responsable_site',
                'choice_label'  => 'displayPr',
                'expanded'      => false,
                'multiple'      => false,
                'query_builder' => static function(PersonnelRepository $personnelRepository) {
                    return $personnelRepository->findAllOrder();
                },
                'attr'          => ['data-live-search' => 'true', 'data-provide' => 'selectpicker'],
            ])
            ->add('sitePrincipal', EntityType::class, [
                'class'        => Site::class,
                'label'        => 'label.site_principal',
                'choice_label' => 'libelle',
                'expanded'     => true,
                'multiple'     => false
            ])
            ->add('sites', EntityType::class, [
                'class'        => Site::class,
                'label'        => 'label.sites',
                'choice_label' => 'libelle',
                'expanded'     => true,
                'multiple'     => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => Ufr::class,
            'translation_domain' => 'form'

        ]);
    }
}
