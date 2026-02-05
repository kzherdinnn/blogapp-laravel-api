# Test API Script
# Pastikan server Laravel sudah berjalan di http://127.0.0.1:8000

$baseUrl = "http://127.0.0.1:8000/api"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  BLOG API TESTING" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Test 1: Register User
Write-Host "1. Testing REGISTER endpoint..." -ForegroundColor Yellow
$registerData = @{
    name = "Test User"
    email = "test@example.com"
    password = "password123"
    password_confirmation = "password123"
} | ConvertTo-Json

try {
    $registerResponse = Invoke-RestMethod -Uri "$baseUrl/register" -Method Post -Body $registerData -ContentType "application/json"
    Write-Host "✓ Register Success!" -ForegroundColor Green
    Write-Host "User: $($registerResponse.user.name) ($($registerResponse.user.email))" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Register Failed (User might already exist)" -ForegroundColor Red
    Write-Host ""
}

# Test 2: Login User
Write-Host "2. Testing LOGIN endpoint..." -ForegroundColor Yellow
$loginData = @{
    email = "test@example.com"
    password = "password123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/login" -Method Post -Body $loginData -ContentType "application/json"
    $token = $loginResponse.access_token
    Write-Host "✓ Login Success!" -ForegroundColor Green
    Write-Host "Token: $($token.Substring(0, 50))..." -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Login Failed" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    exit
}

# Test 3: Create Post
Write-Host "3. Testing CREATE POST endpoint..." -ForegroundColor Yellow
$postData = @{
    title = "My First Blog Post"
    body = "This is the content of my first blog post. Laravel API is working great!"
} | ConvertTo-Json

$headers = @{
    "Authorization" = "Bearer $token"
    "Content-Type" = "application/json"
}

try {
    $createPostResponse = Invoke-RestMethod -Uri "$baseUrl/posts/create" -Method Post -Body $postData -Headers $headers
    $postId = $createPostResponse.post.id
    Write-Host "✓ Post Created!" -ForegroundColor Green
    Write-Host "Post ID: $postId" -ForegroundColor Gray
    Write-Host "Title: $($createPostResponse.post.title)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Create Post Failed" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
}

# Test 4: Get All Posts
Write-Host "4. Testing GET ALL POSTS endpoint..." -ForegroundColor Yellow
try {
    $postsResponse = Invoke-RestMethod -Uri "$baseUrl/posts/" -Method Get -Headers $headers
    Write-Host "✓ Retrieved Posts!" -ForegroundColor Green
    Write-Host "Total Posts: $($postsResponse.posts.Count)" -ForegroundColor Gray
    
    if ($postsResponse.posts.Count -gt 0) {
        Write-Host "`nFirst Post:" -ForegroundColor Gray
        Write-Host "  - ID: $($postsResponse.posts[0].id)" -ForegroundColor Gray
        Write-Host "  - Title: $($postsResponse.posts[0].title)" -ForegroundColor Gray
        Write-Host "  - Author: $($postsResponse.posts[0].user.name)" -ForegroundColor Gray
    }
    Write-Host ""
} catch {
    Write-Host "✗ Get Posts Failed" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
}

# Test 5: Get My Posts
Write-Host "5. Testing GET MY POSTS endpoint..." -ForegroundColor Yellow
try {
    $myPostsResponse = Invoke-RestMethod -Uri "$baseUrl/posts/my_posts" -Method Get -Headers $headers
    Write-Host "✓ Retrieved My Posts!" -ForegroundColor Green
    Write-Host "My Posts Count: $($myPostsResponse.posts.Count)" -ForegroundColor Gray
    Write-Host ""
} catch {
    Write-Host "✗ Get My Posts Failed" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
}

# Test 6: Create Comment (if we have a post)
if ($postId) {
    Write-Host "6. Testing CREATE COMMENT endpoint..." -ForegroundColor Yellow
    $commentData = @{
        post_id = $postId
        body = "Great post! This is a test comment."
    } | ConvertTo-Json

    try {
        $commentResponse = Invoke-RestMethod -Uri "$baseUrl/comments/create" -Method Post -Body $commentData -Headers $headers
        Write-Host "✓ Comment Created!" -ForegroundColor Green
        Write-Host "Comment: $($commentResponse.comment.body)" -ForegroundColor Gray
        Write-Host ""
    } catch {
        Write-Host "✗ Create Comment Failed" -ForegroundColor Red
        Write-Host $_.Exception.Message -ForegroundColor Red
        Write-Host ""
    }
}

# Test 7: Like Post (if we have a post)
if ($postId) {
    Write-Host "7. Testing LIKE POST endpoint..." -ForegroundColor Yellow
    $likeData = @{
        post_id = $postId
    } | ConvertTo-Json

    try {
        $likeResponse = Invoke-RestMethod -Uri "$baseUrl/posts/like" -Method Post -Body $likeData -Headers $headers
        Write-Host "✓ Post Liked!" -ForegroundColor Green
        Write-Host ""
    } catch {
        Write-Host "✗ Like Post Failed" -ForegroundColor Red
        Write-Host $_.Exception.Message -ForegroundColor Red
        Write-Host ""
    }
}

# Test 8: Logout
Write-Host "8. Testing LOGOUT endpoint..." -ForegroundColor Yellow
try {
    $logoutResponse = Invoke-RestMethod -Uri "$baseUrl/logout" -Method Get -Headers $headers
    Write-Host "✓ Logout Success!" -ForegroundColor Green
    Write-Host ""
} catch {
    Write-Host "✗ Logout Failed" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  TESTING COMPLETED!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
