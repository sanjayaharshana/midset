# OAuth Setup Instructions

## Xelenic OAuth Configuration

To enable OAuth authentication with Xelenic, add the following environment variables to your `.env` file:

```env
# Xelenic OAuth Configuration
XELENIC_CLIENT_ID=your_xelenic_client_id_here
XELENIC_CLIENT_SECRET=your_xelenic_client_secret_here
XELENIC_REDIRECT_URI=http://localhost:8000/callback
```

## Setup Steps

1. **Get OAuth Credentials**: Contact Xelenic to obtain your OAuth client ID and client secret
2. **Configure Redirect URI**: Set the redirect URI in your Xelenic OAuth application to: `http://your-domain.com/callback`
3. **Update Environment Variables**: Add the credentials to your `.env` file
4. **Test OAuth Flow**: Visit the login page and click "Login with Xelenic"

## OAuth Flow

The application implements the standard OAuth 2.0 authorization code flow:

1. User clicks "Login with Xelenic" button
2. Redirects to `https://xelenic.com/oauth/authorize` with required parameters
3. User authorizes the application on Xelenic
4. Xelenic redirects back to `/callback` with authorization code
5. Application exchanges code for access token
6. Application fetches user data using access token
7. User is logged in or created in the local database

## Files Modified

- `config/services.php` - Added Xelenic OAuth configuration
- `app/Http/Socialite/XelenicProvider.php` - Custom Socialite provider
- `app/Providers/AppServiceProvider.php` - Registered custom provider
- `app/Http/Controllers/AuthController.php` - Added OAuth methods
- `app/Models/User.php` - Added xelenic_id field
- `routes/web.php` - Added OAuth routes
- `resources/views/auth/login.blade.php` - Added OAuth button
- Database migration for xelenic_id column

