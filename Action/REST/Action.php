<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action\REST;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\Serializer;
use Elao\Bundle\AdminBundle\Action\Action as BaseAction;

/**
 * Abstract REST Action
 */
abstract class Action extends BaseAction
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML  = 'xml';

    /**
     * Serializer
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var array
     */
    protected static $formats;

    /**
     * Set serializer
     *
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get format from Request
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getFormat(Request $request)
    {
        $supportedContentTypes  = $this->getSupportedContentTypes();
        $acceptableContentTypes = $request->getAcceptableContentTypes();

        foreach ($acceptableContentTypes as $contentType) {
            if (isset($supportedContentTypes[$contentType])) {
                return $supportedContentTypes[$contentType];
            }
        }

        throw new \Exception(sprintf(
            'No acceptable content type found "%s" in supported  content types "%s".',
            implode(', ', $acceptableContentTypes),
            implode(', ', $supportedContentTypes)
        ));
    }

    /**
     * Create response
     *
     * @param array $data
     * @param integer $status
     * @param string $format
     *
     * @return Response
     */
    protected function createResponse($data = '', $status = 200, $format = 'json')
    {
        $headers = ['Content-Type' => $this->getContentType($format)];

        return new Response(
            $data !== null ? $this->serializer->serialize($data, $format) : $data,
            $status,
            $headers
        );
    }

    /**
     * Get formats
     *
     * @return array
     */
    protected function getSupportedFormats()
    {
        if (null === static::$formats) {
            static::initializeFormats();
        }

        return static::$formats;
    }

    /**
     * Get supported content types
     *
     * @return array
     */
    protected function getSupportedContentTypes()
    {
        $supportedContentTypes = [];
        $supportedFormats      = $this->getSupportedFormats();

        foreach ($supportedFormats as $format => $contentTypes) {
            foreach ($contentTypes as $contentType) {
                if (!isset($supportedContentTypes[$contentType])) {
                    $supportedContentTypes[$contentType] = $format;
                }
            }
        }

        return $supportedContentTypes;
    }

    /**
     * Get content type for the given format
     *
     * @param string $format
     *
     * @return string
     */
    protected function getContentType($format)
    {
        $formats = $this->getSupportedFormats();

        if (!isset($formats[$format])) {
            throw new \Exception(sprintf('Unsopported format "%s".', $format));
        }

        return $formats[$format][0];
    }

    /**
     * Initializes HTTP request formats.
     */
    protected static function initializeFormats()
    {
        static::$formats = array(
            'json' => ['application/json', 'application/x-json', '*/*'],
            'xml'  => ['text/xml', 'application/xml', 'application/x-xml', '*/*'],
        );
    }
}
