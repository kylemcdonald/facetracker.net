# facetracker.net

This repository contains the contents of the [facetracker.net](https://facetracker.net) website.

The site is fully static. The pages were rendered from the original PHP templates (last PHP version: commit `3576573`), and old `.php` URLs are redirected via `_redirects`. It is hosted on Cloudflare Pages:

```
wrangler pages deploy . --project-name facetracker-net --branch master
```
