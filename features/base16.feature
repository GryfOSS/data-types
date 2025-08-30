Feature: Base16 Buffer functionality
  As a developer
  I want to work with hexadecimal data
  So that I can encode and decode hex information

  Scenario: Base16 to ASCII conversion
    Given I have a Base16 buffer with hexits "48656c6c6f"
    When I decode the Base16 buffer
    And I convert to ASCII
    Then the result should be "Hello"

  Scenario: Base16 to decimal conversion
    Given I have a Base16 buffer with hexits "ff"
    When I decode the Base16 buffer
    And I convert to base10 number
    Then the result should be "255"

  Scenario: Base16 validation
    Given I have a Base16 buffer with hexits "deadbeef"
    When I decode the Base16 buffer
    And I convert to base10 number
    Then the result should be "3735928559"
