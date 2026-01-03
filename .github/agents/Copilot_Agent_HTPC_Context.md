# 🤖 Copilot Agent Context – HTPC/Homelab Environment

**Purpose:** This document provides comprehensive context for AI assistants (GitHub Copilot, Claude, etc.) working with the HTPC/homelab infrastructure and associated projects.

**Last Updated:** 2025-12-28

---

## 📋 Quick Reference

| Component | Value |
|-----------|-------|
| **Docker Host IP** | 192.168.10.243 |
| **Host OS** | Linux (Docker host) |
| **Network Subnet** | 192.168.10.0/24 |
| **Router** | Asus RT-AC87U |
| **Remote Access** | Tailscale VPN |
| **Primary Project** | PriceBuddy (Laravel + Filament + PostgreSQL) |

---

## 🏗️ System Architecture

### Infrastructure Layers

```
┌─────────────────────────────────────────┐
│         Internet / Public Wi-Fi         │
└──────────────┬──────────────────────────┘
               │
        [Tailscale Overlay]
               │
┌──────────────▼──────────────────────────┐
│      Asus RT-AC87U (192.168.10.1)      │
│            LAN Gateway / NAT            │
└──────────────┬──────────────────────────┘
               │
    192.168.10.0/24 LAN
               │
┌──────────────▼──────────────────────────┐
│    Docker Host (192.168.10.243)        │
│  ┌─────────────────────────────────┐   │
│  │  Docker Engine                  │   │
│  │  ├─ PriceBuddy (Laravel)        │   │
│  │  ├─ PostgreSQL                  │   │
│  │  ├─ Plex Media Server           │   │
│  │  ├─ Sonarr / Radarr             │   │
│  │  └─ Home Assistant              │   │
│  └─────────────────────────────────┘   │
└─────────────────────────────────────────┘
```

### Networking Details

- **LAN Subnet:** 192.168.10.0/24
- **Docker Host:** 192.168.10.243
- **Docker Networking:** Bridge mode, ports mapped to host IP
- **Remote Access:** Tailscale mesh VPN
  - Secure remote access to Docker services
  - No port forwarding required
  - Docker host configured as exit node candidate
  - Access from public Wi-Fi supported

---

## 🐳 Docker Services

### Core Media Stack

| Service | Purpose | Container Name |
|---------|---------|----------------|
| **Plex** | Media server | plex |
| **Sonarr** | TV series management | sonarr |
| **Radarr** | Movie management | radarr |
| **Home Assistant** | Smart home automation | homeassistant |

### PriceBuddy Stack

| Service | Purpose | Container Name | Port |
|---------|---------|----------------|------|
| **PriceBuddy** | Laravel application | pricebuddy | 8021 |
| **PostgreSQL** | Database | postgres | 5432 |

**Access URL:** http://192.168.10.243:8021

---

## 🎯 PriceBuddy Application

### Overview
- **Framework:** Laravel + Filament
- **Language:** PHP
- **Database:** PostgreSQL
- **GitHub:** https://github.com/jez500/pricebuddy
- **Purpose:** Product price tracking via web scraping

### Core Functionality
- Product URL scraping
- Store/domain management
- Price tracking over time
- Store auto-creation from URLs
- Multi-currency support

### Database Configuration

```yaml
Host: localhost (from host) / postgres (from containers)
Port: 5432
Database: pricebuddy
User: pricebuddy
Password: [configured in Docker environment]
Storage: Persistent Docker volume
```

### Applied Fixes

#### 1. Notification Data Type Fix (Issue #48)
**Problem:** Laravel notification serialization errors  
**Solution:** 
```sql
ALTER TABLE notifications
ALTER COLUMN data TYPE JSON
USING data::json;
```
**Status:** ✅ Applied and working

---

## 🔧 Development Workflow

### Primary Tools

1. **VSCode**
   - Primary editor for container code
   - Used for Laravel debugging
   - PR preparation
   - Remote SSH to 192.168.10.243 (optional)

2. **Docker CLI**
   - Container inspection
   - Database access
   - Laravel artisan commands

3. **PostgreSQL CLI**
   - Direct database queries
   - Schema inspection
   - Data validation

### Essential Commands

```bash
# Access PostgreSQL directly
docker exec -it postgres psql -U pricebuddy -d pricebuddy

# Laravel Tinker (REPL)
docker exec -it pricebuddy php /app/artisan tinker

# View Laravel logs
docker logs -f pricebuddy

# Restart PriceBuddy container
docker restart pricebuddy

# Access container shell
docker exec -it pricebuddy bash

# Run tests
docker exec -it pricebuddy php /app/vendor/bin/phpunit

# Clear Laravel cache
docker exec -it pricebuddy php /app/artisan cache:clear
```

