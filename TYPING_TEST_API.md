# Typing Test API Documentation

## Endpoints

### 1. Save Typing Test
**POST** `/api/v1/typing-tests`

Save a completed typing test result.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "wpm": 75,
  "accuracy": 94.5,
  "duration": 60,
  "correct_words": 120,
  "incorrect_words": 5,
  "total_words": 125,
  "text_content": "The quick brown fox..."
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Typing test saved successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "wpm": 75,
    "accuracy": "94.50",
    "duration": 60,
    "completed_at": "2026-01-14T09:00:00.000000Z"
  }
}
```

---

### 2. Get Statistics
**GET** `/api/v1/typing-tests/statistics`

Get user's typing statistics summary.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Statistics retrieved successfully",
  "data": {
    "best_wpm": 82,
    "avg_wpm": 74,
    "avg_accuracy": 92.90,
    "tests_taken": 42,
    "total_words_typed": 15840,
    "total_time_spent": 7200,
    "recent_tests": [...]
  }
}
```

---

### 3. Get Recent Activity
**GET** `/api/v1/typing-tests/recent-activity?limit=10`

Get user's recent typing test history.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `limit` (optional, default: 10) - Number of results to return

**Response (200):**
```json
{
  "success": true,
  "message": "Recent activity retrieved successfully",
  "data": [
    {
      "id": 1,
      "wpm": 75,
      "accuracy": "94.00",
      "duration": 100,
      "completed_at": "2026-01-14T07:17:13.000000Z",
      "completed_at_human": "2 hours ago"
    }
  ]
}
```

---

### 4. Get Progress
**GET** `/api/v1/typing-tests/progress?days=30`

Get user's typing progress over time.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `days` (optional, default: 30) - Number of days to retrieve

**Response (200):**
```json
{
  "success": true,
  "message": "Progress data retrieved successfully",
  "data": [
    {
      "date": "2026-01-14",
      "wpm": 75,
      "accuracy": "94.00",
      "completed_at": "2026-01-14T07:17:13+00:00"
    }
  ]
}
```

---

### 5. Get All Tests (Paginated)
**GET** `/api/v1/typing-tests?per_page=15`

Get all typing tests with pagination.

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page` (optional, default: 15) - Items per page

**Response (200):**
```json
{
  "success": true,
  "message": "Typing tests retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [...],
    "per_page": 15,
    "total": 42
  }
}
```

---

### 6. Get Personal Bests
**GET** `/api/v1/typing-tests/personal-bests`

Get user's personal best records.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Personal bests retrieved successfully",
  "data": {
    "highest_wpm": {...},
    "highest_accuracy": {...},
    "longest_test": {...},
    "most_words": {...}
  }
}
```

---

### 7. Get Single Test
**GET** `/api/v1/typing-tests/{id}`

Get details of a specific typing test.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Typing test retrieved successfully",
  "data": {
    "id": 1,
    "wpm": 75,
    "accuracy": "94.00",
    ...
  }
}
```

---

### 8. Delete Test
**DELETE** `/api/v1/typing-tests/{id}`

Delete a typing test.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Typing test deleted successfully",
  "data": []
}
```

---

## Frontend Integration Example

```javascript
// Save a completed test
const saveTest = async (testData) => {
  const response = await fetch('http://localhost:8000/api/v1/typing-tests', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(testData)
  })
  return await response.json()
}

// Get statistics
const getStats = async () => {
  const response = await fetch('http://localhost:8000/api/v1/typing-tests/statistics', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  })
  return await response.json()
}

// Get recent activity
const getActivity = async (limit = 10) => {
  const response = await fetch(
    `http://localhost:8000/api/v1/typing-tests/recent-activity?limit=${limit}`,
    {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    }
  )
  return await response.json()
}
```
