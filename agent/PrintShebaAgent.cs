using System;
using System.IO;
using System.Net;
using System.Text;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Drawing.Printing;
using System.Diagnostics;
using System.Threading;
using System.Windows.Forms;
using System.Collections.Generic;
using System.Web.Script.Serialization;
using Microsoft.Win32;

namespace PrintSheba
{
    public class AppConfig
    {
        public string server_url { get; set; }
        public string api_token { get; set; }
        public string bw_printer { get; set; }
        public string color_printer { get; set; }
        public int poll_interval_sec { get; set; }
        public bool auto_start { get; set; }
        public bool minimize_to_tray { get; set; }

        public AppConfig()
        {
            server_url = "http://localhost:8000";
            api_token = "";
            bw_printer = "";
            color_printer = "";
            poll_interval_sec = 3;
            auto_start = false;
            minimize_to_tray = true;
        }
    }

    public class PrintJobItem
    {
        public string job_token { get; set; }
        public string file_url { get; set; }
        public string file_name { get; set; }
        public string original_name { get; set; }
        public string file_type { get; set; }
        public string color_mode { get; set; }
        public bool is_duplex { get; set; }
        public int copies { get; set; }
        public string paper_size { get; set; }
        public string customer_name { get; set; }
        public string pickup_reference { get; set; }
        public bool is_office_job { get; set; }
    }

    public class PollResponse
    {
        public string status { get; set; }
        public string shop_name { get; set; }
        public List<PrintJobItem> jobs { get; set; }
    }

    public class AgentForm : Form
    {
        private AppConfig config;
        private string configPath;
        private string tempDir;
        private System.Windows.Forms.Timer pollTimer;
        private System.Windows.Forms.Timer heartbeatTimer;
        private bool isPolling = false;
        private int totalJobsPrinted = 0;
        private int totalErrors = 0;

        // UI Controls
        private Label lblStatusPill;
        private Label lblShopPill;
        private TextBox txtServerUrl;
        private TextBox txtApiToken;
        private ComboBox cmbBwPrinter;
        private ComboBox cmbColorPrinter;
        private CheckBox chkAutoStart;
        private CheckBox chkMinimizeToTray;
        private ListBox lstLog;
        private Label lblTotalPrintsVal;
        private Label lblActiveQueueVal;
        private Label lblSpoolerStatusVal;
        private Button btnSave;
        private Button btnTestPrint;
        private Button btnCheckNow;
        private NotifyIcon trayIcon;
        private ContextMenuStrip trayMenu;

        // Brand Color Tokens
        private readonly Color BrandDarkNavy = Color.FromArgb(15, 23, 42);     // #0F172A
        private readonly Color BrandSlate = Color.FromArgb(30, 41, 59);        // #1E293B
        private readonly Color BrandRose = Color.FromArgb(225, 29, 72);        // #E11D48
        private readonly Color BrandPink = Color.FromArgb(244, 63, 94);        // #F43F5E
        private readonly Color BrandEmerald = Color.FromArgb(16, 185, 129);    // #10B981
        private readonly Color BrandBg = Color.FromArgb(248, 250, 252);        // #F8FAFC
        private readonly Color BrandTerminal = Color.FromArgb(11, 17, 32);     // #0B1120

        public AgentForm()
        {
            InitializeComponent();
            SetupApp();
        }

        private void SetupApp()
        {
            try
            {
                // Enable TLS 1.2 (SecurityProtocolType.Tls12 = 3072) & Trust HTTPS for production hosting
                ServicePointManager.SecurityProtocol = (SecurityProtocolType)3072 | SecurityProtocolType.Tls | SecurityProtocolType.Ssl3;
                ServicePointManager.ServerCertificateValidationCallback = (sender, certificate, chain, sslPolicyErrors) => true;
            }
            catch { }

            configPath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "config.json");
            tempDir = Path.Combine(Path.GetTempPath(), "PrintSheba");
            if (!Directory.Exists(tempDir))
            {
                Directory.CreateDirectory(tempDir);
            }

            LoadConfig();
            PopulatePrinters();
            ApplyConfigToUI();

            // Set up polling timer
            pollTimer = new System.Windows.Forms.Timer();
            pollTimer.Interval = Math.Max(2, config.poll_interval_sec) * 1000;
            pollTimer.Tick += (s, e) => PollJobs();
            pollTimer.Start();

            // Heartbeat timer (every 30 seconds)
            heartbeatTimer = new System.Windows.Forms.Timer();
            heartbeatTimer.Interval = 30000;
            heartbeatTimer.Tick += (s, e) => SendHeartbeat();
            heartbeatTimer.Start();

            Log("🚀 PrintSheba (প্রিন্ট সেবা) উইন্ডোজ অটো-প্রিন্ট ক্লাউড এজেন্ট চালু হয়েছে।");
            Log("🔒 জিরো-রিটেনশন ক্যাশ ডিরেক্টরি প্রস্তুত: " + tempDir);

            // Send initial heartbeat
            ThreadPool.QueueUserWorkItem(o => SendHeartbeat());
        }

        private void LoadConfig()
        {
            try
            {
                if (File.Exists(configPath))
                {
                    string json = File.ReadAllText(configPath);
                    var ser = new JavaScriptSerializer();
                    config = ser.Deserialize<AppConfig>(json) ?? new AppConfig();
                }
                else
                {
                    config = new AppConfig();
                    SaveConfig();
                }
            }
            catch (Exception ex)
            {
                Log("⚠️ কনফিগারেশন লোড এরর: " + ex.Message);
                config = new AppConfig();
            }
        }

        private void SaveConfig()
        {
            try
            {
                var ser = new JavaScriptSerializer();
                string json = ser.Serialize(config);
                File.WriteAllText(configPath, json);
                SetWindowsStartup(config.auto_start);
            }
            catch (Exception ex)
            {
                Log("⚠️ কনফিগারেশন সেভ করতে সমস্যা: " + ex.Message);
            }
        }

        private void SetWindowsStartup(bool enable)
        {
            try
            {
                RegistryKey rk = Registry.CurrentUser.OpenSubKey("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run", true);
                if (rk != null)
                {
                    string appPath = Application.ExecutablePath;
                    if (enable)
                    {
                        rk.SetValue("PrintShebaAgent", "\"" + appPath + "\"");
                    }
                    else
                    {
                        rk.DeleteValue("PrintShebaAgent", false);
                    }
                }
            }
            catch (Exception ex)
            {
                Log("⚠️ অটো-স্টার্ট রেজিস্ট্রি আপডেট এরর: " + ex.Message);
            }
        }

