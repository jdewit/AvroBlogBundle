Feature: Comment Feature
    
    Scenario: Create a new comment
        Given I am logged in as a user
        And I am on "/comment/new"
        And I select "mr" from "Title"
        And I fill in "Body" with "text string"
        And I fill in "Name" with "string"
        And I press "Create Comment"
        Then I should see "Comment created"

    Scenario: Create another new comment
        Given I am logged in as a user
        And I am on "/comment/new"
        And I select "mr" from "Title"
        And I fill in "Body" with "text string"
        And I fill in "Name" with "string"
        And I press "Create Comment"
        Then I should see "Comment created"

    Scenario: Edit a comment
        Given I am logged in as a user
        And I am on "/comment/2"
        And I press "Update Comment"
        Then I should see "Comment updated"

    Scenario: Delete a comment
        Given I am logged in as a user
        And I am on "/comment/2"
        And I press "Delete Comment"
        Then I should see "Comment Deleted"

