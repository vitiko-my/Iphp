<?php
/**
 * Created by Vitiko
 * Date: 10.07.12
 * Time: 16:43
 */
namespace Iphp\CoreBundle\Controller;

use Irr\ProjectBundle\Form\InlineEdit\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class InlineEditController extends Controller
{


    function formAction(Request $request)
    {

        $entityClassPath = $request->query->get('entityClassPath');
        $entityId = $request->query->get('entityId');

        //if (! $entityClassPath ||  !$entityId )


        $entity = $this->getDoctrine()->getRepository($entityClassPath)->findOneById($entityId);
        $form = $this->createForm(new ProjectType(), $entity);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);


            $this->getDoctrine()->getManager()->persist($entity);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('IphpCoreBundle:InlineEdit:form-finishedit.html.twig', array(
                            /*'form' => $form->createView(),
                            'entityClassPath' => $entityClassPath,
                            'entityId' => $entityId*/
                        ));
        }
        else
        {
            return $this->render('IphpCoreBundle:InlineEdit:form.html.twig', array(
                'form' => $form->createView(),
                'entityClassPath' => $entityClassPath,
                'entityId' => $entityId
            ));
        }
    }
}
