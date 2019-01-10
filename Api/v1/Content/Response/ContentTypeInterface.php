<?php

namespace Memsource\Connector\Api\v1\Content\Response;

interface ContentTypeInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function getFolder();
}
