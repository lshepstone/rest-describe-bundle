<?php

namespace Sheppers\RestDescribeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Describe\Resource(
 *   name="DescribeResource"
 * )
 */
class ResourceController extends Controller
{
    /**
     * @Route("/resources", name="RestDescribe_Resources_getResources")
     * @Method({"GET"})
     * @Describe\Operation(
     *   name="getResources",
     *   scope="collection",
     *   note="Gets all resources and their relationships"
     * )
     */
    public function getResourcesAction(Request $request)
    {
        $resources = $this->getDoctrine()->getManager('describe')
            ->getRepository('SheppersRestDescribeBundle:Resource')
            ->findAll()
        ;

        $data = array(
            'href' => $request->getUri()
        );

        foreach ($resources as $resource) {
            $data['items'][$resource->getName()] = array(
                'href' => $this->generateUrl('RestDescribe_Resources_getResource', array('resource' => $resource->getName()), true)
            );
        }

        return $data;
    }

    /**
     * @Route("/resources/{resource}", name="RestDescribe_Resources_getResource")
     * @Method({"GET"})
     * @Describe\Operation(
     *   name="getResource",
     *   scope="resource",
     *   note="Gets a specific resource",
     *   request=@Describe\Request(
     *     parameters={
     *       "resource"={
     *         "required"=true,
     *         "type"="string"
     *       }
     *     }
     *   )
     * )
     */
    public function getResourceAction($resource)
    {
        $resource = $this->getDoctrine()->getManager('describe')
            ->getRepository('SheppersRestDescribeBundle:Resource')
            ->findOneByName($resource)
        ;

        return array(
            'name' => $resource->getName(),
            'links' => array(
                array(
                    'rel' => 'collection/DescribeOperation',
                    'href' => $this->generateUrl('RestDescribe_Operations_getOperations', array('resource' => $resource->getName()), true)
                )
            )
        );
    }
}
