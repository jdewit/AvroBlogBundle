Feature: Blog Feature
    Scenario: Create a post
        Given I am logged in as admin
        And I am on "/post/new"
        Then create these blogs:
            | title | content | isFeatured |
            | Ron Paul | Ron Paul owns the debate | 1 |
            | Ron Paul | Ron Paul owns the debate | 0 |
            | Keiser Report: Passing Fiat cash grenade | This week Max Keiser and co-host, Stacy Herbert, discuss passing the currency grenade and the Central Bank of Nigeria mentions trading oil with China in yuan. In the second half of the show Max talks to anthropologist, David Graeber, about his new book, Debt: The First 5000 Years. | 1 |

    Scenario: Edit a post
        Given I am logged in as admin
        And I am on "/post/edit/1"
        When I fill in "Title" with "Edited Title"
        When I fill in "Content" with "Edited Content"
        When I fill in "Featured" with "0" 
        And I press "Update Post"     
        Then I should see "Post Updated"


    Scenario: Delete a post
        Given I am logged in as admin
        And I am on "/post/delete/1"
        Then there should be no post with id = 1

    Scenario: Add a comment
        Given I am logged in as a user
        And I am on "/post/1"

