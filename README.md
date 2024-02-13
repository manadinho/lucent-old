<div align="center">
  
![Lucent](lucent-logo-light.png)

</div>

## Introduction

Welcome to **Lucent** – the Open Source, Self Hosted error tracking service tailored for Laravel applications. Drawing inspiration from esteemed services such as Flare and Sentry, Lucent offers the freedom and flexibility of open-source software, combined with the robustness required for error logging.

With Lucent, you can take charge of monitoring your Laravel applications by hosting your own error tracking server. Say goodbye to the constraints of paid services and embrace the limitless potential of tracking numerous Laravel projects without any additional cost.

## Features

- **Multi-Project Management:** Host Lucent on your server and start tracking an unlimited number of Laravel projects.
- **Team Collaboration:** Create multiple teams, add members, and assign roles with ease. Lucent supports 'User' and 'Admin' roles for nuanced access control.
- **Project Association:** Effortlessly create and associate projects with teams, streamlining the error management process.
- **Time-Frame Filtering:** View errors within specific time frames to pinpoint issues efficiently.
- **Error Handling:** Gain control over your errors by snoozing, deleting, or marking them as resolved as per your operational flow.



## Installation
Clone the repository
```bash
git clone https://github.com/manadinho/lucent.git
```
Install composer dependencies
```bash
composer install
```
Copy .env.example to create .env file
```bash
cp .env.example .env
```
Generate APP_KEY
```bash
php artisan key:generate
```
Setup database connection and run migrations
```bash
php artisan migrate
```
Run seeder to seed superadmin data
```bash
php artisan db:seed
```
Publish assets for bladewindUI
```bash
php artisan vendor:publish --provider="Mkocansey\Bladewind\BladewindServiceProvider" --tag=bladewind-public --force
```
Install npm dependencies
```bash
npm install
```
Compile npm packages
```bash
npm run build
```
Use these credentials to login as Super Admin

**EMAIL:** superadmin@lucent.com

**PASSWORD:** password
## Next Step

Your Lucent App is now operational and ready to capture exceptions for your projects. The next step involves integrating the Lucent-Package into your project to enable the logging of exceptions into the Lucent App. You can find the Lucent-Package here: https://github.com/manadinho/lucent-package.
