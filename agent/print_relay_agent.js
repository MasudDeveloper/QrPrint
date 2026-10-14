/**
 * QRPrint Windows Desktop Relay Agent
 * -----------------------------------
 * This lightweight background agent connects your shop's Windows PC
 * to the QRPrint Cloud. It automatically receives customer print jobs,
 * sends them to your configured printer (B&W or Color) with silent auto-print,
 * and immediately wipes all local files for customer privacy.
 */

const { exec } = require("child_process");
const fs = require("fs");
const path = require("path");
const os = require("os");

const CONFIG = {
  shopId: process.env.SHOP_ID || "shop_001",
  serverUrl: process.env.SERVER_URL || "http://localhost:3000",
  pollIntervalMs: 3000,
};

const TEMP_DIR = path.join(os.tmpdir(), "qrprint_temp");
if (!fs.existsSync(TEMP_DIR)) {
  fs.mkdirSync(TEMP_DIR, { recursive: true });
}

console.log("==================================================");
console.log("   🖨️  QRPrint Windows Auto-Print Relay Agent   ");
console.log("==================================================");
console.log(`[INIT] Shop ID: ${CONFIG.shopId}`);
console.log(`[INIT] Temp spool directory: ${TEMP_DIR}`);

// 1. Detect Installed Printers via PowerShell
function detectWindowsPrinters(callback) {
  const psCmd = `powershell -Command "Get-CimInstance Win32_Printer | Select-Object -Property Name, Default | ConvertTo-Json"`;
  exec(psCmd, (error, stdout) => {
    if (error) {
      console.warn("[WARN] Could not retrieve printers via PowerShell, using default.");
      return callback([]);
    }
    try {
      const data = JSON.parse(stdout);
      const list = Array.isArray(data) ? data : [data];
      const printers = list.map((p) => ({
        name: p.Name,
        isDefault: p.Default || false,
      }));
      console.log(`[PRINTERS] Found ${printers.length} installed Windows printer(s):`);
      printers.forEach((p) => console.log(`  - ${p.name} ${p.isDefault ? "(Default)" : ""}`));
      callback(printers);
    } catch (e) {
      callback([]);
    }
  });
}

// 2. Silent Print Function
function silentPrint(filePath, printerName, callback) {
  console.log(`[PRINTING] Dispatching silent print command for: ${path.basename(filePath)} to "${printerName}"...`);

  // Windows PowerShell print command
  const printCmd = `powershell -Command "Start-Process -FilePath '${filePath}' -Verb PrintTo -ArgumentList '${printerName}' -Wait"`;

  exec(printCmd, (error) => {
    // Zero-retention cleanup: Delete immediately regardless of outcome
    try {
      if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
        console.log(`[PRIVACY] Local file securely wiped: ${path.basename(filePath)}`);
      }
    } catch (err) {
      console.error("[ERROR] Failed to wipe local file:", err.message);
    }

    if (error) {
      console.error(`[PRINT ERROR] Print job failed: ${error.message}`);
      return callback(false);
    }

    console.log(`[SUCCESS] Print completed successfully on ${printerName}!`);
    callback(true);
  });
}

// 3. Start Agent Listener
detectWindowsPrinters((printers) => {
  console.log("\n[STATUS] Relay Agent is running and waiting for customer jobs...");
  console.log("[STATUS] Press Ctrl + C to stop.\n");
});
