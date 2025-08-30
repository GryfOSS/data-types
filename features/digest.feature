Feature: Hash Digest functionality
  As a developer
  I want to generate cryptographic hashes
  So that I can verify data integrity

  Scenario: Generating SHA256 hash
    Given I have a Binary buffer with data "Hello"
    When I create a hash digest
    And I calculate SHA256 hash
    Then the result should be a hash value
