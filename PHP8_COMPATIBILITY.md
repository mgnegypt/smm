# PHP 8 Compatibility Notes

This document tracks the PHP 8 compatibility fixes applied in this repository.

## Scope of this update

- Scanned `controller/`, `lib/`, and root `*.php` files for deprecated APIs (`each`, `ereg`, and common removed functions).
- Replaced deprecated PHP APIs used by payment/authentication paths.
- Added a shared input-normalization layer to reduce type-conversion and undefined-index issues in PHP 8.

## Changes made

1. **`each()` replacement in payment IPN flow**
   - File: `controller/panel/payment.php`
   - Replaced `while(list(...) = each($_POST))` with `foreach` over sanitized input (`$postData`).
   - Preserved original hash-string concatenation logic while adding safe extraction for `IPN_PID`, `IPN_PNAME`, and `IPN_DATE`.

2. **`ereg()` replacement in WebMoney integration**
   - File: `lib/webmoney/webmoney.inc.php`
   - Replaced:
     - `ereg('^[ZREUD][0-9]{12}$', $tmp)`
   - With:
     - `preg_match('/^[ZREUD][0-9]{12}$/', $tmp)`
   - Validation semantics remain the same.

3. **Unified input-validation helper layer**
   - New file: `lib/php8_input.php`
   - Added helper functions:
     - `input_safe_array()`
     - `input_string()`
     - `input_array()`
     - `input_int()`
   - These helpers provide safe defaults and normalization for request payloads.

4. **Authentication input hardening**
   - Files:
     - `controller/panel/auth.php`
     - `controller/admin/login.php`
   - Switched direct `$_POST` reads to normalized reads via `lib/php8_input.php` helpers.

## Why this matters for PHP 8+

- PHP 8 is stricter around type handling and deprecated/removed functions.
- Replacing removed APIs (`each`, `ereg`) prevents runtime fatals.
- Normalizing request input reduces undefined index warnings and accidental scalar/array conversion errors.

## Notes from scan

- No remaining **PHP `each()` function usage** was found in scanned PHP files after this update.
- No remaining **PHP `ereg*()` usage** was found in scanned PHP files after this update.
- Matches for `split` seen in vendor/docs assets are not PHP deprecated runtime calls in the project flow.
