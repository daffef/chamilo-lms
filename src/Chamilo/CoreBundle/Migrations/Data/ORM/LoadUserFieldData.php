<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Migrations\Data\ORM;

use Chamilo\CoreBundle\Entity\UserField;
use Chamilo\CoreBundle\Entity\UserFieldOptions;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;

/**
 * Class LoadUserFieldData
 * @package Chamilo\CoreBundle\DataFixtures\ORM
 */
class LoadUserFieldData extends AbstractFixture implements
    ContainerAwareInterface,
    OrderedFixtureInterface,
    VersionedFixtureInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Saving user fields
        /*$userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('legal_accept');
        $userField->setFieldDisplayText('Legal');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('already_logged_in');
        $userField->setFieldDisplayText('Already logged in');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('update_type');
        $userField->setFieldDisplayText('Update script type');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(10);
        $userField->setFieldVariable('tags');
        $userField->setFieldDisplayText('tags');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('rssfeeds');
        $userField->setFieldDisplayText('RSS');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('legal_accept');
        $userField->setFieldDisplayText('Legal');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('dashboard');
        $userField->setFieldDisplayText('Dashboard');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('timezone');
        $userField->setFieldDisplayText('Timezone');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(4);
        $userField->setFieldVariable('mail_notify_invitation');
        $userField->setFieldDisplayText('MailNotifyInvitation');
        $userField->setFieldVisible(1);
        $userField->setFieldChangeable(1);
        $userField->setFieldDefaultValue(1);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(4);
        $userField->setFieldVariable('mail_notify_message');
        $userField->setFieldDisplayText('MailNotifyMessage');
        $userField->setFieldVisible(1);
        $userField->setFieldChangeable(1);
        $userField->setFieldDefaultValue(1);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(4);
        $userField->setFieldVariable('mail_notify_group_message');
        $userField->setDisplayText('MailNotifyGroupMessage');
        $userField->setFieldVisible(1);
        $userField->setFieldChangeable(1);
        $userField->setFieldDefaultValue(1);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('user_chat_status');
        $userField->setFieldDisplayText('User chat status');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        $userField = new UserField();
        $userField->setFieldType(1);
        $userField->setFieldVariable('google_calendar_url');
        $userField->setFieldDisplayText('Google Calendar URL');
        $userField->setFieldVisible(0);
        $userField->setFieldChangeable(0);
        $manager->persist($userField);

        // First
        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(8);
        $userFieldOption->setOptionValue(1);
        $userFieldOption->setOptionDisplayText('AtOnce');
        $userFieldOption->setOptionOrder(1);
        $manager->persist($userFieldOption);

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(8);
        $userFieldOption->setOptionValue(8);
        $userFieldOption->setOptionDisplayText('Daily');
        $userFieldOption->setOptionOrder(2);
        $manager->persist($userFieldOption);

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(8);
        $userFieldOption->setOptionValue(0);
        $userFieldOption->setOptionDisplayText('No');
        $userFieldOption->setOptionOrder(3);
        $manager->persist($userFieldOption);

        // Second
        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(9);
        $userFieldOption->setOptionValue(1);
        $userFieldOption->setOptionDisplayText('AtOnce');
        $userFieldOption->setOptionOrder(1);
        $manager->persist($userFieldOption);

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(9);
        $userFieldOption->setOptionValue(8);
        $userFieldOption->setOptionDisplayText('Daily');
        $userFieldOption->setOptionOrder(2);
        $manager->persist($userFieldOption);

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(9);
        $userFieldOption->setOptionValue(0);
        $userFieldOption->setOptionDisplayText('No');
        $userFieldOption->setOptionOrder(3);
        $manager->persist($userFieldOption);

        // Third

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(10);
        $userFieldOption->setOptionValue(1);
        $userFieldOption->setOptionDisplayText('AtOnce');
        $userFieldOption->setOptionOrder(1);
        $manager->persist($userFieldOption);

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(10);
        $userFieldOption->setOptionValue(8);
        $userFieldOption->setOptionDisplayText('Daily');
        $userFieldOption->setOptionOrder(2);
        $manager->persist($userFieldOption);

        $userFieldOption = new UserFieldOptions();
        $userFieldOption->setFieldId(10);
        $userFieldOption->setOptionValue(0);
        $userFieldOption->setOptionDisplayText('No');
        $userFieldOption->setOptionOrder(3);
        $manager->persist($userFieldOption);
        */
        $manager->flush();
    }

    /**
     * @return \FOS\UserBundle\Model\UserManagerInterface
     */
    public function getManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}
