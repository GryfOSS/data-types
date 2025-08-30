Feature: ASCII utility functions
  As a developer
  I want to encode and decode ASCII to Base16
  So that I can convert text to hexadecimal representation

  Scenario: Encoding ASCII to Base16
    When I encode "Hello" to Base16 ASCII
    Then the result should be a Base16 object

  Scenario: Round-trip ASCII encoding and decoding
    Given I have a Base16 buffer with hexits "48656c6c6f"
    When I decode Base16 ASCII
    Then the result should be "Hello"

  Scenario: Encoding ASCII numbers
    When I encode "123" to Base16 ASCII
    Then the result should be a Base16 object

  Scenario: Attempting to encode UTF8 text
    When I try to encode UTF8 text "Héllo" to Base16
    Then an InvalidArgumentException should be thrown
    And the exception message should contain "Cannot encode UTF-8 string into hexadecimals"
