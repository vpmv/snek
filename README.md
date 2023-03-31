Snek
---

Simple Snake game built with VueJS 3 and Laravel 10 (Inertia) and TailwindCSS.

I've focussed on building out the front-end functionality as much as possible. Since time ran out, I let tooling like Docker slide for the time being.
The UI is very rudimentary and ugly.

# Features
- Retro style snake game 
- Different levels
- Player scores
- Level editor
- Ever-growing Snek

# Install & run
Since there's no Docker container yet, you have to use development mode and Sail to get things up and running.

```
cp .env.example .env 
composer install
npm install
vendor/bin/sail up -d
vendor/bin/sail php artisan migrate
vite build
```


# To do
- Docker container
- More levels
- UI/UX
