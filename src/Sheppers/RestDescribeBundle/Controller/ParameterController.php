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
 *   name="DescribeParameter"
 * )
 */
class ParameterController extends Controller
{
    /**
     * @Route("/resources/{resource}/operations/{operation}/parameters", name="RestDescribe_Parameters_getParameters")
     * @Method({"GET"})
     * @Describe\Operation(
     *   name="getParameters",
     *   scope="collection",
     *   note="Gets the parameters for an operation"
     * )
     */
    public function getParametersAction(Request $request, $resource, $operation)
    {
        $operation = $this->getDoctrine()->getManager('describe')
            ->getRepository('SheppersRestDescribeBundle:Operation')
            ->findOneByNameForResource($resource, $operation)
        ;

        $data = array(
            'href' => $request->getUri(),
            'links' => array(
                array(
                    'rel' => 'resource/DescribeOperation',
                    'href' => $this->generateUrl('RestDescribe_Operations_getOperation', array(
                        'resource' => $resource,
                        'operation' => $operation->getName()
                    ), true)
                )
            )
        );

        /** @var $parameter Parameter */
        foreach ($operation->getParameters() as $parameter) {
            $data['items'][$parameter->getName()] = array(
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
