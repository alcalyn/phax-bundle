<?php

namespace Phax\CoreBundle\Model;

use Symfony\Component\HttpFoundation\JsonResponse;
use Phax\CoreBundle\Model\PhaxReaction;

/**
 * Transform a PhaxReaction in symfony2 Response.
 */
class PhaxResponse extends JsonResponse
{
    public function __construct(PhaxReaction $phaxReaction)
    {
        parent::__construct($phaxReaction->jsonSerialize());
    }
}
