<?php

namespace ContentBundle\Controller;

use ContentBundle\Entity\Content;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContentBundle\Entity\ContentRelated;
use ContentBundle\Form\ContentRelatedType;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * ContentRelated controller.
 *
 * @Rest\NamePrefix("content_related_")
 */
class ContentRelatedController extends Controller
{


    /**
     * Array of content related elements.
     *
     * @Rest\Get("/.{_format}", defaults = { "_format" = "json" })
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Returns contents",
     *
     *  output={
     *   "class"="ContentBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     * @return View
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $contents = $em->getRepository('ContentBundle:Content')->findAll();

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:content:index.html.twig")
            ->setTemplateVar('contents')
            ->setSerializationContext(SerializationContext::create()->setGroups($groups))
            ->setData($contents);

        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Creates a new ContentRelated entity.
     *
     * @param Request $request
     * @param Content $content
     * @return View
     * @internal param String $_format
     *
     * @Rest\Post(
     *     "new/.{_format}",
     *     defaults = { "_format" = "json" }
     * )
     * @Rest\View(serializerGroups={"user","mod","admin"})
     *
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Creates new related content",
     *
     *  input={
     *     "class"="ContentBundle\Form\ContentRelatedType",
     *     "name" = ""
     *  },
     *
     *  output={
     *   "class"="ContentBundle\Entity\ContentRelated",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function newAction(Request $request, Content $content)
    {

        $contentRelated = new ContentRelated();
        $contentRelated->setContent($content);

        $form = $this->createForm('ContentBundle\Form\ContentRelatedType', $contentRelated);
        $form->submit($request->request->all());

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups($groups));

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRelated);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:contentRelated:show.html.twig")
                ->setTemplateVar('contents')
                ->setData($contentRelated);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                ->setTemplateVar('error')
                ->setData($this->get('validator')->validate($contentRelated))
                ->setTemplateData(['message' => $form->getErrors(true)])
                ->setTemplate('ContentBundle:Default:new.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Finds and displays a ContentRelated entity.
     *
     * @Rest\Get(
     *     "/{contentRelated}.{_format}",
     *     requirements={"contentRelated" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Content $content
     * @param ContentRelated $contentRelated
     * @return View
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Returns content related data",
     *
     *  output={
     *   "class"="EntriesBundle\Entity\Content",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function showAction(Content $content, ContentRelated $contentRelated)
    {
        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        $view = View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:contentRelated:show.html.twig")
            ->setTemplateVar('contentRelated')
            ->setSerializationContext(SerializationContext::create()->setGroups($groups))
            ->setData([$contentRelated]);

        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Edit an existing ContentRelated entity.
     *
     * @Rest\Patch(
     *     "/{contentRelated}.{_format}",
     *     requirements={"contentRelated" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Content $content
     * @param ContentRelated $contentRelated
     * @return View
     * @throws \NotFoundHttpException*
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Updates content data",
     *
     *  input={
     *     "class"="ContentBundle\Form\ContentRelatedType",
     *     "name" = ""
     *  },
     *
     *  output={
     *   "class"="EntriesBundle\Entity\ContentRelated",
     *   "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"},
     *   "groups"={"user","mod","admin"}
     *  }
     * )
     */
    public function editAction(Request $request, Content $content, ContentRelated $contentRelated)
    {
        $editForm = $this->createForm('ContentBundle\Form\ContentRelatedType', $contentRelated);
        $editForm->submit($request->request->all(), false);

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        $view = View::create()
            ->setSerializationContext(SerializationContext::create()->setGroups($groups));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentRelated);
            $em->flush();

            $view
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:contentRelated:show.html.twig")
                ->setTemplateVar('contents')
                ->setData($contentRelated);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                ->setTemplateVar('error')
                ->setData($this->get('validator')->validate($contentRelated))
                ->setTemplateData(['message' => $editForm->getErrors(true)])
                ->setTemplate('ContentBundle:content:edit.html.twig');
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Deletes a ContentRelated entity.
     *
     * @Rest\Delete(
     *     "/{contentRelated}.{_format}",
     *     requirements={"contentRelated" = "\d+"},
     *     defaults = { "_format" = "json" }
     * )
     *
     * @Rest\View(serializerGroups={"user","mod","admin"})
     * @param Request $request
     * @param Content $content
     * @param ContentRelated $contentRelated
     *
     * @return View
     * @internal param Content $content
     *
     * @ApiDoc(
     *  resource="/api/content/",
     *  description="Deletes ContentRelated"
     * )
     */
    public function deleteAction(Request $request, Content $content, ContentRelated $contentRelated)
    {
        if (!$contentRelated) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();
        $form->submit($request->request->get($form->getName()));

        $groups = $this->get('user_bundle.user')->getGrantedAPIGroups();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contentRelated);
            $em->flush();

            return View::create()
                ->setStatusCode(Codes::HTTP_OK)
                ->setTemplate("ContentBundle:content:index.html.twig")
                ->setTemplateVar('contents')
                ->setSerializationContext(SerializationContext::create()->setGroups($groups))
                ->setData(['status' => true]);
        }

        return View::create()
            ->setStatusCode(Codes::HTTP_OK)
            ->setTemplate("ContentBundle:content:index.html.twig")
            ->setTemplateVar('contents')
            ->setSerializationContext(SerializationContext::create()->setGroups($groups))
            ->setData($this->get('validator')->validate($contentRelated));
    }

    /**
     * Creates a form to delete a ContentRelated entity.
     *
     * @param ContentRelated $contentRelated The ContentRelated entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContentRelated $contentRelated)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('content_related_delete', array('id' => $contentRelated->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
