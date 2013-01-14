<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Avro\BlogBundle\Tests\Doctrine;

use Avro\BlogBundle\Doctrine\PostManager;

/**
 * Test the Post Manager class
 */
class PostManagerTest extends \PHPUnit_Framework_TestCase
{
    const POST_CLASS = 'Avro\BlogBundle\Tests\Doctrine\DummyPost';

    protected $postManager;
    protected $repository;

    /**
     * Setup test class
     */
    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::POST_CLASS))
            ->will($this->returnValue($this->repository));
        $objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::POST_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::POST_CLASS));


        $this->postManager = new PostManager($objectManager, 'Avro\BlogBundle\Tests\Doctrine\DummyPost');
    }

    public function testFindAll()
    {
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(array()));

        $this->postManager->findAll();
    }
}
