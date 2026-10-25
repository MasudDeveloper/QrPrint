# 🎬 PrintSheba (প্রিন্টসেবা) — Remotion Video Suite (Promo & 3 Tutorials)

প্রিন্টসেবার জন্য তৈরি অত্যাধুনিক মোশন গ্রাফিক্স প্রমোশনাল ভিডিও এবং **ওয়েবসাইটের আসল ইন্টারফেস (Real Website UI)** সংবলিত ৩টি পৃথক পুঙ্খানুপুঙ্খ ব্যবহার নির্দেশিকা (Walkthrough Video Tutorials)।

---

## 📽️ ভিডিওগুলোর তালিকা ও কম্পোজিশন

| কম্পোজিশন ID | ধরন | বিষয়বস্তু | দৈর্ঘ্য ও ফ্রেম | রেন্ডার কমান্ড |
|---|---|---|---|---|
| **`PrintShebaPromo`** | প্রমোশনাল ভিডিও | মোশন গ্রাফিক্স, প্রবলেম, সল্যুশন, ফিচার ও রূপান্তর | ১৪৯.৩৩ সেকেন্ড (৪৪৮০ ফ্রেম) | `npm run render` |
| **`Tutorial-Customer`** | ইউজার নির্দেশিকা | কাস্টমার সেলফ-সার্ভিস গাইড (QR স্ক্যান ➔ ফাইল ➔ অপশন ➔ প্রিন্ট) | ৬০.০০ সেকেন্ড (১৮০০ ফ্রেম) | `npm run render:customer` |
| **`Tutorial-Shopkeeper`** | শপ নির্দেশিকা (সংক্ষিপ্ত) | দোকানদারদের ৫ মিনিটে সেটআপ (রেট, প্রিন্টার, উইন্ডোজ এজেন্ট, পোস্টার, লাইভ সেলস) | ৯০.০০ সেকেন্ড (২৭০০ ফ্রেম) | `npm run render:shopkeeper` |
| **`Tutorial-Shopkeeper-Full`** | শপ নির্দেশিকা (পূর্ণাঙ্গ) | একাউন্ট রেজিস্ট্রেশন, রেট, প্রিন্টার, উইন্ডোজ এজেন্ট (.EXE), অ্যান্ড্রয়েড অ্যাপ (APK) ও পোস্টার | ১৩০.০০ সেকেন্ড (৩৯০০ ফ্রেম) | `npm run render:shopkeeper:full` |
| **`Tutorial-Office`** | অফিস নির্দেশিকা | অফিস ফ্রি প্রিন্ট এবং স্মার্ট এআই NID কার্ড অটো-ক্রপ ও A4 প্রিন্ট টুল | ৬০.০০ সেকেন্ড (১৮০০ ফ্রেম) | `npm run render:office` |

---

## 🚀 কীভাবে রিমোশন স্টুডিও (Remotion Studio) চালাবেন?

ভিডিওগুলো ব্রাউজারে লাইভ প্রিভিউ, প্লে, পজ, ফ্রেম স্ক্রাব ও ইন্টারঅ্যাক্টিভভাবে দেখার জন্য:

```bash
cd remotion_marketing
npm start
```

ব্রাউজারে স্টুডিও ওপেন হলে বাম পাশের মেনু থেকে যেকোনো টিউটোরিয়াল বা প্রমো ভিডিও সিলেক্ট করে সাথে সাথে প্লে করতে পারবেন!

---

## 🎙️ ইলেভেন ল্যাবস (ElevenLabs) ভয়েসওভার স্ক্রিপ্ট

আমরা ৩টি টিউটোরিয়াল এবং মূল প্রমো ভিডিওর জন্য নিখুঁত বাংলা স্ক্রিপ্ট তৈরি করে রেখেছি:
1. **৩টি টিউটোরিয়ালের ভয়েসওভার স্ক্রিপ্ট:**  
   👉 [VOICEOVER_TUTORIAL_SCRIPTS.md](./VOICEOVER_TUTORIAL_SCRIPTS.md)
2. **মাস্টার প্রমো ভিডিওর ভয়েসওভার স্ক্রিপ্ট:**  
   👉 [VOICEOVER_SCRIPT_ELEVENLABS.md](./VOICEOVER_SCRIPT_ELEVENLABS.md)

