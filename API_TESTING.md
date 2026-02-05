# Blog API - Testing Guide

Server berjalan di: **http://127.0.0.1:8000**

## 📋 Available Endpoints

### 🔓 Public Endpoints (No Authentication Required)

#### 1. Register User
- **URL**: `POST /api/register`
- **Body**:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### 2. Login User
- **URL**: `POST /api/login`
- **Body**:
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```
- **Response**: Returns JWT token

---

### 🔒 Protected Endpoints (Require JWT Token)

**Note**: Add token to header: `Authorization: Bearer {your_token}`

#### 3. Logout
- **URL**: `GET /api/logout`
- **Headers**: `Authorization: Bearer {token}`

#### 4. Save User Info
- **URL**: `POST /api/save_user_info`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "name": "Updated Name",
    "email": "updated@example.com"
}
```

---

### 📝 Post Endpoints

#### 5. Get All Posts
- **URL**: `GET /api/posts/`
- **Headers**: `Authorization: Bearer {token}`

#### 6. Get My Posts
- **URL**: `GET /api/posts/my_posts`
- **Headers**: `Authorization: Bearer {token}`

#### 7. Create Post
- **URL**: `POST /api/posts/create`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "title": "My First Blog Post",
    "body": "This is the content of my blog post..."
}
```

#### 8. Update Post
- **URL**: `POST /api/posts/posts/{id}`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "title": "Updated Title",
    "body": "Updated content..."
}
```

#### 9. Delete Post
- **URL**: `DELETE /api/posts/{id}`
- **Headers**: `Authorization: Bearer {token}`

#### 10. Like Post
- **URL**: `POST /api/posts/like`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "post_id": 1
}
```

---

### 💬 Comment Endpoints

#### 11. Get Comments for Post
- **URL**: `POST /api/posts/comments`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "post_id": 1
}
```

#### 12. Create Comment
- **URL**: `POST /api/comments/create`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "post_id": 1,
    "body": "Great post!"
}
```

#### 13. Update Comment
- **URL**: `PUT /api/comments/update`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "comment_id": 1,
    "body": "Updated comment text"
}
```

#### 14. Delete Comment
- **URL**: `DELETE /api/comments/delete`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
```json
{
    "comment_id": 1
}
```

---

## 🧪 Testing Flow

### Step 1: Register a new user
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Test User\",\"email\":\"test@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
```

### Step 2: Login to get token
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"test@example.com\",\"password\":\"password123\"}"
```

### Step 3: Create a post (use token from step 2)
```bash
curl -X POST http://127.0.0.1:8000/api/posts/create \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d "{\"title\":\"My First Post\",\"body\":\"This is my first blog post content\"}"
```

### Step 4: Get all posts
```bash
curl -X GET http://127.0.0.1:8000/api/posts/ \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📊 Database Tables

- **users**: User accounts
- **posts**: Blog posts
- **comments**: Comments on posts
- **likes**: Post likes
- **password_resets**: Password reset tokens
- **migrations**: Migration history

---

## 🔧 Tools for Testing

You can use:
1. **Postman** - Import endpoints and test
2. **Thunder Client** (VS Code extension)
3. **cURL** - Command line testing
4. **Browser** - For simple GET requests
5. **Insomnia** - API testing tool

---

## ⚠️ Important Notes

1. All protected endpoints require JWT token in Authorization header
2. Token format: `Bearer {your_token_here}`
3. Register first, then login to get token
4. Token expires based on JWT_TTL setting (default: 60 minutes)
5. Refresh token TTL: 20160 minutes (14 days)
