# 🔗 Link Skiplock

A Laravel-based subscription-protected link management system that ensures only users subscribed to specific YouTube channels can access protected content.

## ✨ Features

### 🎯 Core Functionality
- **Protected Links**: Create short links that are only accessible to YouTube subscribers
- **YouTube Integration**: Seamless authentication and subscription verification via YouTube API
- **User Dashboard**: Comprehensive analytics and link management interface
- **Smart Authentication**: Google OAuth with YouTube scope for secure access
- **Real-time Verification**: Automatic subscription status checking with 24-hour cache

### 📊 Analytics & Management
- **Click Tracking**: Monitor access attempts and successful clicks
- **Channel Management**: Add and manage multiple YouTube channels
- **Link Status Control**: Enable/disable links instantly
- **Subscription Monitoring**: Track user subscription status in real-time

### 🎨 Modern UI/UX
- **Responsive Design**: Mobile-first approach with modern CSS
- **Smooth Animations**: AOS (Animate On Scroll) integration
- **Clean Interface**: Professional dashboard with intuitive navigation
- **Accessibility**: Touch-friendly design with proper contrast ratios

## 🚀 Quick Start

### Prerequisites

- **PHP**: >= 8.1
- **Composer**: Latest version
- **Node.js**: >= 16.x
- **MySQL**: >= 8.0 or MariaDB >= 10.3
- **YouTube Data API v3**: API key required
- **Google OAuth**: Client credentials required

### 1. Clone Repository

```bash
git clone https://github.com/sw3do/laravel-link-skiplock.git
cd laravel-link-skiplock
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Environment Variables

Edit `.env` file with your credentials:

```env
# Application
APP_NAME="Link Skiplock"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=link_skiplock
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Google OAuth (Required)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# YouTube API (Required)
YOUTUBE_API_KEY=your_youtube_api_key
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 6. Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🔧 Configuration

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable **Google+ API** and **YouTube Data API v3**
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `http://localhost:8000/auth/google/callback`
5. Copy Client ID and Client Secret to `.env`

### YouTube API Setup

1. In Google Cloud Console, go to **APIs & Services > Credentials**
2. Create API Key
3. Restrict the key to **YouTube Data API v3**
4. Copy API key to `.env` as `YOUTUBE_API_KEY`

### Production Deployment

For production deployment, update these environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Update OAuth redirect URI
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

## 🏗️ Architecture

### Project Structure

```
laravel-link-skiplock/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Google OAuth & YouTube auth
│   │   ├── DashboardController.php # Main dashboard
│   │   ├── LinkController.php      # Protected link management
│   │   └── YouTubeController.php   # Channel management
│   └── Models/
│       ├── User.php               # User model with YouTube integration
│       ├── ProtectedLink.php      # Protected link model
│       └── YouTubeChannel.php     # YouTube channel model
├── resources/
│   ├── views/                     # Blade templates
│   ├── css/app.css               # Custom CSS with utilities
│   └── js/app.js                 # JavaScript functionality
└── database/migrations/           # Database schema
```

### Database Schema

#### Users Table
- Basic user information
- Google OAuth tokens
- YouTube subscription data with timestamps
- Subscription cache management

#### YouTube Channels Table
- Channel metadata (ID, name, thumbnail)
- User relationship for multi-tenant support

#### Protected Links Table
- Link information and configuration
- Click tracking
- Channel association
- Status management

### Key Features Implementation

#### YouTube Subscription Verification
```php
// Check if user is subscribed to specific channel
public function isSubscribedToChannel($channelId)
{
    return in_array($channelId, $this->youtube_subscriptions ?? []);
}

// Smart cache with 24-hour refresh
public function areSubscriptionsStale()
{
    return $this->subscriptions_updated_at?->lt(now()->subDay()) ?? true;
}
```

