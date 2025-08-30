Feature: Integers utility functions
  As a developer
  I want to check if numbers are within ranges
  So that I can validate input boundaries

  Scenario: Number within range
    When I check if 5 is in range 1 to 10
    Then the result should be "true"

  Scenario: Number at lower boundary
    When I check if 1 is in range 1 to 10
    Then the result should be "true"

  Scenario: Number at upper boundary
    When I check if 10 is in range 1 to 10
    Then the result should be "true"

  Scenario: Number below range
    When I check if 0 is in range 1 to 10
    Then the result should be "false"

  Scenario: Number above range
    When I check if 11 is in range 1 to 10
    Then the result should be "false"
