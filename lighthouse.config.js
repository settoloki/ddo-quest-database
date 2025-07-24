module.exports = {
  ci: {
    collect: {
      numberOfRuns: 3,
      startServerCommand: 'php artisan serve --port=8000',
      startServerReadyPattern: 'started',
      url: [
        'http://localhost:8000',
        'http://localhost:8000/quests',
        'http://localhost:8000/login',
        'http://localhost:8000/register'
      ]
    },
    assert: {
      assertions: {
        'categories:performance': ['warn', { minScore: 0.8 }],
        'categories:accessibility': ['error', { minScore: 0.9 }],
        'categories:best-practices': ['warn', { minScore: 0.9 }],
        'categories:seo': ['warn', { minScore: 0.8 }],
        'categories:pwa': 'off'
      }
    },
    upload: {
      target: 'temporary-public-storage'
    }
  }
};
