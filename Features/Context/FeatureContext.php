<?php

namespace Avro\BlogBundle\Features\Context;

use Avro\UserBundle\Features\Context\FeatureContext as UserContext;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;

//
// Require 3rd-party libraries here:
//
   require_once 'PHPUnit/Autoload.php';
   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends UserContext
{
    /**
     * @Given /^create these blogs:$/
     */
    public function createTheseVideos(TableNode $table)
    {
        $hash = $table->getHash();
        $result = array();
        foreach ($hash as $row) {
            $array = array(
                new Step\Given("I am on \"/blog/post/new\""),
                new Step\When("I fill in \"Title\" with \"".$row['title']."\""),
                new Step\When("I fill in \"Content\" with \"".$row['content']."\""),
                new Step\When("I fill in \"Featured\" with \"".$row['isFeatured']."\""), 
                new Step\When("I press \"Create Post\"")     
            );
            $result = array_merge($result, $array);
        }
        return $result;
    }

    /**
     * @Then /^there should be no post with id = (\d+)$/
     */
    public function thereShouldBeNoPostWithId($id)
    {
        $postManager = $this->getContainer()->get("avro_blog.post_manager");
        $post = $postManager->findPostBy(array('id' => $id));
        assertEmpty($post); 
    }
}
