<?php

namespace Sheppers\RestDescribeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Sheppers\RestDescribeBundle\Entity\Property;

/**
 * @Describe\Resource(
 *   name="DescribeProperty"
 * )
 */
class PropertyController extends Controller
{
    /**
     * @Route("/resources/{resource}/properties", name="RestDescribe_Properties_getProperties")
     * @Method({"GET"})
     * @Describe\Operation(
     *   name="getProperties",
     *   scope="collection",
     *   description="Gets the properties for a resource"
     * )
     */
    public function getPropertiesAction(Request $request, $resource)
    {
        $resource = $this->getDoctrine()->getManager('describe')
            ->getRepository('SheppersRestDescribeBundle:Resource')
            ->findOneByName($resource)
        ;

        $data = array(
            'href' => $request->getUri(),
            'links' => array(
                array(
                    'rel' => 'resource/DescribeResource',
                    'href' => $this->generateUrl('RestDescribe_Resources_getResource', array(
                        'resource' => $resource->getName()
                    ), true)
                )
            )
        );

        /** @var $property Property */
        foreach ($resource->getProperties() as $property) {
            $data['items'][$property->getName()] = array(
                'description' => $property->getDescription(),
                'type' => $property->getType(),
                'format' => $property->getFormat(),
                'default' => $property->getDefault(),
                'sample' => $property->getSample()
            );
        }

        return $data;
    }
}
