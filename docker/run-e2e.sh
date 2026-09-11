#!/bin/bash
# =============================================================================
# run-e2e.sh — Run the full E2E test suite
# Usage: bash docker/run-e2e.sh
# =============================================================================
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

echo "=========================================="
echo " SmartSourcing E2E Test Runner"
echo "=========================================="
echo ""

cd "$PROJECT_DIR"

# Step 1: Build and start containers
echo "[1/6] Building and starting containers..."
docker compose -f docker-compose.testing.yml --env-file .env.testing down -v 2>/dev/null || true
docker compose -f docker-compose.testing.yml --env-file .env.testing up -d --build

# Step 2: Wait for services to be healthy
echo "[2/6] Waiting for services to be ready..."
sleep 15

# Step 3: Seed the database
echo "[3/6] Seeding test database..."
docker compose -f docker-compose.testing.yml exec -T app bash /var/www/html/docker/test-seed.sh

# Step 4: Verify app is responding
echo "[4/6] Verifying app health..."
for i in $(seq 1 30); do
  if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/eng | grep -q "200\|302"; then
    echo "  App is healthy!"
    break
  fi
  echo "  Waiting for app... ($i/30)"
  sleep 2
done

# Step 5: Run Playwright tests
echo "[5/6] Running Playwright tests..."
docker compose -f docker-compose.testing.yml run --rm playwright
TEST_EXIT_CODE=$?

# Step 6: Collect results
echo "[6/6] Collecting test results..."
mkdir -p tests/e2e/results
docker compose -f docker-compose.testing.yml cp ss-test-playwright:/app/tests/e2e/results/ tests/e2e/results/ 2>/dev/null || true

# Print summary
echo ""
echo "=========================================="
echo " Test Results Summary"
echo "=========================================="

if [ $TEST_EXIT_CODE -eq 0 ]; then
  echo " All tests PASSED!"
else
  echo " Some tests FAILED (exit code: $TEST_EXIT_CODE)"
  echo ""
  echo " Check the HTML report:"
  echo "   tests/e2e/results/html/index.html"
fi

# Cleanup
echo ""
echo "Cleaning up containers..."
docker compose -f docker-compose.testing.yml down -v

exit $TEST_EXIT_CODE
