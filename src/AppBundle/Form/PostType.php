<?php

namespace AppBundle\Form;

use AppBundle\Form\Transformer\UploadedFileDataTransformer;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostType extends AbstractType
{
    /**
     * @var UploadedFileDataTransformer
     */
    private $fileTransformer;

    /**
     * PostType constructor.
     * @param UploadedFileDataTransformer $fileTransformer
     */
    public function __construct(UploadedFileDataTransformer $fileTransformer)
    {
        $this->fileTransformer = $fileTransformer;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class,
            ["label" => "Titre"])

            ->add('text', CKEditorType::class,
                ["label" => "Texte", "attr" => ["row" => 12]])

            ->add('theme', EntityType::class,
                ["class" => "AppBundle\Entity\Theme", "attr" => ["placeholder" => "Choisissez un thème"], "choice_label" => "name", "disabled" => true])

            ->add("imageFileName", FileType::class,["label"=>"Image", "required" => false])

            ->add("submit", SubmitType::class,
                ["label" => "Valider", "attr" => ["class" => "btn btn-primary"]]);


        $builder->addViewTransformer($this->fileTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
