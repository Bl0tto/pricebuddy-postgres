🖥️ HTPC / Homelab Docker Environment – Baseline Summary
1. Host System
Primary Docker Host

Device: Dedicated HTPC / PC

OS: Linux (Docker host)

LAN IP: 192.168.10.243

Role:

Runs all Docker containers

Acts as core homelab services node

Candidate / active Tailscale exit node

2. Network Topology
Router

Model: Asus RT-AC87U

Function:

LAN gateway

NAT to internet

No advanced VLAN routing performed here

LAN

Subnet: 192.168.10.0/24

Docker Host: 192.168.10.243

Docker Networking:

Services exposed via Docker bridge networking

Ports mapped directly to host IP

3. Remote Access & Overlay Networking
Tailscale

Installed on:

Docker Host (192.168.10.243)

Remote devices (incl. Raspberry Pi 4, public Wi-Fi clients)

Usage:

Secure remote access to Docker services

Avoids port forwarding

Supports access from public Wi-Fi

Exit Node:

Docker host may be configured as Tailscale exit node

Allows remote devices to route internet traffic via home LAN

4. Core Docker Services
Media & Home Automation Stack
Service	Purpose
Plex	Media server
Sonarr	TV series management
Radarr	Movie management
Home Assistant	Smart home automation

All services run as Docker containers on the same host.

5. PriceBuddy Stack (Self-Hosted)
Application

PriceBuddy (Laravel + Filament)

Access: Via mapped Docker port (e.g. http://192.168.10.243:8021)

Usage:

Product scraping

Store/domain management

Price tracking

Database

PostgreSQL

Container Name: postgres

Database: pricebuddy

User: pricebuddy

Storage: Persistent Docker volume

Known DB Fix Applied
ALTER TABLE notifications
ALTER COLUMN data TYPE JSON
USING data::json;


Fixes Laravel notification serialization errors (GitHub Issue #48)

6. Known Application Issues (PriceBuddy)
Store / Domain Matching Bug

Stores saved with domains like:

[
  {"domain": "jw.com.au"},
  {"domain": "www.jw.com.au"}
]


Bug:

Product creation fails when full URLs are supplied

Domain matching does not normalize URLs

Causes:

Duplicate store creation

“Domain does not belong to any stores”

scopeDomainFilter() receiving null

Confirmed Behavior

Store test scrape works

Store exists in DB

Product creation fails due to domain mismatch

Root cause: URL vs domain normalization missing

7. Tooling & Workflow
Development / Debugging

VSCode:

Primary editor for container code

Used for Laravel debugging and PR preparation

CLI / Docker Exec:

PostgreSQL inspection via psql

Laravel debugging via php artisan tinker

Example Commands
docker exec -it postgres psql -U pricebuddy -d pricebuddy
docker exec -it pricebuddy php /app/artisan tinker

8. Design Constraints & Goals

Prefer correct architectural fixes, not workarounds

Maintain:

PostgreSQL compatibility

Future DB engine compatibility

Fixes should:

Normalize domains once

Avoid duplicate stores

Preserve existing functionality

Changes intended to be:

Upstream-safe

PR-ready

Fully tested

9. Intended Use of This Document

This document is designed to:

Rapidly onboard AI assistants

Provide full system context in one prompt

Avoid repeated explanations

Serve as a Notion-importable baseline

Support:

Debugging

Architecture reviews

PR preparation

Docker / network reasoning