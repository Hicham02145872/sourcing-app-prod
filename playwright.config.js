
import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: './playwright-tests',
  use: {
    baseURL: 'http://localhost:8000',
    trace: 'on-first-retry',
  },
  webServer: {
    command: 'php artisan serve',
    url: 'http://localhost:8000',
    reuseExistingServer: !process.env.CI,
  },
});
