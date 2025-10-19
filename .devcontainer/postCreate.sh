#!/usr/bin/env bash
set -e

# Composer optional (if you add packages later)
if ! command -v composer >/dev/null 2>&1; then
  echo "Composer not found (ok for vanilla PHP app)."
fi

# Permissions (uploads)
mkdir -p /workspace/uploads
chmod -R 777 /workspace/uploads || true

echo "Post-create complete."
