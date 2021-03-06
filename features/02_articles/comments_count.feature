@articles
Feature: Setting comments count in articles
  In order store comments count in articles
  As a HTTP Client
  I want to be able to push JSON content with data and set it as comments count

  Scenario: Submitting request payload in json format
    When I run the "swp:organization:update" command with options:
      | name          | Organization1 |
      | --env         | test          |
      | --secretToken | secret_token  |
    Then the command output should be "Organization Organization1 (code: 123456, secret token: secret_token) has been updated and is enabled!"
    When I add "Content-Type" header equal to "application/json"
    And I add "x-publisher-signature" header equal to "sha1=0dcd1953d72dda47f4a4acedfd638a3c58def7bc"
    Then I send a "PATCH" request to "/api/v1/content/articles" with body:
     """
      {
        "article_comments":{
          "url": "http://localhost/news/test-news-article",
          "commentsCount": "31"
        }
      }
     """
    Then the response status code should be 200
    And the JSON nodes should contain:
      | commentsCount | 31 |
    And the JSON node "createdAt" should be equal to "updatedAt" node


  Scenario: Submitting request with redirecting article url
    When I run the "swp:organization:update" command with options:
      | name          | Organization1 |
      | --env         | test          |
      | --secretToken | secret_token  |
    Then the command output should be "Organization Organization1 (code: 123456, secret token: secret_token) has been updated and is enabled!"
    When I add "Content-Type" header equal to "application/json"
    And I add "x-publisher-signature" header equal to "sha1=0dcd1953d72dda47f4a4acedfd638a3c58def7bc"
    Then I send a "PATCH" request to "/api/v1/content/articles" with body:
     """
      {
        "article_comments":{
          "url": "http://localhost/r/test-news-article",
          "commentsCount": "35"
        }
      }
     """
    Then the response status code should be 200
    And the JSON nodes should contain:
      | commentsCount | 35 |
    And the JSON node "createdAt" should be equal to "updatedAt" node
