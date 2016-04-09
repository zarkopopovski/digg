<?php

namespace UserBundle\Controller;

use FOS\RestBundle\View\View;
use CoreBundle\Controller\BaseController;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\NamePrefix("user_")
 */
class DefaultController extends BaseController
{
    /**
     * @Rest\Get("/{user}", requirements={"user" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param User $user
     * @return View
     *
     * @ApiDoc(
     *  resource="/api/user/",
     *  description="Returns user information",
     *
     *  output={
     *   "class"="UserBundle\Entity\User",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"list"}
     *  }
     * )
     */
    public function getUserAction(User $user)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData($user);
    }

    /**
     * @Rest\Post("/new")
     * @Rest\View(serializerGroups={"list"})
     *
     * @ApiDoc(
     *  resource="/api/user/",
     *  description="Create a new user",
     *  input="Your\Namespace\Form\Type\YourType",
     *  output={
     *   "class"="UserBundle\Entity\User",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"list"}
     *  }
     * )
     */
    public function newUserAction(Request $request)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData(['lala']);
    }

    /**
     * @Rest\Put("/{entry}", requirements={"entry" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param User $user
     * @return View
     */
    public function updateEntryAction(User $user)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData($user);
    }

    /**
     * @Rest\Delete("/{entry}", requirements={"entry" = "\d+"})
     * @Rest\View(serializerGroups={"list"})
     * @param User $user
     * @return View
     */
    public function deleteUserAction(User $user)
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat('json')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')))
            ->setData([1]);
    }
}
