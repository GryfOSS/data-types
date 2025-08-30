Feature: BcMath utility functions
  As a developer
  I want to convert numbers between different bases
  So that I can work with various number systems

  Scenario: Encoding decimal to Base16
    When I encode number "255" to Base16
    Then the result should be "ff"

  Scenario: Decoding Base16 to decimal
    When I decode hexits "ff" to decimal
    Then the result should be "255"

  Scenario: Converting decimal to binary
    When I convert "10" from base 10 to base 2
    Then the result should be "1010"

  Scenario: Converting binary to decimal
    When I convert "1010" from base 2 to base 10
    Then the result should be "10"

  Scenario: Converting hex to decimal
    When I convert "ff" from base 16 to base 10
    Then the result should be "255"

  Scenario: Large number conversion
    When I convert "deadbeef" from base 16 to base 10
    Then the result should be "3735928559"