#### Smart Authentication Flow
1. User clicks protected link
2. System checks authentication status
3. If not authenticated, redirects to Google OAuth
4. After authentication, returns to original link
5. Verifies YouTube subscription
6. Grants or denies access

## 🎨 Styling & UI

### CSS Architecture

The project uses a utility-first CSS approach with custom components:

```css
/* Utility Classes */
.flex, .grid, .gap-4, .w-full    /* Layout utilities */
.text-sm, .font-medium           /* Typography utilities */
.btn, .card, .badge              /* Component classes */
.sm:flex-row, .md:grid-4         /* Responsive variants */
```

### Responsive Design

- **Mobile First**: Optimized for mobile devices
- **Breakpoints**: `sm: 640px`, `md: 768px`, `lg: 1024px`
- **Touch Friendly**: 44px minimum touch targets
- **Progressive Enhancement**: Works without JavaScript

### Animation System

Uses AOS (Animate On Scroll) for smooth animations:

```html
<div data-aos="fade-up" data-aos-delay="200">
    <!-- Animated content -->
</div>
```

## 🔐 Security Features

### Authentication
- Google OAuth 2.0 integration
- Secure token storage and refresh
- Session-based user management

### Access Control
- YouTube subscription verification
- Link-level access control
- User-based resource isolation

### Data Protection
- Encrypted sensitive data storage
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM

## 📱 API Integration

### YouTube Data API v3

#### Subscription Fetching
```php
$youtube = new YouTube($client);
$response = $youtube->subscriptions->listSubscriptions('snippet', [
    'mine' => true,
    'maxResults' => 50
]);
```

#### Channel Information
```php
$response = $youtube->channels->listChannels('snippet', [
    'id' => $channelId
]);
```

### Google OAuth Integration

#### Scopes Required
- `https://www.googleapis.com/auth/youtube.readonly`
- Basic profile information

## 🚀 Deployment

### Production Checklist

1. **Environment Setup**
   ```bash
   cp .env.example .env.production
   # Configure production variables
   ```

2. **Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   ```

3. **Database**
   ```bash
   php artisan migrate --force
   ```

4. **Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **File Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### Docker Support

```dockerfile
FROM php:8.1-apache
# ... (Docker configuration)
```

### Performance Optimization

- **Query Optimization**: Eager loading for related models
- **Caching**: Redis for session and cache storage
- **Asset Optimization**: Minified CSS/JS in production
- **Database Indexing**: Optimized indexes for frequent queries

## 🤝 Contributing

### Development Setup

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Make changes and test thoroughly
4. Commit changes: `git commit -m 'Add amazing feature'`
5. Push to branch: `git push origin feature/amazing-feature`
6. Open a Pull Request

### Code Standards

- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Follow Laravel best practices
- **Testing**: Include tests for new features
- **Documentation**: Update README for significant changes

### Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📄 License

This project is licensed under the MIT License. See [LICENSE](LICENSE) file for details.

## 🐛 Troubleshooting

### Common Issues

#### Google OAuth Errors
- Verify redirect URI matches exactly
- Check API credentials are correct
- Ensure Google+ API is enabled

#### YouTube API Issues
- Confirm API key is restricted properly
- Check quota limits in Google Cloud Console
- Verify channel IDs are correct format

#### Database Connection
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check database exists and is accessible

### Support

For issues and questions:
1. Check existing [Issues](../../issues)
2. Create new issue with detailed description
3. Include environment details and error logs

## 📊 Roadmap

### Upcoming Features
- [ ] Advanced analytics dashboard
- [ ] Multiple subscription requirements per link
- [ ] Email notifications for access attempts
- [ ] API endpoints for external integration
- [ ] Bulk link management
- [ ] Custom domain support

### Long-term Goals
- [ ] Multi-platform support (Twitch, Discord)
- [ ] Advanced user roles and permissions
- [ ] White-label solutions
- [ ] Enterprise features

---

**Built with ❤️ using Laravel, YouTube API, and modern web technologies.**
