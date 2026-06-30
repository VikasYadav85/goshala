# SSH Config for Claude Code

Add to your ~/.ssh/config on the machine running Claude Code.
Replace [FILL IN] with actual values.

Host gsstg-server
    HostName 159.223.107.48
    User claude-server
    IdentityFile ~/.ssh/claude-server

## Server User Setup (run once as root)
adduser claude-server --disabled-password
usermod -aG docker claude-server
usermod -aG www-data claude-server

## Generate Claude's SSH key
ssh-keygen -t ed25519 -f ~/.ssh/claude-server -C "claude-code-access"
ssh-copy-id -i ~/.ssh/claude-server.pub claude-server@159.223.107.48

---

> NOTE (current reality, 2026-06): the live server is presently accessed as
> `root` over **password auth** (key auth is not yet configured). The
> `claude-server` dedicated-user + key setup above is the RECOMMENDED target
> state — it must be set up by the tech lead before the `/deploy`, `/monitor`,
> `/logs`, and `/db` slash commands (which assume `Host gsstg-server`) will work.
> App lives at `/var/www/html/goshala`; web user is `www-data`.