---

## 🐛 Known Issues & Design Constraints

### Current Bugs

#### Store/Domain Matching Bug
**Severity:** High  
**Impact:** Product creation fails with domain mismatch errors

**Symptoms:**
- Stores saved with domains like `[{"domain": "jw.com.au"}, {"domain": "www.jw.com.au"}]`
- Product creation fails when full URLs supplied
- Error: "Domain does not belong to any stores"
- `scopeDomainFilter()` receiving null
- Duplicate store creation

**Root Cause:**
- URL vs domain normalization missing
- No consistent handling of `www.` prefix
- Domain extraction from full URLs not standardized

**Confirmed Behavior:**
- ✅ Store test scrape works
- ✅ Store exists in database
- ❌ Product creation fails due to domain mismatch

**Priority:** High – blocks core functionality

### Design Principles

When implementing fixes:

✅ **DO:**
- Prefer architectural fixes over workarounds
- Maintain PostgreSQL compatibility
- Ensure future DB engine compatibility
- Normalize domains once at entry point
- Avoid duplicate store creation
- Preserve existing functionality
- Make changes upstream-safe and PR-ready
- Write comprehensive tests

❌ **DON'T:**
- Implement quick hacks or patches
- Break existing functionality
- Introduce database-specific code
- Create technical debt
- Skip testing

---

## 🔐 Access & Authentication

### SSH Access
- **Host:** 192.168.10.243 (hostname: htpc)
- **Port:** 8022
- **User:** casa
- **Protocol:** SSH
- **Auth Options:**
  1. SSH key-based authentication (htpc-agent) - preferred
  2. Password authentication
  
**Note:** For AI assistant SSH access, consider:
- Creating dedicated service account
- Using SSH keys with restricted permissions
- Limiting access via SSH authorized_keys command restrictions

### Tailscale Access
- All remote connections via Tailscale mesh network
- No direct port forwarding required
- Secure access from any location

---

## 📚 Related Documentation

- **Main Context:** [HTPC_Network.md](HTPC_Network.md)
- **PriceBuddy Context:** [Copilot_Agent_PriceBuddy_Context.md](Copilot_Agent_PriceBuddy_Context.md)
- **PriceBuddy DB Fix:** [PriceBuddy/PriceBuddy_DB_Fix.pdf](PriceBuddy/PriceBuddy_DB_Fix.pdf)
- **GitHub Repository:** https://github.com/jez500/pricebuddy

---

## 🎯 Common Tasks

### Debugging Database Issues
1. Connect via psql: `docker exec -it postgres psql -U pricebuddy -d pricebuddy`
2. Check table schema: `\d+ table_name`
3. Query data: `SELECT * FROM stores WHERE domain @> '[{"domain": "example.com"}]';`
4. Check constraints: `\d+ stores`

### Testing Changes
1. Make code changes in VSCode
2. Restart container: `docker restart pricebuddy`
3. Check logs: `docker logs -f pricebuddy`
4. Run tests: `docker exec -it pricebuddy php /app/vendor/bin/phpunit`
5. Validate via Tinker: `docker exec -it pricebuddy php /app/artisan tinker`

### Creating Pull Requests
1. Create feature branch
2. Implement changes following design principles
3. Write/update tests
4. Run full test suite
5. Update documentation
6. Commit with descriptive messages
7. Push and create PR
8. Reference related issues

---

## 💡 Tips for AI Assistants

1. **Always check existing code** before making changes
2. **Run tests** after modifications
3. **Validate database changes** via psql
4. **Check logs** for runtime errors
5. **Follow Laravel conventions**
6. **Maintain Filament compatibility**
7. **Consider PostgreSQL JSON operations** for domain arrays
8. **Test with real URLs** from supported stores
9. **Document breaking changes**
10. **Prefer idiomatic Laravel/PHP** over clever hacks

---

## 🚀 Quick Start for New Sessions

```bash
# 1. Verify Docker services running
ssh user@192.168.10.243
docker ps

# 2. Check PriceBuddy logs
docker logs --tail 50 pricebuddy

# 3. Verify database connectivity
docker exec -it postgres psql -U pricebuddy -d pricebuddy -c "SELECT version();"

# 4. Test Laravel
docker exec -it pricebuddy php /app/artisan --version

# 5. Access application
# Open browser: http://192.168.10.243:8021
```

---

**Version:** 1.0  
**Maintainer:** System Administrator  
**Support:** See GitHub Issues at jez500/pricebuddy
