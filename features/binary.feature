Feature: Binary Buffer functionality
  As a developer
  I want to work with binary data
  So that I can manipulate and convert binary information

  Scenario: Creating and converting binary data
    Given I have a Binary buffer with data "Hello"
    When I convert the binary to Base16
    Then the Base16 hexits should be "48656c6c6f"

  Scenario: Binary to Base64 conversion
    Given I have a Binary buffer with data "Hello"
    When I convert the binary to Base64
    Then the Base64 encoded should be "SGVsbG8="

  Scenario: Binary to Bitwise conversion
    Given I have a Binary buffer with data "A"
    When I convert the binary to Bitwise
    Then the Bitwise value should be "01000001"

  Scenario: Buffer manipulation
    Given I have a Binary buffer with data "Hello"
    When I append " World" to the buffer
    Then the buffer value should be "Hello World"
    And the buffer length should be 11

  Scenario: Read-only buffer protection
    Given I have a Binary buffer with data "Test"
    When I set the buffer to read-only
    And I try to modify the read-only buffer
    Then a BadMethodCallException should be thrown
