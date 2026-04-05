# StrikeflixWebsite - Anime Streaming Platform

A modern, full-featured anime streaming website built with PHP and MySQL. Browse, watch, and track your favorite anime series with an intuitive interface and seamless user experience.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C87?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Google OAuth](https://img.shields.io/badge/Google_OAuth-4285F4?style=for-the-badge&logo=google&logoColor=white)

## 📋 Table of Contents
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Project Structure](#project-structure)
- [Usage](#usage)
- [Security](#security)
- [Author](#author)

## ✨ Features

### User Management
- **Traditional Login/Register** - Username and password authentication
- **Google OAuth 2.0 Integration** - One-click sign-in with Google
- **User Profiles** - Customize your profile with bio, birthdate, and profile picture

### Anime Browsing & Discovery
- **Browse Anime** - Explore all available anime series
- **Search Functionality** - Find anime by title (real-time search)
- **Genre Filtering** - Filter anime by genre categories
- **Featured & New Releases** - Highlighted showcase of popular and new anime
- **Anime Details** - View comprehensive information including ratings, status, and episode count

### Watchlist Management
- **Add to Watchlist** - Save anime for later viewing
- **Track Progress** - Keep track of current episode and watch status
- **User Ratings** - Rate anime on a 0-10 scale
- **Watchlist Dashboard** - View all your saved anime in one place

### Video Playback
- **Episode Streaming** - Watch episodes directly on the platform
- **Theater Mode** - Full-screen immersive viewing experience
- **Episode Navigation** - Easily navigate between episodes
- **Responsive Video Player** - Mobile-friendly video controls

## 🛠 Tech Stack

- **Backend:** PHP 8.x
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Authentication:** Google OAuth 2.0 with JWT support
- **UI Components:** Swiper.js for carousels
- **Package Manager:** Composer
- **Styling:** Custom CSS with responsive design

## 📦 Prerequisites

Before you begin, ensure you have the following installed:
- PHP 8.0 or higher
- MySQL 5.7 or higher (or MariaDB)
- Composer
- Web server (Apache/Nginx)
- Git

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/LosLejo/VideoStreamingWebsite.git
cd VideoStreamingWebsite
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Set Up Environment Variables
Copy the example environment file and update it with your credentials:
```bash
cp .env.example .env
```

### 4. Configure `.env` File
Edit the `.env` file and add your configuration:
```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost/google-callback.php

# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=strikeflix
```

**⚠️ Important:** Never commit `.env` to version control. It's in `.gitignore` by default.

## 🗄 Database Setup

### 1. Create Database
```sql
CREATE DATABASE u495515480_strikeflix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Run Setup Script
Visit `http://localhost/setup.php` in your browser to automatically create all tables:
- Users (authentication & profiles)
- Anime Series (anime information)
- Episodes (video content)
- Genre Categories (genre organization)
- Watchlist (user tracking)

Or manually execute the SQL from `setup.php` if needed.

## 📁 Project Structure

```
VideoStreamingWebsite/
├── Assets/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   ├── images/           # Image assets
│   ├── thumbnails/       # Anime thumbnails
│   ├── videos/           # Video files
│   └── swiper/           # Swiper library
├── vendor/               # Composer dependencies
├── config.php            # Environment configuration loader
├── db.php                # Database connection
├── setup.php             # Database initialization
├── login.php             # Authentication
├── home.php              # Homepage
├── search.php            # Search results
├── watch.php             # Video player
├── favorites.php         # Watchlist management
├── profile.php           # User profile
├── genres.php            # Genre browsing
├── .env                  # Environment variables (not in Git)
├── .env.example          # Environment template
└── .gitignore            # Git ignore rules
```

## 🎯 Usage

### 1. Start Your Server
```bash
# Using PHP built-in server
php -S localhost:8000

# Or use your Apache/Nginx setup
```

### 2. Access the Application
- Visit `http://localhost/home.php`
- Create an account or use Google OAuth
- Browse and add anime to your watchlist
- Start watching!

### 3. Admin Features (Optional)
- Visit `http://localhost/insert_anime.php` to add new anime
- Visit `http://localhost/populate_genres.php` to manage genres

## 🔒 Security

### Environment Variables
All sensitive credentials are managed through environment variables:
- Database credentials are in `.env` (not in Git)
- Google OAuth keys are in `.env`
- All configuration is loaded from `config.php`

### Best Practices Implemented
- SQL prepared statements to prevent SQL injection
- Password hashing with PHP's `password_hash()`
- OAuth 2.0 for secure authentication
- HTTPS recommended in production
- CORS-friendly setup

### Secret Management
- `.env` is in `.gitignore` - never committed
- Use `.env.example` as a template for setup
- Always verify credentials are environment variables before pushing

## 📄 Key Files

- **config.php** - Loads environment variables and sets up configuration
- **db.php** - Database connection using environment variables
- **login.php** - User authentication (traditional & Google OAuth)
- **google-callback.php** - Google OAuth redirect handler
- **home.php** - Main anime browsing interface
- **watch.php** - Video player with theater mode
- **favorites.php** - Watchlist management dashboard

## 🎬 Features Walkthrough

### Watching Anime
1. Login with email or Google account
2. Search or browse anime genres
3. Click on an anime to view details
4. Add to watchlist
5. Click "Watch" to start streaming

### Managing Watchlist
- View progress for each anime
- Update current episode watched
- Rate anime on your watchlist
- Remove anime when finished

### User Profile
- Update profile information
- View watchlist statistics
- Manage watched anime history

## 🐛 Troubleshooting

### Database Connection Failed
- Check `.env` file is properly configured
- Verify MySQL/MariaDB is running
- Run `setup.php` to create tables

### Google OAuth Not Working
- Verify `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` in `.env`
- Check redirect URI matches Google Cloud Console settings
- Ensure `GOOGLE_REDIRECT_URI` is correct

### Missing Videos
- Check video files exist in `Assets/videos/`
- Verify episode `video_url` is correctly set in database

## 📝 License

This is an educational project created for ELECTIVE 1 & ELECTIVE 2 final projects.

## 👤 Author

**Carlos Alejo**
- GitHub: [@LosLejo](https://github.com/LosLejo)
- Email: carlos.alejo0603@gmail.com

---

**Built with ❤️ as a learning project**
