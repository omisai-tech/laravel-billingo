# Security Policy

## Reporting a Vulnerability

We take the security of this package seriously. If you discover a security vulnerability, please report it responsibly.

### How to Report

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please report them via email to: **security@omisai.com**

### What to Include

Please include the following information in your report:

- A description of the vulnerability
- Steps to reproduce the issue
- Potential impact of the vulnerability
- Any suggested fixes (if applicable)

### Response Timeline

- **Initial Response**: Within 48 hours, we will acknowledge receipt of your report
- **Status Update**: Within 7 days, we will provide an initial assessment
- **Resolution**: We aim to resolve critical issues within 30 days

### What to Expect

1. **Acknowledgment**: We will acknowledge your report within 48 hours
2. **Investigation**: We will investigate and validate the reported vulnerability
3. **Communication**: We will keep you informed of our progress
4. **Fix & Release**: Once fixed, we will release a patch and credit you (unless you prefer to remain anonymous)
5. **Disclosure**: We will coordinate with you on the timing of public disclosure

### Safe Harbor

We consider security research conducted in accordance with this policy to be:

- Authorized concerning any applicable anti-hacking laws
- Exempt from DMCA restrictions
- Conducted in good faith

We will not pursue legal action against researchers who:

- Act in good faith
- Avoid privacy violations
- Do not destroy data
- Give us reasonable time to fix issues before disclosure

## Security Best Practices

When using this package, please follow these security best practices:

### API Key Protection

```php
// ✅ DO: Use environment variables
BILLINGO_API_KEY=your-api-key-here

// ❌ DON'T: Hardcode API keys in your code
$config = ['api_key' => 'your-api-key-here'];
```

### Environment Configuration

```bash
# Never commit .env files
# Ensure .env is in your .gitignore
```

### Production Considerations

1. **Disable Debug Mode**: Set `BILLINGO_DEBUG=false` in production
2. **Use HTTPS**: Always use HTTPS in production environments
3. **Limit API Key Scope**: Use API keys with minimal required permissions
4. **Rotate Keys**: Regularly rotate your API keys
5. **Monitor Usage**: Monitor API usage for suspicious activity

### Laravel Security

Follow Laravel's security best practices:

- Keep Laravel and all dependencies up to date
- Use Laravel's built-in CSRF protection
- Validate and sanitize all user input
- Use prepared statements (Eloquent handles this automatically)

## Dependencies

This package relies on the following key dependencies:

- **GuzzleHTTP**: For HTTP requests
- **Laravel Framework**: For service container and configuration

We regularly update dependencies to include security patches. Please keep this package updated to the latest version.

## Changelog

Security-related changes will be documented in our releases with the `[Security]` tag.

## Contact

For security concerns: **security@omisai.com**

For general questions: Open an issue on [GitHub](https://github.com/omisai-tech/laravel-billingo/issues)
