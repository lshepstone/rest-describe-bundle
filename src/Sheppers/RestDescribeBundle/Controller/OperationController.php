<?php

namespace Sheppers\RestDescribeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Sheppers\RestDescribeBundle\Entity\Parameter;

/**
 * @Describe\Resource(
 *   name="DescribeOperation"
 * )
 */
class OperationController extends Controller
{
    /**
     * @Route("/resources/{resource}/operations", name="RestDescribe_Operations_getOperations")
     * @Method({"GET"})
     * @Describe\Operation(
     *   name="getOperations",
     *   scope="collection",
     *   note="Gets operations for a resource"
     * )
     */
    public function getOperationsAction(Request $request, $resource)
    {
        $operations = $this->getDoctrine()->getManager('describe')
            ->getRepository('SheppersRestDescribeBundle:Operation')
            ->findForResource($resource)
        ;

        $data = array(
            'href' => $request->getUri(),
            'links' => array(
                array(
                    'rel' => 'resource/DescribeResource',
                    'href' => $this->generateUrl('RestDescribe_Resources_getResource', array(
                        'resource' => $resource
                    ), true)
                )
            )
        );

        foreach ($operations as $operation) {
            $data['operations'][$operation->getName()] = array(
                'href' => $this->generateUrl('RestDescribe_Operations_getOperation', array(
                    'resource' => $operation->getResource()->getName(),
                    'operation' => $operation->getName()
                ), true)
            );
        }

        return $data;
    }

    /**
     * @Route("/resources/{resource}/operations/{operation}", name="RestDescribe_Operations_getOperation")
     * @Method({"GET"})
     * @Describe\Operation(
     *   name="getOperation",
     *   scope="resource",
     *   note="Gets a specific resource operation",
     *   request=@Describe\Request(
     *     parameters={
     *       "resource"={
     *         "required"=true,
     *         "type"="string"
     *       },
     *       "operation"={
     *         "required"=true,
     *         "type"="string"
     *       }
     *     }
     *   )
     * )
     */
    public function getOperationAction(Request $request, $resource, $operation)
    {
        $operation = $this->getDoctrine()->getManager('describe')
            ->getRepository('SheppersRestDescribeBundle:Operation')
            ->findOneByNameForResource($resource, $operation)
        ;

        $data = array(
            'href' => $request->getUri(),
            'links' => array(
                array(
                    'rel' => 'resource/DescribeResource',
                    'href' => $this->generateUrl('RestDescribe_Resources_getResource', array(
                        'resource' => $resource
                    ), true)
                )
            ),
            'name' => $operation->getName(),
            'scope' => $operation->getScope(),
            'uri' => $operation->getUri()
        );

        /** @var $parameter Parameter */
        foreach ($operation->getParameters() as $parameter) {
            $data['parameters'][$parameter->getName()] = array(
                'description' => $parameter->getNote(),
                'required' => $parameter->isRequired(),
                'type' => $parameter->getType(),
                'location' => $parameter->getLocation(),
                'format' => $parameter->getFormat(),
                'default' => $parameter->getDefault(),
                'sample' => $parameter->getSample()
            );
        }

        return $data;
    }
}
