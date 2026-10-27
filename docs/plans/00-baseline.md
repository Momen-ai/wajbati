# Baseline Test Run

**Date:** 2026-10-17  
**Commit:** HEAD (`4be892b "updating and fixing"`)  
**PHP Version:** 8.3.28  
**Laravel Version:** 12.x  
**PHPUnit Version:** 12.5.3  

## Command Executed
```bash
php artisan test
```

## Results
- **Total Tests:** 44
- **Passed:** 42
- **Failed:** 2
- **Assertions:** 95
- **Duration:** 34.74s

### Failures Recorded
1. `Tests\Feature\OrderStatusTest > allowed transition succeeds`:
   - `ArgumentCountError`: Too few arguments to function `Tests\Feature\OrderStatusTest::test_allowed_transition_succeeds()`, 0 passed and exactly 2 expected.
   - Cause: PHPUnit 12 does not support `@dataProvider` annotations in docblocks; requires `#[\PHPUnit\Framework\Attributes\DataProvider]`.
2. `Tests\Feature\OrderStatusTest > forbidden transition returns error`:
   - `ArgumentCountError`: Too few arguments to function `Tests\Feature\OrderStatusTest::test_forbidden_transition_returns_error()`, 0 passed and exactly 2 expected.
   - Cause: Same PHPUnit 12 attribute requirement (documented in F-26).

*(Note: ProfileTest passed because Vite was not run during this headless run or hot asset was present; once manifest is enforced without build it fails as noted in F-26).*
