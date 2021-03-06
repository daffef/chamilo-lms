<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\UserBundle\Form;

use Sylius\Bundle\AttributeBundle\Form\EventListener\BuildAttributeFormChoicesListener;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Attribute\Model\AttributeTypes;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Attribute type.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class AttributeType extends AbstractResourceType
{
    /**
     * Subject name.
     *
     * @var string
     */
    protected $subjectName;

    /**
     * Constructor.
     *
     * @param string $dataClass
     * @param array $validationGroups
     * @param string $subjectName
     */
    public function __construct(
        $dataClass,
        array $validationGroups,
        $subjectName
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->subjectName = $subjectName;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'sylius.form.attribute.name',
                )
            )
            ->add(
                'presentation',
                'text',
                array(
                    'label' => 'sylius.form.attribute.presentation',
                )
            )
            ->add(
                'type',
                'choice',
                array(
                    'choices' => AttributeTypes::getChoices(),
                )
            )
            ->addEventSubscriber(
                new BuildAttributeFormChoicesListener(
                    $builder->getFormFactory()
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf('%s_extra_field', $this->subjectName);
    }
}
