# Alphavel Validation

> Input validation with Laravel-style rules

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## ✨ Features

- ✅ **10+ validation rules** - required, email, min, max, etc
- 🎯 **Laravel-compatible** - Familiar syntax
- 🚀 **Fast** - Minimal overhead
- 📝 **Custom rules** - Extensible

## 📦 Installation

```bash
composer require alphavel/validation
```

## 🚀 Quick Start

```php
use Alphavel\Validation\Validator;

$validator = new Validator($request->all(), [
    'email' => 'required|email',
    'password' => 'required|min:8',
    'age' => 'required|integer|min:18',
]);

if ($validator->fails()) {
    return Response::json([
        'errors' => $validator->errors()
    ], 422);
}

$validated = $validator->validated();
```

## 📚 Documentation

**Full documentation**: https://github.com/alphavel/documentation/blob/master/packages/validation/README.md

## 📄 License

MIT License
