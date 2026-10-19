@echo off
title PrintSheba AI Document Detection Microservice
echo ========================================================
echo  Starting PrintSheba AI Fast Document & NID Microservice
echo ========================================================
cd /d "%~dp0"

python -m pip install -r requirements.txt
echo.
echo [INFO] Starting Uvicorn Server on http://0.0.0.0:8000 ...
python -m uvicorn main:app --host 0.0.0.0 --port 8000 --reload
pause
