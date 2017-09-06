<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Author;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthorFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ObjectManager $manager
     */
    public  function  load(ObjectManager $manager)
    {

        $encoderFactory = $this->container->get("security.encoder_factory");
        $encoder = $encoderFactory->getEncoder(new Author());
        $password = $encoder->encodePassword("123", null);

        $author = new Author();
        $author->setName("Anastasie")
                ->setFirstName("Pelland")
                ->setEmail("AnastasiePelland@dayrep.com")
                ->setPassword($password);

        $this->addReference("auteur_1", $author);

        $manager->persist($author);

        $author = new Author();
        $author->setName("Richard")
            ->setFirstName("Owens")
            ->setEmail("RichardJOwens@rhyta.com")
            ->setPassword($password);

        $this->addReference("auteur_2", $author);

        $manager->persist($author);

        $author = new Author();
        $author->setName("Graham")
            ->setFirstName("France ")
            ->setEmail("FranceWBecnel@dayrep.com")
            ->setPassword($password);

        $this->addReference("auteur_3", $author);

        $manager->persist($author);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function  getOrder()
    {
        return 2;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}