### ElevenLabs সেটিংস পরামর্শ:
- **Model:** Multilingual v2
- **Voice Tone:** সাবলীল, প্রাঞ্জল ও দিকনির্দেশনামূলক (Friendly, Instructive, Clear)
- **Stability:** 50%
- **Clarity + Similarity:** 80%

---

## 📹 MP4 ভিডিও রেন্ডার করার নিয়ম

যেকোনো ভিডিও ফুল এইচডি (1080p @ 30 FPS) ফরম্যাটে রেন্ডার করতে নিচের যেকোনো কমান্ড টার্মিনালে রান করুন:

### ১. কাস্টমার টিউটোরিয়াল রেন্ডার:
```bash
npm run render:customer
```
*(আউটপুট: `remotion_marketing/out/tutorial-customer.mp4`)*

### ২. দোকানদার পূর্ণাঙ্গ সেটআপ টিউটোরিয়াল রেন্ডার (উইন্ডোজ এজেন্ট ও অ্যান্ড্রয়েড অ্যাপসহ):
```bash
npm run render:shopkeeper:full
```
*(আউটপুট: `remotion_marketing/out/tutorial-shopkeeper-full.mp4`)*

### ৩. অফিস ও স্মার্ট NID টিউটোরিয়াল রেন্ডার:
```bash
npm run render:office
```
*(আউটপুট: `remotion_marketing/out/tutorial-office.mp4`)*

### ৪. মূল প্রমোশনাল মোশন গ্রাফিক্স ভিডিও রেন্ডার:
```bash
npm run render
```
*(আউটপুট: `remotion_marketing/out/printsheba-promo.mp4`)*

---

## 🖥️ বাস্তব ইন্টারফেস কম্পোনেন্টসমূহ (`src/components/ui_mockups/`)

এই টিউটোরিয়ালগুলোতে কোনো সাধারণ স্লাইড নয়, বরং সরাসরি আপনার তৈরি ব্লেড ভিউগুলোর পিক্সেল-পারফেক্ট ইন্টারঅ্যাক্টিভ রেপ্লিকা ব্যবহৃত হয়েছে:
1. **`MockCustomerMobileScreen.tsx`**: কাস্টমার মোবাইল ইন্টারফেস (`resources/views/customer/upload.blade.php`), ফাইল সিলেকশন, লাইভ অটো পেজ কাউন্ট, কালার/ডুপ্লেক্স ও লাইভ বিল।
2. **`MockShopDashboardScreen.tsx`**: দোকানদার ড্যাশবোর্ড (`resources/views/shop/dashboard.blade.php`), লাইভ রিলে ইন্ডিকেটর, প্রিন্ট কিউ ও ক্যাশ অনুমোদন।
3. **`MockRatesSetupScreen.tsx`**: রেট সেটিংস (`resources/views/shop/rates.blade.php`), সাদাকালো, কালার, ডুপ্লেক্স ও বিকাশ/নগদ ওয়ালেট।
4. **`MockPrintersScreen.tsx`**: প্রিন্টার সেটিংস (`resources/views/shop/printers.blade.php`), HP LaserJet ও Epson কালার অটো-রাউটিং।
5. **`MockAgentDownloadScreen.tsx`**: উইন্ডোজ এজেন্ট (`resources/views/shop/agent_setup.blade.php`), .EXE ডাউনলোড, API Token এবং Windows 11 ডেস্কটপ কনসোল প্রিভিউ।
6. **`MockQrPosterScreen.tsx`**: কাউন্টার পোস্টার জেনারেটর (`resources/views/shop/qr_poster.blade.php`), A4 হাই-রেস প্রিন্ট প্রিভিউ।
7. **`MockNidPortalScreen.tsx`**: স্মার্ট NID টুল (`resources/views/customer/office_nid_upload.blade.php`), সামনের ও পেছনের পিঠের ফটো স্ক্যান, এআই ক্রপ ও A4 শিট অ্যালাইনমেন্ট।
8. **`MockOfficeDirectPrintScreen.tsx`**: অফিস ডিরেক্ট প্রিন্ট (`resources/views/shop/quick_print.blade.php`), সরাসরি ফ্রি প্রিন্ট।
9. **`MockCursor.tsx`**: ইন্টারঅ্যাক্টিভ মাউস ও টাচ পয়েন্টার ক্লিকের অ্যানিমেশনসহ।
