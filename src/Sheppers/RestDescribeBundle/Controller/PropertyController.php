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
            ),
            'items' => $this->entitiesToArray($resource->getProperties())
        );

        return $data;
    }

    /**
     * Converts an array of Property entity objects to an array.
     *
     * @param $entities
     *
     * @return array|null
     */
    protected function entitiesToArray($entities)
    {
        $properties = null;

        foreach ($entities as $entity) {
            $name = $entity->getName();
            $properties[$name] = array(
                'type' => $entity->getType(),
                'description' => $entity->getDescription(),
                'default' => $entity->getDefault(),
                'format' => $entity->getFormat(),
                'sample' => $entity->getSample()
            );

            if (count($entity->getProperties())) {
                $properties[$name]['properties'] = $this->entitiesToArray($entity->getProperties());
            }
        }

        return $properties;
    }
}
