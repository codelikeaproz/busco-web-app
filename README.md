# busco-web-app
Website Application For Busco Sugar Milling Co Inc. during my Ojt, propose (Side-project)

## Deploying to Railway
The Laravel app lives in the **`busco-web-app`** folder. When connecting this repo to Railway:
1. In your Railway service → **Settings** → **Source**, set **Root Directory** to **`busco-web-app`**.
2. Set **Build Command** to: `composer install --no-dev && npm run build`
3. Add env vars: `APP_KEY`, `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`, `LOG_CHANNEL=stderr`
4. After deploy, go to **Networking** → **Generate Domain**, then set `APP_URL` to that URL.
