<?php

namespace Memsource\Connector\Api\v1\Content\Response;

interface ContentInterface
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
     * @return string
     */
    public function getBody();

    /**
     * @return int
     */
    public function getModified();

    /**
     * @return bool
     */
    public function getFolder();
}
