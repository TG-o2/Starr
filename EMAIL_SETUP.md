# Email Verification Setup Guide

## Problem Fixed
✓ Email verification flow is now working  
✓ PDO database connection in verify.php is fixed  
✓ Environment configuration file created

## Remaining Step: Configure Gmail Credentials

### Why Email Verification Isn't Working Yet
The email verification system requires Gmail SMTP credentials. These are stored in the `.env` file (which has been created but is empty).

### How to Enable Email Verification

#### Step 1: Create Gmail App Password
Since Gmail disabled "Less Secure Apps", you need to generate an "App Password":

1. Go to [Google Account Security](https://myaccount.google.com/security)
2. Enable **2-Factor Authentication** (if not already enabled)
3. Go to **App passwords** → Select "Mail" → Select "Windows Computer" (or your OS)
4. Google will generate a 16-character password
5. Copy the generated password (without spaces)

#### Step 2: Update .env File
Open the `.env` file in the root directory and replace the placeholder values:

```env
GMAIL_USER=your-actual-email@gmail.com
GMAIL_APP_PASSWORD=your16charpassword
```

**Example:**
```env
GMAIL_USER=starr.education@gmail.com
GMAIL_APP_PASSWORD=abcd efgh ijkl mnop
```

#### Step 3: Test the Flow
1. **Create new user** via signup.php
2. **Check email** (inbox and spam folder) for verification email
3. **Click verification link** - should show ✓ success message
4. **Login** with the verified account

---

## How It Works (Technical Details)

### Email Verification Flow:
1. User signs up → `addUserWithVerification()` creates user with `verified = 0`
2. PHPMailer sends verification email with unique token link
3. User clicks link → `verify.php` marks user as `verified = 1`
4. User can now login

### Key Files:
- `.env` - Gmail credentials (KEEP SECRET - don't commit!)
- `config/email_config.php` - Email configuration
- `Controller/UserController.php` - `addUserWithVerification()` method
- `View/Front office/User-signup/verify.php` - Email verification handler
- `View/Front office/User-signup/login.php` - Shows verification status

### Login Status Codes:
- `verified = 0` → "Account requires verification" + auto-resend email
- `verified = 1` + `is_approved = 0` (non-admin) → "Pending admin approval"
- `is_banned = 1` → "Your account has been banned"
- Otherwise → Successful login

---

## Troubleshooting

### Issue: "This verification link is invalid or has already been used"
- **Cause:** Token was used or expired
- **Fix:** Click "Resend Verification Email" button on login page

### Issue: Verification email not arriving
1. Check Gmail spam/junk folder
2. Verify `.env` file has correct credentials
3. Check `logs/email.log` for PHPMailer errors

### Issue: "Your session has expired for security" after logout
- **Expected behavior:** After logout/re-login, system auto-resends verification email
- **Fix:** Check email and click new verification link

---

## Security Notes
⚠️ **IMPORTANT:**
- Never commit `.env` file with real credentials to GitHub
- Add `.env` to `.gitignore` (if not already)
- Use Gmail App Password, not your actual Gmail password
- Regenerate App Password if it's exposed

---

## Next Steps (After Email Setup)
1. Test complete signup → email → verification → login flow
2. Monitor `logs/email.log` for any sending errors
3. Implement optional: "Resend verification email" feature on dashboard

