# UI Review: Auth & Profile Settings

## 1. Audit
- **Surfaces**: `auth.login`, `auth.register`, `auth.forgot-password`, `profile.edit`, `front.chef.profile.edit`.
- **Security & UX**: Form validation feedback, CSRF protection, password confirmation dialogs, and email verification notice.

## 2. Critique
- **Before**:
  - Auth pages had hardcoded "Login" and "Register" headings with LTR layout in Arabic mode.
  - Profile address management allowed adding unlimited addresses without validation limits.
  - Image upload lacked size and mime-type client feedback before submit.
- **After**:
  - Full bidirectional support for auth templates with right-aligned labels in RTL and left-aligned in LTR.
  - Address limit capped at 10 with clear error alerts.
  - Client-side and server-side validation enforcing max 2MB image uploads with valid extensions.

## 3. Harden & Clarify
- **Role Assignment**:
  - Register form payload strictly creates role `user`. Attempting to pass `role=admin` or `role=chef` in post body is securely ignored.
- **Natural Copy**:
  - "Kitchen Name" -> "اسم المطبخ أو العلامة التجارية"
  - "Leave blank to keep current password" -> "اتركه فارغاً إذا كنت لا ترغب بتغيير كلمة المرور"

## 4. Adapt & Polish
- Ergonomic single-column card layout centered on mobile screens.
- Smooth toast alerts for successful profile, password, or address updates.
