Feature: DataTypes utility functions
  As a developer
  I want to validate different data types
  So that I can ensure data integrity

  Scenario: Checking bitwise data
    When I check if "101010" is bitwise
    Then the result should be "true"

  Scenario: Checking invalid bitwise data
    When I check if "102010" is bitwise
    Then the result should be "false"

  Scenario: Checking Base16 data
    When I check if "deadbeef" is Base16
    Then the result should be "true"

  Scenario: Checking invalid Base16 data
    When I check if "xyz123" is Base16
    Then the result should be "false"

  Scenario: Checking UTF8 data
    When I check if "Hello" is UTF8
    Then the result should be "false"

  Scenario: Checking UTF8 data with special characters
    When I check if "Héllo" is UTF8
    Then the result should be "true"
