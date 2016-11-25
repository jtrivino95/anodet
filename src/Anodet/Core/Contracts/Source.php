<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/22/16
 * Time: 6:33 PM
 */

namespace Anodet\Core\Contracts;


interface Source
{

    /**
     * @param integer $sourceId
     */
    public function setSourceId($sourceId);

    /**
     * @param string $sourceType
     */
    public function setSourceType($sourceType);

    /**
     * @return string
     */
    public function getSourceId();

    /**
     * @return string
     */
    public function getSourceType();
}