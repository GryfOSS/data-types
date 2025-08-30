Feature: BcNumber functionality
  As a developer
  I want to perform arbitrary precision arithmetic
  So that I can handle large numbers accurately

  Scenario: Creating a BcNumber with basic operations
    Given I have a BcNumber with value "100"
    When I set the scale to 2
    And I add "25.5" to the number
    Then the result should be "125.50"

  Scenario: Arithmetic operations
    Given I have a BcNumber with value "10"
    When I multiply the number by "3"
    Then the result should be "30"

  Scenario: Division with precision
    Given I have a BcNumber with value "10"
    When I set the scale to 2
    And I divide the number by "3"
    Then the result should be "3.33"

  Scenario: Subtraction
    Given I have a BcNumber with value "100"
    When I subtract "25" from the number
    Then the result should be "75"
