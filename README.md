# sessions

[![Build Status](https://travis-ci.org/ronanchilvers/sessions.svg?branch=master)](https://travis-ci.org/ronanchilvers/sessions)
[![codecov](https://codecov.io/gh/ronanchilvers/sessions/branch/master/graph/badge.svg)](https://codecov.io/gh/ronanchilvers/sessions)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![Stability](https://img.shields.io/badge/stability-alpha-red.svg?longCache=true&style=flat-square)

This is a simple package that provides a PSR7 compatible session handling mechanism. It supports different storage backends. Currently there are two implemented - a native session wrapper and a backend supporting encrypted cookie based storage.

## Installation

The easiest way to install is via composer:

```
composer install ronanchilvers/sessions
```

## Usage

Here's a simple usage example using the native storage backend:

```php
$storage = new \Ronanchilvers\Sessions\Storage\NativeStorage();
$session = new \Ronanchilvers\Sessions\Session($storage);

$session->set('username', 'ronan');

$username = $session->get('username');
```
