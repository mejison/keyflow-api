# KeyFlow API - Endpoints Summary

## Available Endpoints ✅

### System
- ✅ `GET /api/health` - Health check

### Authentication (Public)
- ✅ `POST /api/v1/auth/register` - User registration
- ✅ `POST /api/v1/auth/login` - User login
- ✅ `POST /api/v1/auth/forgot-password` - Request password reset
- ✅ `POST /api/v1/auth/reset-password` - Reset password with token

### Authentication (Protected - requires Bearer token)
- ✅ `POST /api/v1/auth/logout` - Logout (revoke token)
- ✅ `GET /api/v1/auth/me` - Get current user info

### Typing Tests (Protected - requires Bearer token)
- ✅ `POST /api/v1/typing-tests` - Save completed test
- ✅ `GET /api/v1/typing-tests` - Get all tests (paginated)
- ✅ `GET /api/v1/typing-tests/statistics` - Get user statistics
- ✅ `GET /api/v1/typing-tests/recent-activity` - Get recent test history
- ✅ `GET /api/v1/typing-tests/progress` - Get progress over time
- ✅ `GET /api/v1/typing-tests/personal-bests` - Get personal best records
- ✅ `GET /api/v1/typing-tests/{id}` - Get specific test
- ✅ `DELETE /api/v1/typing-tests/{id}` - Delete test

### Social Authentication
- ✅ `GET /api/v1/auth/social/google` - Redirect to Google OAuth
- ✅ `GET /api/v1/auth/social/google/callback` - Google OAuth callback (redirects to frontend)
- ✅ `GET /api/v1/auth/social/github` - Redirect to GitHub OAuth
- ✅ `GET /api/v1/auth/social/github/callback` - GitHub OAuth callback (redirects to frontend)

**OAuth Flow:**
1. User clicks "Login with Google/GitHub"
2. Backend redirects to OAuth provider
3. User authorizes
4. Provider redirects to callback
5. Backend creates token and redirects to: `{FRONTEND_URL}/auth/callback/{provider}?token={token}`

## Quick Test Commands

### 1. Register
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 3. Get Current User (replace TOKEN with your token)
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer TOKEN"
```

### 4. Logout (replace TOKEN with your token)
```bash
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Authorization: Bearer TOKEN"
```

## Tested & Working ✅

- ✅ User Registration
- ✅ User Login
- ✅ Get Current User (with token)
- ✅ Health Check
- ✅ Validation Errors
- ✅ Database Migrations
- ✅ Sanctum Token Authentication

## Next Steps for OAuth

To enable Google/GitHub authentication:

1. **Setup OAuth Apps:**
   - Google: https://console.cloud.google.com/
   - GitHub: https://github.com/settings/developers

2. **Add credentials to .env:**
   ```env
   FRONTEND_URL=http://localhost:5173
   
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   
   GITHUB_CLIENT_ID=your_client_id
   GITHUB_CLIENT_SECRET=your_client_secret
   ```

3. **Test OAuth flow:**
   - Visit `/api/v1/auth/social/google` in browser
   - Complete OAuth flow on provider's site
   - Provider redirects to callback endpoint
   - Backend redirects to frontend with token: `http://localhost:5173/auth/callback/google?token={token}`

## Documentation Files

- [AUTH_API.md](AUTH_API.md) - Complete authentication API documentation (English)
- [TYPING_TEST_API.md](TYPING_TEST_API.md) - Typing test API documentation
- [SETUP_AUTH.md](SETUP_AUTH.md) - Setup instructions (Ukrainian)
- This file - Quick reference

## Response Format

All endpoints return JSON with this structure:

**Success:**
```json
{
  "success": true,
  "message": "Description",
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error description",
  "data": { ... }
}
```
