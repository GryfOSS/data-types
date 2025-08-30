Feature: ByteReader functionality
  As a developer
  I want to read binary data sequentially
  So that I can process byte streams efficiently

  Scenario: Reading bytes sequentially
    Given I have a ByteReader for binary data "Hello"
    When I read the next 2 bytes
    Then the result should be "He"
    And the reader position should be 2

  Scenario: Reading remaining bytes
    Given I have a ByteReader for binary data "Hello"
    When I read the next 2 bytes
    And I read the next 3 bytes
    Then the result should be "llo"
    And the reader position should be 5

  Scenario: Resetting reader position
    Given I have a ByteReader for binary data "Hello"
    When I read the next 3 bytes
    And I reset the reader
    Then the reader position should be 0
