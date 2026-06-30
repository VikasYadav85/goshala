# Lessons Learned
Rules added here prevent repeated mistakes. Each rule was born from an actual error.

## Code Patterns
(none yet)

## Common Pitfalls
(none yet)

## Testing
(none yet)

## Bulk Operations
- NEVER run sed -i on files without checking the file list first
- NEVER run find -exec on directories without excluding: .claude/skills/, node_modules/, .git/, dist/, build/
- ALWAYS show the exact file list before any operation touching 5+ files
- ALWAYS exclude lock files (package-lock.json, bun.lock, yarn.lock) from bulk modifications
