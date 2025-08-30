# Behat Functional Tests for GryfOSS Data-Types Library

## Overview

This project now includes comprehensive Behat functional tests that cover all major classes and functionality in the GryfOSS data-types library. These tests verify the behavior from a user's perspective, ensuring that the library functions correctly in real-world scenarios.

## Test Coverage

### Classes Tested

1. **BcNumber** - Arbitrary precision arithmetic operations
2. **Binary** - Binary data manipulation and conversion
3. **Base16** - Hexadecimal encoding/decoding
4. **Base16\Decoder** - Base16 to various format conversions
5. **ByteReader** - Sequential binary data reading
6. **Digest** - Cryptographic hash generation
7. **BcMath** - Mathematical utilities for base conversion
8. **ASCII** - ASCII to Base16 encoding/decoding
9. **DataTypes** - Data type validation utilities
10. **Integers** - Range validation utilities

### Test Scenarios (37 total)

#### BcNumber (4 scenarios)
- Creating BcNumber with basic operations
- Arithmetic operations (multiply, divide, add, subtract)
- Division with precision control
- Scale setting for decimal places

#### Binary Buffer (5 scenarios)
- Binary to Base16 conversion
- Binary to Base64 conversion
- Binary to Bitwise conversion
- Buffer manipulation (append operations)
- Read-only buffer protection

#### Base16 (3 scenarios)
- Base16 to ASCII conversion
- Base16 to decimal conversion
- Complex hex validation

#### ByteReader (3 scenarios)
- Sequential byte reading
- Reading remaining bytes
- Reader position management and reset

#### BcMath (6 scenarios)
- Decimal to Base16 encoding
- Base16 to decimal decoding
- Base conversion (decimal ↔ binary, hex ↔ decimal)
- Large number conversions

#### ASCII (4 scenarios)
- ASCII to Base16 encoding
- Round-trip encoding/decoding
- Number encoding
- UTF-8 validation and error handling

#### DataTypes (6 scenarios)
- Bitwise data validation
- Base16 data validation
- UTF-8 character detection
- Invalid data type detection

#### Integers (5 scenarios)
- Range boundary testing
- Lower and upper boundary validation
- Out-of-range detection

#### Digest (1 scenario)
- SHA256 hash generation

### Key Features Tested

1. **Data Conversion**: Binary ↔ Base16 ↔ Base64 ↔ ASCII ↔ Bitwise
2. **Arithmetic Operations**: Precision arithmetic with scale control
3. **Buffer Management**: Read-only protection, append/prepend operations
4. **Stream Processing**: Sequential byte reading with position tracking
5. **Data Validation**: Type checking and format validation
6. **Error Handling**: Exception handling for invalid operations
7. **Cryptographic Functions**: Hash generation for data integrity

## Running the Tests

### Prerequisites
- PHP 8+
- Composer
- Behat (installed via composer)

### Commands

```bash
# Run all functional tests
vendor/bin/behat

# Run tests without colors (for CI/automation)
vendor/bin/behat --no-colors

# Run specific feature
vendor/bin/behat features/bcnumber.feature

# Dry run to check test syntax
vendor/bin/behat --dry-run
```

### Test Results
✅ **37 scenarios (37 passed)**
✅ **104 steps (104 passed)**
⏱️ **Execution time: ~0.02s**

## Project Structure

```
features/
├── bootstrap/
│   └── FeatureContext.php          # Step definitions and test context
├── ascii.feature                   # ASCII utility tests
├── base16.feature                  # Base16 buffer tests
├── bcmath.feature                  # BcMath utility tests
├── bcnumber.feature                # BcNumber arithmetic tests
├── binary.feature                  # Binary buffer tests
├── bytereader.feature              # ByteReader stream tests
├── datatypes.feature               # DataTypes validation tests
├── digest.feature                  # Hash digest tests
└── integers.feature                # Integer utility tests
behat.yml                           # Behat configuration
```

## Benefits

1. **User-Centric Testing**: Tests verify behavior from the end-user perspective
2. **Human-Readable Specifications**: Feature files serve as living documentation
3. **Comprehensive Coverage**: All major classes and use cases covered
4. **Regression Protection**: Prevents breaking changes to core functionality
5. **Documentation**: Serves as usage examples for library consumers
6. **Quality Assurance**: Ensures library behaves correctly in realistic scenarios

## Integration with PHPUnit

These Behat functional tests complement the existing PHPUnit unit tests:
- **PHPUnit**: Achieved 100% line coverage testing internal logic
- **Behat**: Tests external behavior and user workflows
- **Combined**: Provides comprehensive quality assurance from both technical and user perspectives

The combination of 100% PHPUnit coverage + comprehensive Behat functional tests ensures the GryfOSS data-types library is thoroughly tested and reliable for production use.
