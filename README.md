# Flinders Uni Skill Share (FUSS)

A full-stack PHP/MySQL web app for students to exchange skills and services using FUSScredits.

## 🚀 Features

- Student registration/login with Flinders email validation
- Profile management with bio, degree, and profile picture
- Skill offering/requesting with categories
- Transaction history and FUSScredit balance
- Secure authentication and input validation

## 🛠 Tech Stack

- PHP (Vanilla)
- MySQL (Relational DB)
- HTML/CSS/JS (No frameworks)

## 📦 Setup Instructions

1. Install [XAMPP](https://www.apachefriends.org/index.html)
2. Start Apache and MySQL
3. Place project in `htdocs/fuss-project`
4. Create database `flinders` in phpMyAdmin
5. Run `db/schema.sql` to create tables
6. Access app at `http://localhost/fuss-project`

## 🔐 Security Notes

- Passwords hashed with `password_hash()`
- Inputs validated and sanitized
- Sessions used for authentication

## 📄 License
##
MIT