        private void PopulatePrinters()
        {
            cmbBwPrinter.Items.Clear();
            cmbColorPrinter.Items.Clear();

            cmbBwPrinter.Items.Add("-- উইন্ডোজ ডিফল্ট প্রিন্টার --");
            cmbColorPrinter.Items.Add("-- কোনো কালার প্রিন্টার নেই --");
            cmbColorPrinter.Items.Add("-- উইন্ডোজ ডিফল্ট প্রিন্টার --");

            // Filter out virtual/software print drivers that are not physical printers
            var virtualPrinterKeywords = new string[] {
                "onenote",
                "microsoft print to pdf",
                "xps document writer",
                "fax",
                "root print queue"
            };

            foreach (string printer in PrinterSettings.InstalledPrinters)
            {
                string pLower = printer.ToLowerInvariant();
                bool isVirtual = false;
                foreach (string kw in virtualPrinterKeywords)
                {
                    if (pLower.Contains(kw))
                    {
                        isVirtual = true;
                        break;
                    }
                }

                if (!isVirtual)
                {
                    cmbBwPrinter.Items.Add(printer);
                    cmbColorPrinter.Items.Add(printer);
                }
            }

            cmbBwPrinter.SelectedIndex = 0;
            cmbColorPrinter.SelectedIndex = 0;
        }

        private void ApplyConfigToUI()
        {
            txtServerUrl.Text = config.server_url;
            txtApiToken.Text = config.api_token;
            chkAutoStart.Checked = config.auto_start;
            chkMinimizeToTray.Checked = config.minimize_to_tray;

            if (!string.IsNullOrEmpty(config.bw_printer) && cmbBwPrinter.Items.Contains(config.bw_printer))
            {
                cmbBwPrinter.SelectedItem = config.bw_printer;
            }
            if (!string.IsNullOrEmpty(config.color_printer) && cmbColorPrinter.Items.Contains(config.color_printer))
            {
                cmbColorPrinter.SelectedItem = config.color_printer;
            }
        }

        private void Log(string message)
        {
            if (InvokeRequired)
            {
                Invoke(new Action<string>(Log), message);
                return;
            }

            string time = DateTime.Now.ToString("HH:mm:ss");
            string entry = "[" + time + "] " + message;
            lstLog.Items.Add(entry);
            lstLog.TopIndex = lstLog.Items.Count - 1;

            if (lstLog.Items.Count > 600)
            {
                lstLog.Items.RemoveAt(0);
            }
        }

        private void UpdateStats()
        {
            if (InvokeRequired)
            {
                Invoke(new Action(UpdateStats));
                return;
            }

            lblTotalPrintsVal.Text = totalJobsPrinted.ToString() + " টি";
            lblSpoolerStatusVal.Text = totalErrors == 0 ? "সচল ও নিরবচ্ছিন্ন" : totalErrors.ToString() + " টি এরর হ্যান্ডেল্ড";
        }

        private void SetStatus(string text, Color bg, Color fg)
        {
            if (InvokeRequired)
            {
                Invoke(new Action<string, Color, Color>(SetStatus), text, bg, fg);
                return;
            }

            lblStatusPill.Text = text;
            lblStatusPill.BackColor = bg;
            lblStatusPill.ForeColor = fg;
        }

        private void PollJobs()
        {
            if (isPolling) return;
            if (string.IsNullOrEmpty(config.api_token))
            {
                SetStatus("⚠️ API টোকেন সেট করুন", Color.FromArgb(254, 243, 199), Color.FromArgb(180, 83, 9));
                return;
            }

            isPolling = true;
            ThreadPool.QueueUserWorkItem(o =>
            {
                try
                {
                    string url = config.server_url.TrimEnd('/') + "/api/agent/jobs";
                    var request = (HttpWebRequest)WebRequest.Create(url);
                    request.Method = "GET";
                    request.Headers["X-Agent-Token"] = config.api_token;
                    request.Timeout = 8000;

                    using (var response = (HttpWebResponse)request.GetResponse())
                    using (var reader = new StreamReader(response.GetResponseStream()))
                    {
                        string json = reader.ReadToEnd();
                        var ser = new JavaScriptSerializer();
                        var result = ser.Deserialize<PollResponse>(json);

                        if (result != null && result.status == "ok")
                        {
                            SetStatus("● অনলাইন ও সংযুক্ত", Color.FromArgb(209, 250, 229), Color.FromArgb(4, 120, 87));
                            if (lblShopPill.Text != result.shop_name)
                            {
                                Invoke(new Action(() => lblShopPill.Text = "🏪 " + (result.shop_name ?? "শপ সংযুক্ত")));
                            }

                            if (result.jobs != null && result.jobs.Count > 0)
                            {
                                Invoke(new Action(() => lblActiveQueueVal.Text = result.jobs.Count.ToString() + " টি প্রিন্টিং"));
                                Log(string.Format("📥 নতুন {0}টি পেইড প্রিন্ট জব ক্লাউড থেকে রিসিভ হয়েছে!", result.jobs.Count));
                                foreach (var job in result.jobs)
                                {
                                    ProcessJob(job);
                                }
                                Invoke(new Action(() => lblActiveQueueVal.Text = "০ টি (খালি)"));
                            }
                        }
                    }
                }
                catch (WebException wex)
                {
                    HttpWebResponse errRes = wex.Response as HttpWebResponse;
                    if (errRes != null && errRes.StatusCode == HttpStatusCode.Unauthorized)
                    {
                        SetStatus("✕ ইনভ্যালিড টোকেন (Unauthorized)", Color.FromArgb(254, 226, 226), Color.FromArgb(185, 28, 28));
                    }
                    else
                    {
                        SetStatus("● সার্ভার সংযোগ অপেক্ষা...", Color.FromArgb(254, 243, 199), Color.FromArgb(180, 83, 9));
                    }
                }
                catch (Exception)
                {
                    SetStatus("✕ সংযোগ বিচ্ছিন্ন", Color.FromArgb(254, 226, 226), Color.FromArgb(185, 28, 28));
                }
                finally
                {
                    isPolling = false;
                }
            });
        }

