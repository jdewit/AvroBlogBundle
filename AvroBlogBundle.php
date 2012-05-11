<?php

namespace Avro\BlogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AvroBlogBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
