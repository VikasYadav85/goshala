# Access & Credentials Guide
How to get access to everything needed for this project.

## GitHub Repository
- Repo: git@github.com:VikasYadav85/GSSTG.git
- Access: Ask [FILL IN tech lead name] to add you as collaborator
- Setup SSH key: https://docs.github.com/en/authentication/connecting-to-github-with-ssh
- After access granted, run:
  git clone git@github.com:VikasYadav85/GSSTG.git
  git config user.name "Your Name"
  git config user.email "your@email.com"

## Environment Variables
- Copy .env.example to .env
- Request actual values from tech lead via [FILL IN secure channel — NOT Slack/email]
- Required variables are documented in .env.example with descriptions
- NEVER share .env files via email, Slack, or any unencrypted channel

## Deployment / Server Access
- Deployment tool: Manual — direct SSH file-mirroring + `php artisan` (no Dokploy/Vercel/CI)
- Dashboard URL: [FILL IN — DigitalOcean droplet console]
- Access: Request from tech lead
- Deploy command: Use /deploy in Claude Code (has built-in safety protocol)

### Server
| Server | Host/IP | SSH User | Purpose | Who Can Access |
|--------|---------|----------|---------|----------------|
| gsstg-server | 159.223.107.48 | claude-server (target) / root (current) | Live server (production) | All developers |

> App path on server: `/var/www/html/goshala`. Web user: `www-data`. PHP 8.2-fpm + nginx.
> DigitalOcean blocks outbound SMTP ports (25/465/587) — only 443 is open.

### SSH Setup for Claude Code
See docs/SSH_CONFIG.md for full setup instructions.
1. Generate SSH key: ssh-keygen -t ed25519 -f ~/.ssh/claude-server -C "claude-code-access"
2. Send your PUBLIC key (~/.ssh/claude-server.pub) to tech lead
3. Tech lead adds it to the server's authorized_keys
4. Test connection: ssh gsstg-server "echo connected"
5. Claude Code will use your local SSH key automatically — no extra config needed

### Deploy Flow (via Claude Code)
- /deploy — deploys to server autonomously (auto-fix loop with circuit breaker)
- /test-live — tests against live server
- /monitor — checks server health and resources

## Third-Party Services (API Keys in .env)
List each service and how to get access if needed:
- Razorpay (payment gateway): [FILL IN — who manages the Razorpay account]. Live keys currently placeholders; only UPI active.
- Gmail SMTP (receipt email): account radheradhe7266@gmail.com — app password managed by [FILL IN]. Currently blocked by DO SMTP firewall.
- UPI (donations): VPA 7266945885@ptaxis (Gopal Seva Samarpan Trust) — managed by [FILL IN]

## Database Access
- Development DB: SQLite at database/database.sqlite (no connection string needed)
- Staging DB: [FILL IN — or "none"]
- Production DB: MySQL `gopal_seva` on 159.223.107.48 (creds in server .env, DB_* vars)
- Backup command: mysqldump -u[user] -p gopal_seva > backup.sql
- Migration command: php artisan migrate
- Use /db in Claude Code for all database operations — it has backup-first safety protocol

## Who to Contact
- Tech Lead: [FILL IN name + contact]
- DevOps / Server: [FILL IN name + contact]
- Project Manager: [FILL IN name + contact]

## Security Reminders
- Each developer uses their OWN SSH key — never share keys
- Rotate any credential immediately if you suspect it's compromised
- Never commit .env, private keys, or secrets to git
- Use a password manager (1Password, Bitwarden) for shared team credentials
- When migrating to production server, generate ALL new credentials — never reuse dev credentials