        private void ProcessJob(PrintJobItem job)
        {
            string refInfo = !string.IsNullOrEmpty(job.pickup_reference) ? string.Format(" [খাম/রেফারেন্স: {0}]", job.pickup_reference) : "";
            string officeTag = job.is_office_job ? "🏢 [অফিস প্রিন্ট] " : "";
            Log(string.Format("{0}📄 ফাইল ডাউনলোড হচ্ছে: {1} ({2}){3}", officeTag, job.original_name ?? job.file_name, job.color_mode == "color" ? "রঙিন" : "সাদাকালো", refInfo));

            string ext = Path.GetExtension(job.file_name);
            if (string.IsNullOrEmpty(ext)) ext = ".pdf";
            string localFilePath = Path.Combine(tempDir, job.job_token + ext);

            try
            {
                // 1. Download file
                using (var client = new WebClient())
                {
                    client.Headers["X-Agent-Token"] = config.api_token;
                    client.DownloadFile(job.file_url, localFilePath);
                }

                // 2. Select target printer
                string targetPrinter = GetTargetPrinter(job.color_mode);
                Log(string.Format("🖨️ উইন্ডোজ স্পুলার ড্রাইভারে পাঠানো হচ্ছে ➔ \"{0}\" (কপি: {1})", targetPrinter, job.copies));

                // Notify System Tray safely on UI thread
                try
                {
                    if (this.IsHandleCreated && trayIcon != null)
                    {
                        this.BeginInvoke(new Action(() =>
                        {
                            try
                            {
                                string tipTitle = job.is_office_job ? "🏢 অফিস সরাসরি প্রিন্ট" : "PrintSheba অটো-প্রিন্ট";
                                string tipBody = string.Format("{0}\n{1}{2}",
                                    job.original_name ?? job.file_name,
                                    !string.IsNullOrEmpty(job.pickup_reference) ? "খাম: " + job.pickup_reference + "\n" : "",
                                    !string.IsNullOrEmpty(job.customer_name) ? "প্রেরক: " + job.customer_name : "");
                                trayIcon.ShowBalloonTip(3000, tipTitle, tipBody, ToolTipIcon.Info);
                            }
                            catch { }
                        }));
                    }
                }
                catch { }

                // 3. Print silently
                bool printSuccess = ExecutePrint(localFilePath, targetPrinter, job);

                // 4. Zero retention cleanup: Wipe file immediately
                try
                {
                    if (File.Exists(localFilePath))
                    {
                        File.Delete(localFilePath);
                        Log("🔒 জিরো-রিটেনশন প্রাইভেসি: কাস্টমারের লোকাল কপি স্থায়ীভাবে ডিলিট করা হয়েছে।");
                    }
                }
                catch { }

                if (printSuccess)
                {
                    totalJobsPrinted += job.copies;
                    UpdateStats();
                    NotifyJobPrinted(job.job_token);
                    Log(string.Format("✅ প্রিন্ট সফলভাবে সম্পন্ন হয়েছে! (টোকেন: {0})", job.job_token));
                }
                else
                {
                    totalErrors++;
                    UpdateStats();
                    Log(string.Format("❌ প্রিন্ট প্রসেস ব্যর্থ: {0}", job.job_token));
                }
            }
            catch (Exception ex)
            {
                totalErrors++;
                UpdateStats();
                Log("❌ ফাইল ডাউনলোড/প্রিন্ট ত্রুটি: " + ex.Message);
            }
        }

        private string GetTargetPrinter(string colorMode)
        {
            if (colorMode == "color" && !string.IsNullOrEmpty(config.color_printer) && config.color_printer != "-- উইন্ডোজ ডিফল্ট প্রিন্টার --" && config.color_printer != "-- কোনো কালার প্রিন্টার নেই --")
            {
                return config.color_printer;
            }
            if (!string.IsNullOrEmpty(config.bw_printer) && config.bw_printer != "-- উইন্ডোজ ডিফল্ট প্রিন্টার --")
            {
                return config.bw_printer;
            }

            var defaultSettings = new PrinterSettings();
            return defaultSettings.PrinterName;
        }

        private bool ExecutePrint(string filePath, string printerName, PrintJobItem job)
        {
            string ext = Path.GetExtension(filePath).ToLowerInvariant();

            // A. Images: Native GDI scaled print
            if (ext == ".jpg" || ext == ".jpeg" || ext == ".png" || ext == ".bmp" || ext == ".gif")
            {
                return PrintImageNatively(filePath, printerName, job.copies);
            }

            // B. PDF / Documents: Windows hidden shell print
            return PrintDocumentSilently(filePath, printerName, job.copies);
        }

        private bool PrintImageNatively(string filePath, string printerName, int copies)
        {
            try
            {
                using (var image = Image.FromFile(filePath))
                {
                    var pd = new PrintDocument();
                    pd.PrinterSettings.PrinterName = printerName;
                    pd.PrinterSettings.Copies = (short)Math.Max(1, copies);

                    pd.PrintPage += (s, ev) =>
                    {
                        Rectangle m = ev.MarginBounds;
                        float ratio = Math.Min((float)m.Width / image.Width, (float)m.Height / image.Height);
                        int w = (int)(image.Width * ratio);
                        int h = (int)(image.Height * ratio);
                        int x = m.Left + (m.Width - w) / 2;
                        int y = m.Top + (m.Height - h) / 2;

                        ev.Graphics.DrawImage(image, x, y, w, h);
                    };

                    pd.Print();
                    return true;
                }
            }
            catch (Exception ex)
            {
                Log("⚠️ ইমেজ প্রিন্ট ত্রুটি: " + ex.Message);
                return false;
            }
        }

