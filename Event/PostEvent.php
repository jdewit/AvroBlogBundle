<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\BlogBundle\Event;

use Avro\BlogBundle\Model\PostInterface;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Post event class
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class PostEvent extends Event
{
    private $post;
    private $request;

    public function __construct(Request $request, PostInterface $post)
    {
        $this->request = $request;
        $this->post = $post;
    }

    /**
     * @return PostInterface
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}

