<?php

namespace MMC\Profile\Component\Manipulator\Exception;

use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class InvalidUserProfileClassName extends PreconditionFailedHttpException implements ManipulatorExceptionInterface
{
}