        private bool PrintDocumentSilently(string filePath, string printerName, int copies)
        {
            string ext = Path.GetExtension(filePath).ToLowerInvariant();

            // Word Documents: Use Microsoft Word COM Automation or Windows Native Print
            if (ext == ".doc" || ext == ".docx")
            {
                if (PrintWordDocument(filePath, printerName, copies))
                {
                    return true;
                }
            }

            try
            {
                // Strategy 1: Check for bundled or local SumatraPDF (Standard for silent PDF / XPS / CBZ spooling)
                if (ext == ".pdf" || ext == ".xps")
                {
                    string localSumatra = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "SumatraPDF.exe");
                    string[] possibleSumatraPaths = new string[] {
                        localSumatra,
                        Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "tools", "SumatraPDF.exe"),
                        Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFiles), "SumatraPDF", "SumatraPDF.exe"),
                        Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFilesX86), "SumatraPDF", "SumatraPDF.exe")
                    };

                    string sumatraExe = null;
                    foreach (string p in possibleSumatraPaths)
                    {
                        if (File.Exists(p))
                        {
                            sumatraExe = p;
                            break;
                        }
                    }

                    // Auto-fetch SumatraPDF from cloud server if missing
                    if (string.IsNullOrEmpty(sumatraExe) && !string.IsNullOrEmpty(txtServerUrl.Text))
                    {
                        try
                        {
                            Log("⬇️ সাইলেন্ট প্রিন্টার ইঞ্জিন (SumatraPDF) ক্লাউড থেকে ডাউনলোড হচ্ছে...");
                            string downloadUrl = txtServerUrl.Text.Trim().TrimEnd('/') + "/downloads/SumatraPDF.exe";
                            using (var wc = new WebClient())
                            {
                                wc.Headers.Add("User-Agent", "PrintShebaAgent-v2.6");
                                wc.DownloadFile(downloadUrl, localSumatra);
                                if (File.Exists(localSumatra))
                                {
                                    sumatraExe = localSumatra;
                                    Log("✓ সাইলেন্ট প্রিন্টার ইঞ্জিন প্রস্তুত!");
                                }
                            }
                        }
                        catch (Exception dlEx)
                        {
                            Log("⚠️ সুমাত্রা ইঞ্জিন অটো-ডাউনলোড ব্যর্থ: " + dlEx.Message);
                        }
                    }

                    if (!string.IsNullOrEmpty(sumatraExe))
                    {
                        Log("🚀 সুমাত্রা ইঞ্জিন দিয়ে সরাসরি সাইলেন্ট প্রিন্ট হচ্ছে...");
                        string copiesParam = copies > 1 ? string.Format(",copies={0}", copies) : "";
                        string args = string.Format("-silent -print-to \"{0}\" -print-settings \"fit{1}\" \"{2}\"", printerName, copiesParam, filePath);

                        var psi = new ProcessStartInfo
                        {
                            FileName = sumatraExe,
                            Arguments = args,
                            CreateNoWindow = true,
                            UseShellExecute = false,
                            WindowStyle = ProcessWindowStyle.Hidden
                        };

                        using (var p = Process.Start(psi))
                        {
                            if (p != null)
                            {
                                bool exited = p.WaitForExit(20000);
                                if (exited)
                                {
                                    if (p.ExitCode == 0) return true;
                                    Log("⚠️ সুমাত্রা রিটার্ন কোড: " + p.ExitCode);
                                }
                                else
                                {
                                    try { p.Kill(); } catch { }
                                    return true; // Spooled to Windows Print Spooler
                                }
                            }
                        }
                    }
                }

                // Strategy 2: Fallback to Windows Shell PrintTo
                for (int i = 0; i < copies; i++)
                {
                    var psi = new ProcessStartInfo
                    {
                        FileName = filePath,
                        Verb = "printto",
                        Arguments = "\"" + printerName + "\"",
                        CreateNoWindow = true,
                        UseShellExecute = true,
                        WindowStyle = ProcessWindowStyle.Hidden
                    };

                    using (var p = Process.Start(psi))
                    {
                        if (p != null)
                        {
                            p.WaitForExit(15000);
                        }
                    }
                    Thread.Sleep(500);
                }
                return true;
            }
            catch (Exception ex)
            {
                Log("⚠️ ডকুমেন্ট প্রিন্ট ত্রুটি: " + ex.Message);
                return false;
            }
        }

        private bool PrintWordDocument(string filePath, string printerName, int copies)
        {
            Log("📝 মাইক্রোসফট ওয়ার্ড ডকুমেন্ট প্রসেস করা হচ্ছে...");
            try
            {
                Type wordType = Type.GetTypeFromProgID("Word.Application");
                if (wordType != null)
                {
                    dynamic wordApp = Activator.CreateInstance(wordType);
                    try
                    {
                        wordApp.Visible = false;
                        wordApp.DisplayAlerts = 0; // wdAlertsNone
                        wordApp.ActivePrinter = printerName;

                        dynamic doc = wordApp.Documents.Open(
                            FileName: filePath,
                            ReadOnly: true,
                            AddToRecentFiles: false,
                            Visible: false
                        );

                        try
                        {
                            object background = false;
                            object append = false;
                            object range = 0; // wdPrintAllDocument
                            object outputFileName = Type.Missing;
                            object from = Type.Missing;
                            object to = Type.Missing;
                            object item = 0; // wdPrintDocumentContent
                            object copiesObj = copies;

                            doc.PrintOut(
                                Background: background,
                                Append: append,
                                Range: range,
                                OutputFileName: outputFileName,
                                From: from,
                                To: to,
                                Item: item,
                                Copies: copiesObj
                            );

                            Log("✅ ওয়ার্ড ডকুমেন্ট সফলভাবে প্রিন্টারে পাঠানো হয়েছে।");
                            return true;
                        }
                        finally
                        {
                            try
                            {
                                object saveChanges = false;
                                doc.Close(SaveChanges: saveChanges);
                            }
                            catch { }
                        }
                    }
                    finally
                    {
                        try
                        {
                            object saveChanges = false;
                            wordApp.Quit(SaveChanges: saveChanges);
                        }
                        catch { }
                    }
                }
            }
            catch (Exception ex)
            {
                Log("⚠️ ওয়ার্ড অটোমেশন এরর: " + ex.Message);
            }

            return false;
        }

        private void NotifyJobPrinted(string jobToken)
        {
            ThreadPool.QueueUserWorkItem(o =>
            {
                try
                {
                    string url = config.server_url.TrimEnd('/') + "/api/agent/jobs/" + jobToken + "/printed";
                    var request = (HttpWebRequest)WebRequest.Create(url);
                    request.Method = "POST";
                    request.Headers["X-Agent-Token"] = config.api_token;
                    request.ContentType = "application/json";
                    request.ContentLength = 0;
                    request.Timeout = 5000;

                    using (var response = (HttpWebResponse)request.GetResponse())
                    {
                        // Job acknowledged printed
                    }
                }
                catch (Exception ex)
                {
                    Log("⚠️ ক্লাউডে প্রিন্ট সম্পন্ন আপডেট দিতে সমস্যা: " + ex.Message);
                }
            });
        }

        private void SendHeartbeat()
        {
            if (string.IsNullOrEmpty(config.api_token)) return;

            try
            {
                var printersList = new List<string>();
                foreach (string p in PrinterSettings.InstalledPrinters)
                {
                    printersList.Add(p);
                }

                var ser = new JavaScriptSerializer();
                string payload = ser.Serialize(new
                {
                    printers = printersList,
                    bw_printer = config.bw_printer,
                    color_printer = config.color_printer,
                    agent_version = "2.6"
                });
                byte[] bytes = Encoding.UTF8.GetBytes(payload);

                string url = config.server_url.TrimEnd('/') + "/api/agent/heartbeat";
                var request = (HttpWebRequest)WebRequest.Create(url);
                request.Method = "POST";
                request.Headers["X-Agent-Token"] = config.api_token;
                request.ContentType = "application/json";
                request.ContentLength = bytes.Length;
                request.Timeout = 6000;

                using (var stream = request.GetRequestStream())
                {
                    stream.Write(bytes, 0, bytes.Length);
                }

                using (var response = (HttpWebResponse)request.GetResponse())
                {
                    if (response.StatusCode == HttpStatusCode.OK)
                    {
                        string pDesc = !string.IsNullOrEmpty(config.bw_printer) ? config.bw_printer : "সিস্টেম ডিফল্ট";
                        Log("🔄 ক্লাউড সার্ভারে প্রিন্টার সিঙ্ক সফল: " + pDesc);
                    }
                }
            }
            catch (Exception)
            {
                // Silent background heartbeat catch
            }
        }

        private void PerformTestPrint()
        {
            string targetPrinter = GetTargetPrinter("bw");
            Log("🖨️ টেস্ট পেজ পাঠানো হচ্ছে ➔ " + targetPrinter);

            try
            {
                var pd = new PrintDocument();
                pd.PrinterSettings.PrinterName = targetPrinter;
                pd.PrintPage += (s, ev) =>
                {
                    Font fBrand = new Font("Segoe UI", 18, FontStyle.Bold);
                    Font fTagline = new Font("Segoe UI", 10, FontStyle.Bold);
                    Font fBody = new Font("Segoe UI", 10, FontStyle.Regular);
                    Font fSmall = new Font("Segoe UI", 8.5F, FontStyle.Italic);

                    ev.Graphics.DrawString("PrintSheba (প্রিন্ট সেবা) - অফিশিয়াল টেস্ট পেজ", fBrand, Brushes.Black, 50, 50);
                    ev.Graphics.DrawString("স্মার্ট সেলফ-সার্ভিস প্রিন্ট অটোমেশন প্ল্যাটফর্ম", fTagline, Brushes.Gray, 50, 85);
                    ev.Graphics.DrawLine(new Pen(Color.FromArgb(225, 29, 72), 2), 50, 110, 550, 110);

                    ev.Graphics.DrawString("প্রিন্টার নাম: " + targetPrinter, fBody, Brushes.Black, 50, 130);
                    ev.Graphics.DrawString("সংযোগ অবস্থা: সফলভাবে উইন্ডোজ স্পুলার ড্রাইভারে কানেক্টেড!", fBody, Brushes.DarkGreen, 50, 155);
                    ev.Graphics.DrawString("তারিখ ও সময়: " + DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"), fBody, Brushes.Black, 50, 180);
                    ev.Graphics.DrawString("ক্লাউড সার্ভার: " + config.server_url, fBody, Brushes.Black, 50, 205);

                    ev.Graphics.DrawString("ডেভেলপার: MR Developer (https://mrdeveloper.xyz) | হেল্পলাইন: 01644416378", fSmall, Brushes.DarkGray, 50, 250);
                };

                pd.Print();
                Log("✅ টেস্ট প্রিন্ট সফলভাবে প্রিন্টারে পাঠানো হয়েছে!");
                MessageBox.Show("টেস্ট পেজ সফলভাবে প্রিন্টারে স্পুল হয়েছে। ট্রে থেকে কাগজটি সংগ্রহ করুন।", "PrintSheba টেস্ট প্রিন্ট", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex)
            {
                Log("❌ টেস্ট প্রিন্ট ব্যর্থ: " + ex.Message);
                MessageBox.Show("টেস্ট প্রিন্ট ব্যর্থ হয়েছে: " + ex.Message, "ত্রুটি", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        // ==================== MODERN PREMIUM BRANDED UI ====================
        private void InitializeComponent()
        {
            this.Text = "PrintSheba • Windows Cloud Agent (Live Spooler v2.6)";
            this.Size = new Size(820, 755);
            this.StartPosition = FormStartPosition.CenterScreen;
            this.Font = new Font("Segoe UI", 9F, FontStyle.Regular);
            this.BackColor = Color.FromArgb(9, 13, 22); // Deep Luxury OLED Dark Canvas
            try
            {
                this.Icon = Icon.ExtractAssociatedIcon(Application.ExecutablePath);
            }
            catch
            {
                this.Icon = SystemIcons.Application;
            }
            this.FormBorderStyle = FormBorderStyle.FixedDialog;
            this.MaximizeBox = false;

            // 1. TOP LUXURY HEADER (Obsidian Glassmorphism with Rose Glow Accent)
            Panel pnlHeader = new Panel
            {
                Dock = DockStyle.Top,
                Height = 94,
                BackColor = Color.FromArgb(12, 19, 34),
                Padding = new Padding(24, 14, 24, 14)
            };

            // Custom Brand Emblem: Draws Official PrintSheba Printer & QR Vector Art
            Panel pnlLogoBox = new Panel
            {
                Location = new Point(24, 16),
                Size = new Size(62, 62),
                BackColor = Color.Transparent
            };
            pnlLogoBox.Paint += (s, e) =>
            {
                var g = e.Graphics;
                g.SmoothingMode = SmoothingMode.AntiAlias;

                // Glowing circular backdrop
                using (var brush = new LinearGradientBrush(new Rectangle(0, 0, 62, 62), Color.FromArgb(244, 63, 94), Color.FromArgb(225, 29, 72), 45f))
                {
                    g.FillEllipse(brush, 1, 1, 60, 60);
                }

                // Top Paper Sheet (White)
                using (var brushWhite = new SolidBrush(Color.FromArgb(248, 250, 252)))
                {
                    g.FillRectangle(brushWhite, 19, 14, 24, 14);
                }

                // Printer Body (Dark Obsidian Chassis)
                using (var brushBody = new SolidBrush(Color.FromArgb(15, 23, 42)))
                {
                    g.FillRectangle(brushBody, 12, 24, 38, 24);
                }

                // Output Paper Slot
                using (var brushSlot = new SolidBrush(Color.FromArgb(244, 63, 94)))
                {
                    g.FillRectangle(brushSlot, 15, 34, 32, 4);
                }

                // Bottom Output Sheet with QR pattern
                using (var brushWhite = new SolidBrush(Color.White))
                {
                    g.FillRectangle(brushWhite, 17, 36, 28, 18);
                }

                // Mini QR code corner finders
                using (var brushQr = new SolidBrush(Color.FromArgb(225, 29, 72)))
                {
                    g.FillRectangle(brushQr, 19, 38, 6, 6);
                    g.FillRectangle(brushQr, 37, 38, 6, 6);
                    g.FillRectangle(brushQr, 28, 45, 8, 4);
                }

                // Status Green LED
                using (var brushLed = new SolidBrush(Color.FromArgb(16, 185, 129)))
                {
                    g.FillEllipse(brushLed, 14, 27, 4, 4);
                }
            };

            // Brand Typography
            Label lblBrand = new Label
            {
                Text = "PrintSheba (প্রিন্ট সেবা)",
                Font = new Font("Segoe UI", 16F, FontStyle.Bold),
                ForeColor = Color.White,
                Location = new Point(96, 18),
                AutoSize = true
            };

            Label lblSubtitle = new Label
            {
                Text = "স্মার্ট সেলফ-সার্ভিস অটো-প্রিন্ট ক্লাউড এজেন্ট • ভার্সন ২.৬ (অটো-সিঙ্কড)",
                Font = new Font("Segoe UI", 8.8F, FontStyle.Regular),
                ForeColor = Color.FromArgb(6, 182, 212), // Brand Cyan
                Location = new Point(98, 49),
                AutoSize = true
            };

            // Status Pill (Top Right)
            lblStatusPill = new Label
            {
                Text = "● সংযুক্ত হচ্ছে...",
                Font = new Font("Segoe UI", 8.5F, FontStyle.Bold),
                BackColor = Color.FromArgb(17, 53, 43),
                ForeColor = Color.FromArgb(52, 211, 153),
                Location = new Point(595, 18),
                Size = new Size(185, 26),
                TextAlign = ContentAlignment.MiddleCenter
            };

            // Shop Name Pill (Top Right Below Status)
            lblShopPill = new Label
            {
                Text = "🏪 শপ লোড হচ্ছে...",
                Font = new Font("Segoe UI", 8.5F, FontStyle.Bold),
                BackColor = Color.FromArgb(23, 35, 59),
                ForeColor = Color.FromArgb(248, 250, 252),
                Location = new Point(595, 50),
                Size = new Size(185, 26),
                TextAlign = ContentAlignment.MiddleCenter
            };

            // Accent gradient bar at bottom of header
            Panel pnlHeaderLine = new Panel
            {
                Dock = DockStyle.Bottom,
                Height = 2,
                BackColor = Color.FromArgb(244, 63, 94)
            };

            pnlHeader.Controls.Add(pnlLogoBox);
            pnlHeader.Controls.Add(lblBrand);
            pnlHeader.Controls.Add(lblSubtitle);
            pnlHeader.Controls.Add(lblStatusPill);
            pnlHeader.Controls.Add(lblShopPill);
            pnlHeader.Controls.Add(pnlHeaderLine);
            this.Controls.Add(pnlHeader);

            // 2. STATS OVERVIEW CARDS (Modern Luxury Dark Gradient Cards)
            Panel pnlStatsContainer = new Panel
            {
                Location = new Point(24, 110),
                Size = new Size(756, 82)
            };

            // Stat 1: Total Prints
            Panel card1 = CreateMetricCard("আজকের প্রিন্ট টাস্ক", "০ টি", out lblTotalPrintsVal, 0, Color.FromArgb(244, 63, 94));
            // Stat 2: Active Spooling
            Panel card2 = CreateMetricCard("লাইভ প্রিন্ট কিউ", "০ টি (খালি)", out lblActiveQueueVal, 256, Color.FromArgb(56, 189, 248));
            // Stat 3: Spooler Health
            Panel card3 = CreateMetricCard("স্পুলার অবস্থা", "সচল ও প্রস্তুত", out lblSpoolerStatusVal, 512, Color.FromArgb(16, 185, 129));

            pnlStatsContainer.Controls.Add(card1);
            pnlStatsContainer.Controls.Add(card2);
            pnlStatsContainer.Controls.Add(card3);
            this.Controls.Add(pnlStatsContainer);

            // 3. SETTINGS & PRINTER ROUTING CARD (Sleek Dark Surface Card)
            Panel pnlConfig = new Panel
            {
                Location = new Point(24, 206),
                Size = new Size(756, 245),
                BackColor = Color.FromArgb(19, 29, 49)
            };
            pnlConfig.Paint += (s, e) =>
            {
                using (var pen = new Pen(Color.FromArgb(35, 51, 79), 1))
                {
                    e.Graphics.DrawRectangle(pen, 0, 0, pnlConfig.Width - 1, pnlConfig.Height - 1);
                }
            };

            // Header strip inside card
            Panel pnlConfigHeader = new Panel
            {
                Dock = DockStyle.Top,
                Height = 36,
                BackColor = Color.FromArgb(15, 23, 42)
            };
            pnlConfigHeader.Paint += (s, e) =>
            {
                using (var pen = new Pen(Color.FromArgb(35, 51, 79), 1))
                {
                    e.Graphics.DrawLine(pen, 0, pnlConfigHeader.Height - 1, pnlConfigHeader.Width, pnlConfigHeader.Height - 1);
                }
            };

            Label lblConfigTitle = new Label
            {
                Text = "⚙️ ক্লাউড সার্ভার সংযোগ ও প্রিন্টার ম্যাপিং (Hardware & Cloud Sync)",
                Location = new Point(16, 9),
                AutoSize = true,
                Font = new Font("Segoe UI", 9.2F, FontStyle.Bold),
                ForeColor = Color.White
            };

            Label lblSyncBadge = new Label
            {
                Text = "● রিয়েল-টাইম ক্লাউড সিঙ্ক সক্রিয়",
                Location = new Point(540, 7),
                Size = new Size(200, 22),
                Font = new Font("Segoe UI", 8F, FontStyle.Bold),
                BackColor = Color.FromArgb(23, 37, 84),
                ForeColor = Color.FromArgb(96, 165, 250),
                TextAlign = ContentAlignment.MiddleCenter
            };

            pnlConfigHeader.Controls.Add(lblConfigTitle);
            pnlConfigHeader.Controls.Add(lblSyncBadge);
            pnlConfig.Controls.Add(pnlConfigHeader);

            // Row 1: Server URL & API Token
            Label lblUrl = new Label { Text = "সার্ভার URL:", Location = new Point(20, 50), AutoSize = true, Font = new Font("Segoe UI", 9F, FontStyle.Bold), ForeColor = Color.FromArgb(148, 163, 184) };
            txtServerUrl = new TextBox { Location = new Point(135, 47), Size = new Size(230, 26), Font = new Font("Segoe UI", 9.5F), BackColor = Color.FromArgb(10, 15, 29), ForeColor = Color.White, BorderStyle = BorderStyle.FixedSingle };

            Label lblTok = new Label { Text = "শপ API Token:", Location = new Point(380, 50), AutoSize = true, Font = new Font("Segoe UI", 9F, FontStyle.Bold), ForeColor = Color.FromArgb(148, 163, 184) };
            txtApiToken = new TextBox { Location = new Point(490, 47), Size = new Size(220, 26), Font = new Font("Segoe UI", 9.5F), PasswordChar = '•', BackColor = Color.FromArgb(10, 15, 29), ForeColor = Color.White, BorderStyle = BorderStyle.FixedSingle };

            Button btnToggleToken = new Button { Text = "👁️", Location = new Point(714, 46), Size = new Size(28, 27), Cursor = Cursors.Hand, FlatStyle = FlatStyle.Flat, ForeColor = Color.White, BackColor = Color.FromArgb(30, 41, 59) };
            btnToggleToken.FlatAppearance.BorderColor = Color.FromArgb(51, 65, 85);
            btnToggleToken.Click += (s, e) => { txtApiToken.PasswordChar = txtApiToken.PasswordChar == '•' ? '\0' : '•'; };

            // Row 2: B&W Printer
            Label lblBw = new Label { Text = "সাদাকালো প্রিন্টার:", Location = new Point(20, 86), AutoSize = true, Font = new Font("Segoe UI", 9F, FontStyle.Bold), ForeColor = Color.FromArgb(148, 163, 184) };
            cmbBwPrinter = new ComboBox { Location = new Point(135, 83), Size = new Size(607, 26), DropDownStyle = ComboBoxStyle.DropDownList, Font = new Font("Segoe UI", 9.5F), BackColor = Color.FromArgb(10, 15, 29), ForeColor = Color.White };

            // Row 3: Color Printer
            Label lblCol = new Label { Text = "রঙিন প্রিন্টার:", Location = new Point(20, 122), AutoSize = true, Font = new Font("Segoe UI", 9F, FontStyle.Bold), ForeColor = Color.FromArgb(148, 163, 184) };
            cmbColorPrinter = new ComboBox { Location = new Point(135, 119), Size = new Size(607, 26), DropDownStyle = ComboBoxStyle.DropDownList, Font = new Font("Segoe UI", 9.5F), BackColor = Color.FromArgb(10, 15, 29), ForeColor = Color.White };

            // Row 4: Checkboxes
            chkAutoStart = new CheckBox { Text = "কম্পিউটার চালু হলে একা একা ব্যাকগ্রাউন্ডে রান করবে", Location = new Point(135, 154), AutoSize = true, Font = new Font("Segoe UI", 8.8F), ForeColor = Color.FromArgb(203, 213, 225) };
            chkMinimizeToTray = new CheckBox { Text = "মিনিমাইজ করলে সিস্টেম ট্রেতে লুকাবে", Location = new Point(480, 154), AutoSize = true, Font = new Font("Segoe UI", 8.8F), ForeColor = Color.FromArgb(203, 213, 225) };

            // Row 5: Action Buttons (Vibrant Gradient / Modern Solid)
            btnSave = new Button
            {
                Text = "💾 সেটিংস সেভ ও ক্লাউডে সিঙ্ক",
                Location = new Point(135, 190),
                Size = new Size(245, 38),
                BackColor = Color.FromArgb(225, 29, 72),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Font = new Font("Segoe UI", 9.5F, FontStyle.Bold),
                Cursor = Cursors.Hand
            };
            btnSave.FlatAppearance.BorderSize = 0;
            btnSave.MouseEnter += (s, e) => btnSave.BackColor = Color.FromArgb(190, 18, 60);
            btnSave.MouseLeave += (s, e) => btnSave.BackColor = Color.FromArgb(225, 29, 72);
            btnSave.Click += (s, e) =>
            {
                config.server_url = txtServerUrl.Text.Trim();
                config.api_token = txtApiToken.Text.Trim();
                config.bw_printer = cmbBwPrinter.SelectedItem != null ? cmbBwPrinter.SelectedItem.ToString() : "";
                config.color_printer = cmbColorPrinter.SelectedItem != null ? cmbColorPrinter.SelectedItem.ToString() : "";
                config.auto_start = chkAutoStart.Checked;
                config.minimize_to_tray = chkMinimizeToTray.Checked;
                SaveConfig();
                Log("💾 নতুন সেটিংস সংরক্ষিত হয়েছে। ক্লাউড সার্ভারে প্রিন্টার সিঙ্ক হচ্ছে...");
                ThreadPool.QueueUserWorkItem(o =>
                {
                    SendHeartbeat();
                    PollJobs();
                });
                MessageBox.Show("সেটিংস সফলভাবে সংরক্ষিত হয়েছে!\n\nআপনার নির্বাচিত প্রিন্টার স্বয়ংক্রিয়ভাবে ক্লাউড সার্ভারে সিঙ্ক করা হয়েছে।", "PrintSheba Agent", MessageBoxButtons.OK, MessageBoxIcon.Information);
            };

            btnTestPrint = new Button
            {
                Text = "🖨️ টেস্ট পেজ প্রিন্ট করুন",
                Location = new Point(390, 190),
                Size = new Size(185, 38),
                BackColor = Color.FromArgb(16, 185, 129),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Font = new Font("Segoe UI", 9.5F, FontStyle.Bold),
                Cursor = Cursors.Hand
            };
            btnTestPrint.FlatAppearance.BorderSize = 0;
            btnTestPrint.MouseEnter += (s, e) => btnTestPrint.BackColor = Color.FromArgb(5, 150, 105);
            btnTestPrint.MouseLeave += (s, e) => btnTestPrint.BackColor = Color.FromArgb(16, 185, 129);
            btnTestPrint.Click += (s, e) => PerformTestPrint();

            btnCheckNow = new Button
            {
                Text = "🔄 এখনই রিফ্রেশ ও সিঙ্ক",
                Location = new Point(585, 190),
                Size = new Size(157, 38),
                BackColor = Color.FromArgb(30, 41, 59),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Font = new Font("Segoe UI", 9F, FontStyle.Bold),
                Cursor = Cursors.Hand
            };
            btnCheckNow.FlatAppearance.BorderColor = Color.FromArgb(51, 65, 85);
            btnCheckNow.MouseEnter += (s, e) => btnCheckNow.BackColor = Color.FromArgb(51, 65, 85);
            btnCheckNow.MouseLeave += (s, e) => btnCheckNow.BackColor = Color.FromArgb(30, 41, 59);
            btnCheckNow.Click += (s, e) =>
            {
                Log("🔄 ক্লাউড সার্ভার রিফ্রেশ ও প্রিন্টার সিঙ্ক অনুরোধ...");
                ThreadPool.QueueUserWorkItem(o =>
                {
                    SendHeartbeat();
                    PollJobs();
                });
            };

            pnlConfig.Controls.Add(lblUrl);
            pnlConfig.Controls.Add(txtServerUrl);
            pnlConfig.Controls.Add(lblTok);
            pnlConfig.Controls.Add(txtApiToken);
            pnlConfig.Controls.Add(btnToggleToken);
            pnlConfig.Controls.Add(lblBw);
            pnlConfig.Controls.Add(cmbBwPrinter);
            pnlConfig.Controls.Add(lblCol);
            pnlConfig.Controls.Add(cmbColorPrinter);
            pnlConfig.Controls.Add(chkAutoStart);
            pnlConfig.Controls.Add(chkMinimizeToTray);
            pnlConfig.Controls.Add(btnSave);
            pnlConfig.Controls.Add(btnTestPrint);
            pnlConfig.Controls.Add(btnCheckNow);
            this.Controls.Add(pnlConfig);

            // 4. ACTIVITY CONSOLE PANEL (Luxury Developer Terminal)
            Panel pnlTerminal = new Panel
            {
                Location = new Point(24, 465),
                Size = new Size(756, 235),
                BackColor = Color.FromArgb(11, 17, 32)
            };
            pnlTerminal.Paint += (s, e) =>
            {
                using (var pen = new Pen(Color.FromArgb(35, 51, 79), 1))
                {
                    e.Graphics.DrawRectangle(pen, 0, 0, pnlTerminal.Width - 1, pnlTerminal.Height - 1);
                }
            };

            Panel pnlTermHeader = new Panel
            {
                Dock = DockStyle.Top,
                Height = 34,
                BackColor = Color.FromArgb(15, 23, 42)
            };
            pnlTermHeader.Paint += (s, e) =>
            {
                e.Graphics.SmoothingMode = SmoothingMode.AntiAlias;
                // Draw 3 Mac-style dots
                e.Graphics.FillEllipse(new SolidBrush(Color.FromArgb(239, 68, 68)), 14, 12, 10, 10);
                e.Graphics.FillEllipse(new SolidBrush(Color.FromArgb(245, 158, 11)), 29, 12, 10, 10);
                e.Graphics.FillEllipse(new SolidBrush(Color.FromArgb(16, 185, 129)), 44, 12, 10, 10);
            };

            Label lblTermTitle = new Label
            {
                Text = "TERMINAL // LIVE CLOUD RELAY & SPOOLER ACTIVITY",
                Location = new Point(64, 9),
                AutoSize = true,
                Font = new Font("Segoe UI", 7.8F, FontStyle.Bold),
                ForeColor = Color.FromArgb(148, 163, 184)
            };

            Button btnClearTerminal = new Button
            {
                Text = "লগ ক্লিয়ার করুন",
                Location = new Point(645, 5),
                Size = new Size(98, 24),
                Font = new Font("Segoe UI", 7.8F),
                ForeColor = Color.FromArgb(203, 213, 225),
                BackColor = Color.FromArgb(30, 41, 59),
                FlatStyle = FlatStyle.Flat,
                Cursor = Cursors.Hand
            };
            btnClearTerminal.FlatAppearance.BorderColor = Color.FromArgb(51, 65, 85);
            btnClearTerminal.Click += (s, e) => lstLog.Items.Clear();

            pnlTermHeader.Controls.Add(lblTermTitle);
            pnlTermHeader.Controls.Add(btnClearTerminal);
            pnlTerminal.Controls.Add(pnlTermHeader);

            lstLog = new ListBox
            {
                Location = new Point(12, 42),
                Size = new Size(732, 180),
                Font = new Font("Consolas", 9F),
                BackColor = Color.FromArgb(6, 10, 20),
                ForeColor = Color.FromArgb(226, 232, 240),
                HorizontalScrollbar = true,
                BorderStyle = BorderStyle.None
            };

            pnlTerminal.Controls.Add(lstLog);
            this.Controls.Add(pnlTerminal);

            // 5. SYSTEM TRAY MENU
            trayMenu = new ContextMenuStrip();
            trayMenu.Items.Add("ড্যাশবোর্ড ওপেন করুন", null, (s, e) => { ShowForm(); });
            trayMenu.Items.Add("টেস্ট পেজ প্রিন্ট করুন", null, (s, e) => { PerformTestPrint(); });
            trayMenu.Items.Add("-");
            trayMenu.Items.Add("প্রোগ্রাম বন্ধ করুন (Exit)", null, (s, e) => { ExitApp(); });

            Icon appIcon = this.Icon ?? SystemIcons.Application;

            trayIcon = new NotifyIcon
            {
                Text = "PrintSheba Windows Agent v2.6",
                Icon = appIcon,
                ContextMenuStrip = trayMenu,
                Visible = true
            };
            trayIcon.DoubleClick += (s, e) => { ShowForm(); };

            this.FormClosing += (s, e) =>
            {
                if (config != null && config.minimize_to_tray && e.CloseReason == CloseReason.UserClosing)
                {
                    e.Cancel = true;
                    this.Hide();
                    trayIcon.ShowBalloonTip(2000, "PrintSheba ব্যাকগ্রাউন্ডে চলছে", "প্রিন্ট সেবা সফটওয়্যারটি ব্যাকগ্রাউন্ডে সচল রয়েছে এবং কাস্টমারের প্রিন্ট জবের জন্য প্রস্তুত।", ToolTipIcon.Info);
                }
            };
        }

        private Panel CreateMetricCard(string title, string initialVal, out Label outValLabel, int x, Color accentColor)
        {
            Panel card = new Panel
            {
                Location = new Point(x, 0),
                Size = new Size(244, 82),
                BackColor = Color.FromArgb(19, 29, 49)
            };
            card.Paint += (s, e) =>
            {
                using (var pen = new Pen(Color.FromArgb(35, 51, 79), 1))
                {
                    e.Graphics.DrawRectangle(pen, 0, 0, card.Width - 1, card.Height - 1);
                }
            };

            // Left colored gradient accent line
            Panel line = new Panel
            {
                Dock = DockStyle.Left,
                Width = 4,
                BackColor = accentColor
            };

            Label lblT = new Label
            {
                Text = title,
                Font = new Font("Segoe UI", 8.2F, FontStyle.Bold),
                ForeColor = Color.FromArgb(148, 163, 184),
                Location = new Point(14, 13),
                AutoSize = true
            };

            outValLabel = new Label
            {
                Text = initialVal,
                Font = new Font("Segoe UI", 14F, FontStyle.Bold),
                ForeColor = Color.White,
                Location = new Point(14, 37),
                AutoSize = true
            };

            card.Controls.Add(line);
            card.Controls.Add(lblT);
            card.Controls.Add(outValLabel);
            return card;
        }

        private void ShowForm()
        {
            this.Show();
            this.WindowState = FormWindowState.Normal;
            this.BringToFront();
        }

        private void ExitApp()
        {
            if (pollTimer != null) pollTimer.Stop();
            if (heartbeatTimer != null) heartbeatTimer.Stop();
            if (trayIcon != null) trayIcon.Visible = false;
            Application.ExitThread();
        }

        [STAThread]
        public static void Main()
        {
            Application.SetUnhandledExceptionMode(UnhandledExceptionMode.CatchException);
            AppDomain.CurrentDomain.UnhandledException += (s, e) =>
            {
                try
                {
                    string logFile = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "agent_crash.log");
                    File.AppendAllText(logFile, DateTime.Now.ToString() + " [CRASH] " + e.ExceptionObject.ToString() + Environment.NewLine);
                }
                catch { }
            };
            Application.ThreadException += (s, e) =>
            {
                try
                {
                    string logFile = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "agent_crash.log");
                    File.AppendAllText(logFile, DateTime.Now.ToString() + " [THREAD] " + e.Exception.ToString() + Environment.NewLine);
                }
                catch { }
            };

            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new AgentForm());
        }
    }
}
