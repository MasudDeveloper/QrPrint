@echo off
title PrintSheba Windows Auto-Print Agent
color 0A
echo ========================================================
echo   PrintSheba Windows Auto-Print Agent (প্রিন্ট সেবা)
echo ========================================================

if exist "PrintShebaAgent.exe" (
    echo Starting native Windows PrintShebaAgent.exe...
    start "" "PrintShebaAgent.exe"
    exit
)

echo Starting fallback Node.js listener...
node print_relay_agent.js
pause
