# Authentication API Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication Endpoints

### 1. Register
**POST** `/auth/register`

Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2024-01-14T07:00:00.000000Z",
      "updated_at": "2024-01-14T07:00:00.000000Z"
    },
    "access_token": "1|abcdefghijklmnopqrstuvwxyz",
    "token_type": "Bearer"
  }
}
```

---

### 2. Login
**POST** `/auth/login`

Login with email and password.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "User logged in successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "access_token": "2|abcdefghijklmnopqrstuvwxyz",
    "token_type": "Bearer"
  }
}
```

---

### 3. Logout
**POST** `/auth/logout`

Logout and revoke current access token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "User logged out successfully",
  "data": []
}
```

---

### 4. Get Current User
**GET** `/auth/me`

Get authenticated user information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "User retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "google_id": null,
    "github_id": null
  }
}
```

---

### 5. Forgot Password
**POST** `/auth/forgot-password`

Request password reset link.

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password reset link sent to your email",
  "data": []
}
```

---

### 6. Reset Password
**POST** `/auth/reset-password`

Reset password with token from email.

**Request Body:**
```json
{
  "token": "reset_token_from_email",
  "email": "john@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password reset successfully",
  "data": []
}
```

---

## Social Authentication

### Important: OAuth Flow Changed

OAuth callbacks now redirect to your frontend with the token in URL parameters instead of returning JSON.

**Success redirect:**
```
{FRONTEND_URL}/auth/callback/{provider}?token={access_token}
```

**Error redirect:**
```
{FRONTEND_URL}/auth/callback/{provider}?error={error_message}
```

### 7. Google Login - Redirect
**GET** `/auth/social/google`

Redirects user to Google OAuth authorization page.

---

### 8. Google Login - Callback
**GET** `/auth/social/google/callback`

Automatically called by Google after authorization. Redirects to frontend with token.

**Query Parameters:**
- `code`: Authorization code from Google (automatically provided)

**Redirects to:**
- Success: `{FRONTEND_URL}/auth/callback/google?token={access_token}`
- Error: `{FRONTEND_URL}/auth/callback/google?error={error_message}`

---

### 9. GitHub Login - Redirect
**GET** `/auth/social/github`

Redirects user to GitHub OAuth authorization page.

---

### 10. GitHub Login - Callback
**GET** `/auth/social/github/callback`

Automatically called by GitHub after authorization. Redirects to frontend with token.

**Query Parameters:**
- `code`: Authorization code from GitHub (automatically provided)

**Redirects to:**
- Success: `{FRONTEND_URL}/auth/callback/github?token={access_token}`
- Error: `{FRONTEND_URL}/auth/callback/github?error={error_message}`

---

## OAuth Setup Instructions

### Frontend URL Configuration

Add to your `.env` file:
```env
FRONTEND_URL=http://localhost:5173
```

This URL will be used for OAuth callback redirects.

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials (OAuth client ID)
5. Add authorized redirect URIs:
   - `http://localhost:8000/api/v1/auth/social/google/callback`
   - Add production URLs when deploying
6. Copy Client ID and Client Secret to `.env`:
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URL=http://localhost:8000/api/v1/auth/social/google/callback
```

### GitHub OAuth Setup

1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in application details:
   - **Application name**: Your app name
   - **Homepage URL**: `http://localhost:8000`
   - **Authorization callback URL**: `http://localhost:8000/api/v1/auth/social/github/callback`
4. Copy Client ID and Client Secret to `.env`:
```env
GITHUB_CLIENT_ID=your-client-id
GITHUB_CLIENT_SECRET=your-client-secret
GITHUB_REDIRECT_URL=http://localhost:8000/api/v1/auth/social/github/callback
```

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation Error",
  "data": {
    "email": ["The email field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthorized",
  "data": {
    "error": "Invalid credentials"
  }
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Not Found",
  "data": {
    "error": "Provider not supported"
  }
}
```

---

## Using the Access Token

Include the access token in the Authorization header for protected routes:

```
Authorization: Bearer {your_access_token}
```

Example using cURL:
```bash
curl -H "Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz" \
     http://localhost:8000/api/v1/auth/me
```

Example using JavaScript:
```javascript
fetch('http://localhost:8000/api/v1/auth/me', {
  headers: {
    'Authorization': 'Bearer 1|abcdefghijklmnopqrstuvwxyz',
    'Accept': 'application/json'
  }
})
```
